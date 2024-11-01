<?php
/**
 * SlideshowReloadedSlideshowStylesheet handles the loading of the slideshow's stylesheets.
 *
 * @since 1.0.0
 * @author Lerougeliet
 */
class SlideshowReloadedSlideshowStylesheet {
	/** @var bool $allStylesheetsRegistered */
	public static $allStylesheetsRegistered = false;

	/**
	 * Initializes the SlideshowReloadedSlideshowStylesheet class
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueueFrontendStylesheets'));
	}

	/**
	 * Enqueue stylesheet
	 */
	public static function enqueueFrontendStylesheets() {
		if (SlideshowReloadedGeneralSettings::getStylesheetLocation() === 'head') {
			// Register functional stylesheet
			wp_enqueue_style(
				'slideshow-reloaded-stylesheet_functional',
				SlideshowReloadedFunctions::getPluginUrl() . '/public/css/main.css',
				array(),
				SlideshowReloadedMain::$version
			);

			// Get default stylesheets
			$stylesheets        = SlideshowReloadedGeneralSettings::getStylesheets(true, true);
			$defaultStylesheets = $stylesheets['default'];

			// Clean the '.css' extension from the default stylesheets
			foreach ($defaultStylesheets as $defaultStylesheetKey => $defaultStylesheetValue) {
				$newDefaultStylesheetKey = str_replace('.css', '', $defaultStylesheetKey);

				$defaultStylesheets[$newDefaultStylesheetKey] = $defaultStylesheetValue;

				if ($defaultStylesheetKey !== $newDefaultStylesheetKey) {
					unset($defaultStylesheets[$defaultStylesheetKey]);
				}
			}

			self::$allStylesheetsRegistered = true;
		}
	}

	/**
	 * Enqueues a stylesheet based on the stylesheet's name. This can either be a default stylesheet or a custom one.
	 * If the name parameter is left unset, the default stylesheet will be used.
	 *
	 * Returns the name and version number of the stylesheet that's been enqueued, as this can be different from the
	 * name passed. This can be this case if a stylesheet does not exist and a default stylesheet is enqueued.
	 *
	 * @param string $name (optional, defaults to null)
	 * @return array [$name, $version]
	 */
	public static function enqueueStylesheet($name = null) {
		$version = SlideshowReloadedMain::$version;

		if (isset($name)) {
			$name = str_replace('.css', '', $name);
		} else {
			$name = 'style-light';
		}

		wp_enqueue_style(
			'slideshow-reloaded-stylesheet_' . $name,
			SlideshowReloadedFunctions::getPluginUrl() . '/public/css/' . $name . '.css',
			array(),
			$version
		);

		return array($name, $version);
	}
}
