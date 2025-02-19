<?php
/**
 * Class SlideshowReloadedShortcode provides the shortcode function, which is called
 * on use of shortcode anywhere in the posts and pages. Also provides the shortcode
 * inserter, so that it's made easier for non-programmers to insert the shortcode
 * into a post or page.
 *
 * @since 1.0.0
 * @author: Lerougeliet
 */
class SlideshowReloadedShortcode {
	/** @var string $shortCode */
	public static $shortCode = 'slideshow_reloaded';

	/** @var string $shortCodeOld */
  public static $shortCodeOld = 'slideshow_deploy';

	/** @var string $bookmark */
	public static $bookmark = '!slideshow_reloaded!';

	/** @var array $postIDs */
	private static $postIds = array();

	/**
	 * Initializes the shortcode, registering it and hooking the shortcode
	 * inserter media buttons.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		// Register shortcode
		add_shortcode(self::$shortCode, array(__CLASS__, 'slideshowDeploy'));
		add_shortcode(self::$shortCodeOld, array(__CLASS__, 'slideshowDeploy'));

		// Admin
		if (is_admin()) {
			// Add shortcode inserter HTML
			add_action('media_buttons', array(__CLASS__, 'shortcodeInserter'), 11);

			// Enqueue shortcode inserter script
			add_action('admin_enqueue_scripts', array(__CLASS__, 'localizeScript'), 11);
		}
	}

	/**
	 * Function slideshowDeploy adds a bookmark to where ever a shortcode
	 * is found and adds the postId to an array, it then is loaded after
	 * WordPress has done its HTML checks.
	 *
	 * @since 1.0.0
	 * @param mixed $attributes
	 * @return String $output
	 */
	public static function slideshowDeploy($attributes) {
		$postId = '';

		if (isset($attributes['id'])) {
			$postId = $attributes['id'];
		}

		$settings = SlideshowReloadedSlideshowSettingsHandler::getSettings($postId);

		if ($settings['avoidFilter'] == 'true' &&
			strlen(current_filter()) > 0) {
			// Avoid current filter, call function to replace the bookmark with the slideshow
			add_filter(current_filter(), array(__CLASS__, 'insertSlideshow'), 999);

			// Save post id
			self::$postIds[] = $postId;

			// Set output
			$output = self::$bookmark;
		} else {
			// Just output the slideshow, without filtering
			$output = SlideshowReloaded::prepare($postId);
		}

		// Return output
		return $output;
	}

	/**
	 * Function insertSlideshow uses the prepare method of class SlideshowReloaded
	 * to insert the code for the slideshow on the location a bookmark was found.
	 *
	 * @since 1.0.0
	 * @param String $content
	 * @return String $content
	 */
	public static function insertSlideshow($content) {
		// Loop through post ids
		if (is_array(self::$postIds) &&
			count(self::$postIds) > 0) {
			foreach (self::$postIds as $postId) {
				$updatedContent = preg_replace("/" . self::$bookmark . "/", SlideshowReloaded::prepare($postId), $content, 1);

				if (is_string($updatedContent)) {
					$content = $updatedContent;
				}
			}
		}

		// Reset postIds, so a shortcode in a next post can be used
		self::$postIds = array();

		return $content;
	}

	/**
	 * Hooked on the admin's 'media_buttons' hook, outputs the shortcode inserter media button
	 *
	 * @since 1.0.0
	 */
	public static function shortcodeInserter() {
		$data             = new stdClass();
		$data->slideshows = new WP_Query(array(
			'post_type'      => SlideshowReloadedPostType::$postType,
			'orderby'        => 'post_date',
			'posts_per_page' => -1,
			'order'          => 'DESC'
		));

		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'shortcode-inserter.php', $data);
	}

	/**
	 * Enqueues the shortcode inserter script
	 *
	 * @since 1.0.0
	 */
	public static function localizeScript() {
		wp_localize_script(
			'slideshow-reloaded-backend-script',
			'slideshow_reloaded_backend_script_shortcode',
			array(
				'data' => array('shortcode' => SlideshowReloadedShortcode::$shortCode),
				'localization' => array('undefinedSlideshow' => __('No slideshow selected.', 'slideshow-reloaded'))
			)
		);
	}
}
