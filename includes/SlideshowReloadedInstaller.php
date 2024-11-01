<?php
/**
 * SlideshowReloadedInstaller takes care of setting up slideshow setting values and transferring to newer version without
 * losing any settings.
 *
 * @since 1.0.0
 * @author Lerougeliet
 */
class SlideshowReloadedInstaller {
	/** @var string $versionKey Version option key */
	private static $versionKey = 'slideshow-reloaded-plugin-version';

	/**
	 * Determines whether or not to perform an update to the plugin.
	 * Checks are only performed when on admin pages as not to slow down the website.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		// Only check versions in admin
		if (!is_admin()) {
			return;
		}

		// Transfer if no version number is set, or the new version number is greater than the current one saved in the database
		$currentVersion = get_option(self::$versionKey, null);
		if ($currentVersion == null ||
			self::firstVersionGreaterThanSecond(SlideshowReloadedMain::$version, $currentVersion)) {
			self::update($currentVersion);
		}

		// New installation
		if ($currentVersion == null) {
			// Set up capabilities
			self::setCapabilities();
			add_action('wp', array(__CLASS__, 'deactivateOld'));
		}
	}

	/**
	 * Updates user to correct version
	 *
	 * @since 1.0.0
	 * @param string $currentVersion
	 */
	private static function update($currentVersion) {
		// Set new version
		update_option(self::$versionKey, SlideshowReloadedMain::$version);
	}

	/**
	 * Sets capabilities for the default users that have access to creating, updating and deleting slideshows.
	 *
	 * @since 1.0.0
	 */
	private static function setCapabilities() {
		// Check if update has already been done
		if (get_option('slideshow-reloaded-roles-updated') !== false) {
			return;
		}

		// Capabilities
		$addSlideshows   = 'slideshow-reloaded-add-slideshows';
		$editSlideshows  = 'slideshow-reloaded-edit-slideshows';
		$deleteSlideshow = 'slideshow-reloaded-delete-slideshows';

		// Add capabilities to roles
		$roles = array('administrator', 'editor', 'author');

		foreach ($roles as $roleName) {
			// Get role
			$role = get_role($roleName);

			// Continue on non-existent role
			if ($role == null) {
				continue;
			}

			// Add capability to role
			$role->add_cap($addSlideshows);
			$role->add_cap($editSlideshows);
			$role->add_cap($deleteSlideshow);
		}

		// Register as updated
		add_option('slideshow-reloaded-roles-updated', 'updated', '', false);
	}

	/**
	 * Checks if the version input first is greater than the version input second.
	 *
	 * Version numbers are noted as such: x.x.x
	 *
	 * @since 1.0.0
	 * @param String $firstVersion
	 * @param String $secondVersion
	 * @return boolean $firstGreaterThanSecond
	 */
	private static function firstVersionGreaterThanSecond($firstVersion, $secondVersion) {
		// Return false if $firstVersion is not set
		if (empty($firstVersion) ||
			!is_string($firstVersion)) {
			return false;
		}

		// Return true if $secondVersion is not set
		if (empty($secondVersion) ||
			!is_string($secondVersion)) {
			return true;
		}

		// Separate main, sub and bug-fix version number from one another.
		$firstVersion  = explode('.', $firstVersion);
		$secondVersion = explode('.', $secondVersion);

		// Compare version numbers per piece
		for ($i = 0; $i < count($firstVersion); $i++) {
			if (isset($firstVersion[$i], $secondVersion[$i])) {
				if ($firstVersion[$i] > $secondVersion[$i]) {
					return true;
				} elseif ($firstVersion[$i] < $secondVersion[$i]) {
					return false;
				}
			} else {
				return false;
			}
		}

		// Return false by default
		return false;
	}

	public static function deactivateOld() {
		error_log('deactivateOld');
		if (!function_exists('deactivate_plugins')) {
			require_once(ABSPATH . 'wp-admin/includes/plugin.php');
		}
		deactivate_plugins('slideshow-jquery-image-gallery/slideshow.php');
	}
}
