<?php
/**
 * SlideshowReloadedGeneralSettings provides a sub menu page for the slideshow post type. The general settings page is
 * the page that holds most of the slideshow's overall settings, such as user capabilities and slideshow defaults.
 *
 * @since 1.0.0
 * @author Lerougeliet
 */
class SlideshowReloadedGeneralSettings {
	/** @var bool $isCurrentPage Flag that represents whether or not the general settings page is the current page */
	public static $isCurrentPage = false;

	/** @var string $settingsGroup Settings group */
	public static $settingsGroup = 'slideshow-reloaded-general-settings';

	/** @var string $stylesheetLocation Stylesheet location setting */
	public static $stylesheetLocation = 'slideshow-reloaded-stylesheet-location';

	/** @var string $enableLazyLoading Lazy loading setting */
	public static $enableLazyLoading = 'slideshow-reloaded-enable-lazy-loading';

	/** @var array $capabilities User capability settings */
	public static $capabilities = array(
		'addSlideshows'    => 'slideshow-reloaded-add-slideshows',
		'editSlideshows'   => 'slideshow-reloaded-edit-slideshows',
		'deleteSlideshows' => 'slideshow-reloaded-delete-slideshows'
	);

	/** @var string $defaultSettings */
	public static $defaultSettings = 'slideshow-reloaded-default-settings';
	/** @var string $defaultStyleSettings */
	public static $defaultStyleSettings = 'slideshow-reloaded-default-style-settings';

	/**
	 * Initializes the slideshow post type's general settings.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		if (get_option('slideshow-reloaded-last-fetch-upsell') === false) {
			add_option('slideshow-reloaded-last-fetch-upsell', time());
		} else if (time() - get_option('slideshow-reloaded-last-fetch-upsell') > 7 * 24 * 60 * 60
			&& (!defined('DOING_AJAX') || !DOING_AJAX) && (!defined('DOING_CRON') || !DOING_CRON)) {
			update_option('slideshow-reloaded-last-fetch-upsell', time());
			SlideshowReloadedFunctions::fetch_upsell();
		}

		// Only initialize in admin
		if (!is_admin()) {
			return;
		}

		if (isset($_GET['post_type']) &&
			$_GET['post_type'] == 'slideshow' &&
			isset($_GET['page']) &&
			$_GET['page'] == 'general_settings') {
			self::$isCurrentPage = true;
		}

		// Register settings
		add_action('admin_init', array(__CLASS__, 'registerSettings'));

		// Add sub menu
		add_action('admin_menu', array(__CLASS__, 'addSubMenuPage'));

		// Localize
		add_action('admin_enqueue_scripts', array(__CLASS__, 'localizeScript'), 11);
	}

	/**
	 * Adds a sub menu page to the slideshow post type menu.
	 *
	 * @since 1.0.0
	 */
	public static function addSubMenuPage() {
		// Return if the slideshow post type does not exist
		if (!post_type_exists(SlideshowReloadedPostType::$postType)) {
			return;
		}

		// Add sub menu
		add_submenu_page(
			'edit.php?post_type=' . SlideshowReloadedPostType::$postType,
			__('General Settings', 'slideshow-reloaded'),
			__('General Settings', 'slideshow-reloaded'),
			'manage_options',
			'general_settings',
			array(__CLASS__, 'generalSettings')
		);
	}

	/**
	 * Shows the general settings page.
	 *
	 * @since 1.0.0
	 */
	public static function generalSettings() {
		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'general-settings.php');
	}

	/**
	 * Registers required settings into the WordPress settings API.
	 * Only performed when actually on the general settings page.
	 *
	 * @since 1.0.0
	 */
	public static function registerSettings() {
		// Register settings only when the user is going through the options.php page
		$urlParts = explode('/', $_SERVER['PHP_SELF']);

		if (array_pop($urlParts) != 'options.php') {
			return;
		}

		// Register general settings
		register_setting(self::$settingsGroup, self::$stylesheetLocation);
		register_setting(self::$settingsGroup, self::$enableLazyLoading);

		// Register user capability settings, saving capabilities only has to be called once.
		register_setting(self::$settingsGroup, self::$capabilities['addSlideshows']);
		register_setting(self::$settingsGroup, self::$capabilities['editSlideshows']);
		register_setting(self::$settingsGroup, self::$capabilities['deleteSlideshows'], array(__CLASS__, 'saveCapabilities'));

		// Register default slideshow settings
		register_setting(self::$settingsGroup, self::$defaultSettings);
		register_setting(self::$settingsGroup, self::$defaultStyleSettings);
	}

	/**
	 * Localizes the general settings script. Needs to be called on the 'admin_enqueue_scripts' hook.
	 */
	public static function localizeScript() {
		if (!self::$isCurrentPage) {
			return;
		}

		// Localize general settings script
		wp_localize_script(
			'slideshow-reloaded-backend-script',
			'slideshow_reloaded_backend_script_generalSettings',
			array(
				'data'         => array(),
				'localization' => array(
					'newCustomizationPrefix' => __('New', 'slideshow-reloaded'),
					'confirmDeleteMessage'   => __('Are you sure you want to delete this custom style?', 'slideshow-reloaded')
				)
			)
		);
	}

	/**
	 * Returns the stylesheet location, or 'footer' when no stylesheet position has been defined yet.
	 *
	 * @since 1.0.0
	 * @return string $stylesheetLocation
	 */
	public static function getStylesheetLocation() {
		return get_option(SlideshowReloadedGeneralSettings::$stylesheetLocation, 'footer');
	}

	/**
	 * Returns the lazy loading setting, which is disabled (false) by default.
	 *
	 * @since 1.0.0
	 * @return boolean $enableLazyLoading
	 */
	public static function getEnableLazyLoading() {
		return get_option(self::$enableLazyLoading, false) === "true";
	}

	/**
	 * Returns an array of stylesheets with its keys and respective names.
	 *
	 * Gets the version number for each stylesheet when $withVersion is set to true.
	 *
	 * When the $separateDefaultFromCustom boolean is set to true, the default stylesheets will be returned separately
	 * from the custom stylesheets.
	 *
	 * The data returned with both parameters set to 'false' will look like the following:
	 *
	 * [$stylesheetKey => $stylesheetName]
	 *
	 * With both parameters set to 'true' the returned data will be formed like this:
	 *
	 * [
	 *  default => [$stylesheetKey => [name => $stylesheetName, version => $versionNumber]],
	 *  custom => [$stylesheetKey => [name => $stylesheetName, version => $versionNumber]]
	 * ]
	 *
	 * @since 1.0.0
	 * @param boolean $withVersion (optional, defaults to false)
	 * @param boolean $separateDefaultFromCustom (optional, defaults to false)
	 * @return array $stylesheets
	 */
	public static function getStylesheets($withVersion = false, $separateDefaultFromCustom = false) {
		// Default styles
		$defaultStyles = array(
			'style-light.css' => __('Light', 'slideshow-reloaded'),
			'style-dark.css'  => __('Dark', 'slideshow-reloaded')
		);

		// Loop through default stylesheets
		$stylesheetsFilePath = SlideshowReloadedFunctions::getPluginPath() . DIRECTORY_SEPARATOR . 'public/css';

		foreach ($defaultStyles as $fileName => $name) {
			// Check if stylesheet exists on server, don't offer it when it does not exist.
			if (!file_exists($stylesheetsFilePath . DIRECTORY_SEPARATOR . $fileName)) {
				unset($defaultStyles[$fileName]);

				continue;
			}

			// Add version if $withVersion is true
			if ($withVersion) {
				$defaultStyles[$fileName] = array('name' => $name, 'version' => SlideshowReloadedMain::$version);
			}
		}

		// Return
		if ($separateDefaultFromCustom) {
			return array(
				'default' => $defaultStyles,
			);
		}

		return $defaultStyles;
	}

	/**
	 * Saves capabilities, called by a callback from a registered capability setting
	 *
	 * @since 1.0.0
	 * @param String $capability
	 * @return String $capability
	 */
	public static function saveCapabilities($capability) {
		// Verify nonce
		$nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';

		if (!wp_verify_nonce($nonce, self::$settingsGroup . '-options')) {
			return $capability;
		}

		// Roles
		global $wp_roles;

		// Loop through available user roles
		foreach ($wp_roles->roles as $roleSlug => $roleValues) {
			// Continue when the capabilities are either not set or are no array
			if (!is_array($roleValues) ||
				!isset($roleValues['capabilities']) ||
				!is_array($roleValues['capabilities'])) {
				continue;
			}

			// Get role
			$role = get_role($roleSlug);

			// Continue when role is not set
			if ($role == null) {
				continue;
			}

			// Loop through available capabilities
			foreach (self::$capabilities as $capabilitySlug) {
				// If $roleSlug is present in $_POST's capability, add the capability to the role, otherwise remove the capability from the role.
				if ((isset($_POST[$capabilitySlug]) && is_array($_POST[$capabilitySlug]) && array_key_exists($roleSlug, $_POST[$capabilitySlug])) ||
					$roleSlug == 'administrator') {
					$role->add_cap($capabilitySlug);
				} else {
					$role->remove_cap($capabilitySlug);
				}
			}
		}

		return $capability;
	}
}
