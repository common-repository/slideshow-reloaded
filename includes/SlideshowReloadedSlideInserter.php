<?php
/**
 * Class SlideshowReloadedSlideInserter
 *
 * @since 1.0.0
 * @author Lerougeliet
 */
class SlideshowReloadedSlideInserter {

	/**
	 * Enqueues styles and scripts necessary for the media upload button.
	 *
	 * @since 1.0.0
	 */
	public static function localizeScript() {
		// Return if function doesn't exist
		if (!function_exists('get_current_screen')) {
			return;
		}

		// Return when not on a slideshow edit page
		$currentScreen = get_current_screen();

		if ($currentScreen->post_type != SlideshowReloadedPostType::$postType) {
			return;
		}

		wp_localize_script(
			'slideshow-reloaded-backend-script',
			'slideshow_reloaded_backend_script_editSlideshow',
			array(
				'data' => array(),
				'localization' => array(
					'confirm'       => __('Are you sure you want to delete this slide?', 'slideshow-reloaded'),
					'uploaderTitle' => __('Insert media slide', 'slideshow-reloaded')
				)
			)
		);
	}
}
