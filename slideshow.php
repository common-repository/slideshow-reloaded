<?php
/*
 Plugin Name: Slideshow Reloaded
 Plugin URI: http://wordpress.org/extend/plugins/slideshow-reloaded/
 Description: The Slideshow Reloaded plugin is easily deployable on your website. Add any image that has already been uploaded to add to your slideshow, add text slides, or even add a video. Options and styles are customizable for every single slideshow on your website.
 Version: 1.0.2
 Requires at least: 3.5
 Author: Lerougeliet
 Author URI: https://profiles.wordpress.org/lerougeliet/
 License: GPLv2
 Text Domain: slideshow-reloaded
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Class SlideshowReloadedMain fires up the application on plugin load and provides some
 * methods for the other classes to use like the auto-includer and the
 * base path/url returning method.
 *
 * @since 1.0.0
 * @author Lerougeliet
 */
class SlideshowReloadedMain {
	/** @var string $version */
	public static $version = '1.0.1';

	/**
	 * Bootstraps the application by assigning the right functions to
	 * the right action hooks.
	 *
	 * @since 1.0.0
	 */
	public static function bootStrap() {
		require_once plugin_dir_path(__FILE__) . 'includes/class-functions.php';
		SlideshowReloadedFunctions::autoInclude();

		// Initialize localization on init
		require_once plugin_dir_path(__FILE__) . 'includes/class-i18n.php';
		add_action('init', array('SlideshowReloadedI18n', 'load_plugin_textdomain'));

		// Enqueue hooks
		add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueueFrontendScripts'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueueBackendScripts'));

		// Register slideshow post type
		SlideshowReloadedPostType::init();

		// Add general settings page
		SlideshowReloadedGeneralSettings::init();

		// Initialize stylesheet builder
		SlideshowReloadedSlideshowStylesheet::init();

		// Deploy slideshow on do_action('slideshow_reloaded'); hook.
		add_action('slideshow_reloaded', array('SlideshowReloaded', 'deploy'));

		// Initialize shortcode
		SlideshowReloadedShortcode::init();

		// Register widget
		add_action('widgets_init', array('SlideshowReloadedWidget', 'registerWidget'));

		// Initialize plugin updater
		SlideshowReloadedInstaller::init();
	}

	/**
	 * Enqueues frontend scripts and styles.
	 *
	 * Should always be called on the wp_enqueue_scripts hook.
	 *
	 * @since 1.0.0
	 */
	public static function enqueueFrontendScripts() {
		// Enqueue slideshow script if lazy loading is enabled
		if (SlideshowReloadedGeneralSettings::getEnableLazyLoading()) {
			wp_enqueue_script(
				'slideshow-reloaded-script',
				SlideshowReloadedFunctions::getPluginUrl() . '/public/js/main.js',
				array('jquery'),
				self::$version
			);

			wp_localize_script(
				'slideshow-reloaded-script',
				'slideshow_reloaded_script_adminURL',
				admin_url()
			);
		}
	}

	/**
	 * Enqueues backend scripts and styles.
	 *
	 * Should always be called on the admin_enqueue_scrips hook.
	 *
	 * @since 1.0.0
	 */
	public static function enqueueBackendScripts() {
		// Function get_current_screen() should be defined, as this method is expected to fire at 'admin_enqueue_scripts'
		if (!function_exists('get_current_screen')) {
			return;
		}

		$currentScreen = get_current_screen();

		// Enqueue 3.5 uploader
		if ($currentScreen->post_type === 'slideshow' &&
			function_exists('wp_enqueue_media')) {
			wp_enqueue_media();
		}

		wp_enqueue_script(
			'slideshow-reloaded-backend-script',
			SlideshowReloadedFunctions::getPluginUrl() . '/public/js/admin.js',
			array(
				'jquery',
				'jquery-ui-sortable',
				'wp-color-picker'
			),
			SlideshowReloadedMain::$version
		);

		wp_enqueue_style(
			'slideshow-reloaded-backend-style',
			SlideshowReloadedFunctions::getPluginUrl() . '/public/css/admin.css',
			array(
				'wp-color-picker'
			),
			SlideshowReloadedMain::$version
		);
	}

	/**
	 * Outputs the passed view. It's good practice to pass an object like an stdClass to the $data variable, as it can
	 * be easily checked for validity in the view itself using "instanceof".
	 *
	 * @since 1.0.0
	 * @param string   $view
	 * @param stdClass $data (Optional, defaults to stdClass)
	 */
	public static function outputView($view, $data = null) {
		if (!($data instanceof stdClass)) {
			$data = new stdClass();
		}

		$file = SlideshowReloadedFunctions::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view;

		if (file_exists($file)) {
			include $file;
		}
	}

	/**
	 * Uses self::outputView to render the passed view. Returns the rendered view instead of outputting it.
	 *
	 * @since 1.0.0
	 * @param string   $view
	 * @param stdClass $data (Optional, defaults to null)
	 * @return string
	 */
	public static function getView($view, $data = null) {
		ob_start();
		self::outputView($view, $data);
		return ob_get_clean();
	}
}

/**
 * Activate plugin
 */
SlideshowReloadedMain::bootStrap();
