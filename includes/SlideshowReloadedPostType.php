<?php
/**
 * SlideshowReloadedPostType creates a post type specifically designed for
 * slideshows and their individual settings
 *
 * @since 1.0.0
 * @author: Lerougeliet
 */
class SlideshowReloadedPostType {
	/** @var string $postType */
	public static $postType = 'slideshow';

	/**
	 * Initialize Slideshow post type.
	 * Called on load of plugin
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		add_action('init', array(__CLASS__, 'registerSlideshowPostType'));
		add_action('save_post', array('SlideshowReloadedSlideshowSettingsHandler', 'save'));
		add_action('admin_enqueue_scripts', array('SlideshowReloadedSlideInserter', 'localizeScript'), 11);

		add_action('admin_action_slideshow_reloaded_duplicate_slideshow', array(__CLASS__, 'duplicateSlideshow'), 11);

		add_filter('post_updated_messages', array(__CLASS__, 'alterSlideshowMessages'));
		add_filter('post_row_actions', array(__CLASS__, 'duplicateSlideshowActionLink'), 10, 2);
	}

	/**
	 * Registers new post type slideshow
	 *
	 * @since 1.0.0
	 */
	public static function registerSlideshowPostType() {
		global $wp_version;

		register_post_type(
			self::$postType,
			array(
				'labels'               => array(
					'name'               => __('Slideshows', 'slideshow-reloaded'),
					'singular_name'      => __('Slideshow', 'slideshow-reloaded'),
					'add_new_item'       => __('Add New Slideshow', 'slideshow-reloaded'),
					'edit_item'          => __('Edit slideshow', 'slideshow-reloaded'),
					'new_item'           => __('New slideshow', 'slideshow-reloaded'),
					'view_item'          => __('View slideshow', 'slideshow-reloaded'),
					'search_items'       => __('Search slideshows', 'slideshow-reloaded'),
					'not_found'          => __('No slideshows found', 'slideshow-reloaded'),
					'not_found_in_trash' => __('No slideshows found', 'slideshow-reloaded')
				),
				'public'               => false,
				'publicly_queryable'   => false,
				'show_ui'              => true,
				'show_in_menu'         => true,
				'query_var'            => true,
				'rewrite'              => true,
				'capability_type'      => 'post',
				'capabilities'         => array(
					'edit_post'              => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],
					'read_post'              => SlideshowReloadedGeneralSettings::$capabilities['addSlideshows'],
					'delete_post'            => SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'],
					'edit_posts'             => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],
					'edit_others_posts'      => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],
					'publish_posts'          => SlideshowReloadedGeneralSettings::$capabilities['addSlideshows'],
					'read_private_posts'     => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],

					'read'                   => SlideshowReloadedGeneralSettings::$capabilities['addSlideshows'],
					'delete_posts'           => SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'],
					'delete_private_posts'   => SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'],
					'delete_published_posts' => SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'],
					'delete_others_posts'    => SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'],
					'edit_private_posts'     => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],
					'edit_published_posts'   => SlideshowReloadedGeneralSettings::$capabilities['editSlideshows'],
				),
				'has_archive'          => true,
				'hierarchical'         => false,
				'menu_position'        => null,
				'menu_icon'            => version_compare($wp_version, '3.8', '<') ? SlideshowReloadedFunctions::getPluginUrl() . '/public/images/admin-icon.png' : 'dashicons-format-gallery',
				'supports'             => array('title'),
				'register_meta_box_cb' => array(__CLASS__, 'registerMetaBoxes')
			)
		);
	}

	/**
	 * Adds custom meta boxes to slideshow post type.
	 *
	 * @since 1.0.0
	 */
	public static function registerMetaBoxes() {
		add_meta_box(
			'information',
			__('Information', 'slideshow-reloaded'),
			array(__CLASS__, 'informationMetaBox'),
			self::$postType,
			'normal',
			'high'
		);

		add_meta_box(
			'slides-list',
			__('Slides List', 'slideshow-reloaded'),
			array(__CLASS__, 'slidesMetaBox'),
			self::$postType,
			'normal',
			'default'
		);

		add_meta_box(
			'style',
			__('Slideshow Style', 'slideshow-reloaded'),
			array(__CLASS__, 'styleMetaBox'),
			self::$postType,
			'side',
			'low'
		);

		add_meta_box(
			'settings',
			__('Slideshow Settings', 'slideshow-reloaded'),
			array(__CLASS__, 'settingsMetaBox'),
			self::$postType,
			'normal',
			'low'
		);
	}

	/**
	 * Changes the "Post published/updated" message to a "Slideshow created/updated" message without the link to a
	 * frontend page.
	 *
	 * @since 1.0.0
	 * @param mixed $messages
	 * @return mixed $messages
	 */
	public static function alterSlideshowMessages($messages) {
		if (!function_exists('get_current_screen')) {
			return $messages;
		}

		$currentScreen = get_current_screen();

		// Return when not on a slideshow edit page
		if ($currentScreen->post_type != SlideshowReloadedPostType::$postType) {
			return $messages;
		}

		$messageID = filter_input(INPUT_GET, 'message', FILTER_VALIDATE_INT);

		if (!$messageID) {
			return $messages;
		}

		switch ($messageID) {
			case 6:
				$messages[$currentScreen->base][$messageID] = __('Slideshow created', 'slideshow-reloaded');
				break;

			default:
				$messages[$currentScreen->base][$messageID] = __('Slideshow updated', 'slideshow-reloaded');
		}

		return $messages;
	}

	/**
	 * Shows some information about this slideshow
	 *
	 * @since 1.0.0
	 */
	public static function informationMetaBox() {
		global $post;

		$data            = new stdClass();
		$data->snippet   = htmlentities(sprintf('<?php do_action(\'slideshow_reloaded\', \'%s\') ?>', $post->ID));
		$data->shortCode = htmlentities(sprintf('[' . SlideshowReloadedShortcode::$shortCode . ' id=\'%s\']', $post->ID));

		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'information.php', $data);
	}

	/**
	 * Shows slides currently in slideshow
	 *
	 * @since 1.0.0
	 */
	public static function slidesMetaBox() {
		global $post;

		$data         = new stdClass();
		$data->slides = SlideshowReloadedSlideshowSettingsHandler::getSlides($post->ID);

		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'slides.php', $data);
	}

	/**
	 * Shows style used for slideshow
	 *
	 * @since 1.0.0
	 */
	public static function styleMetaBox() {
		global $post;

		$data           = new stdClass();
		$data->settings = SlideshowReloadedSlideshowSettingsHandler::getStyleSettings($post->ID, true);

		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'style-settings.php', $data);
	}

	/**
	 * Shows settings for particular slideshow
	 *
	 * @since 1.0.0
	 */
	public static function settingsMetaBox() {
		global $post;

		// Nonce
		wp_nonce_field(SlideshowReloadedSlideshowSettingsHandler::$nonceAction, SlideshowReloadedSlideshowSettingsHandler::$nonceName);

		$data           = new stdClass();
		$data->settings = SlideshowReloadedSlideshowSettingsHandler::getSettings($post->ID, true);

		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'settings.php', $data);
	}

	/**
	 * Hooked on the post_row_actions filter, adds a "duplicate" action to each slideshow on the slideshow's overview
	 * page.
	 *
	 * @param array $actions
	 * @param WP_Post $post
	 * @return array $actions
	 */
	public static function duplicateSlideshowActionLink($actions, $post) {
		if (current_user_can('slideshow-reloaded-add-slideshows') &&
			$post->post_type === self::$postType) {
			$url = add_query_arg(array(
				'action' => 'slideshow_reloaded_duplicate_slideshow',
				'post'   => $post->ID,
			));

			$actions['duplicate'] = '<a href="' . wp_nonce_url($url, 'duplicate-slideshow_' . $post->ID, 'nonce') . '">' . __('Duplicate', 'slideshow-reloaded') . '</a>';
		}

		return $actions;
	}

	/**
	 * Checks if a "duplicate" slideshow action was performed and whether or not the current user has the permission to
	 * perform this action at all.
	 */
	public static function duplicateSlideshow() {
		$postID           = filter_input(INPUT_GET, 'post', FILTER_VALIDATE_INT);
		$nonce            = filter_input(INPUT_GET, 'nonce', FILTER_SANITIZE_STRING);
		$postType         = filter_input(INPUT_GET, 'post_type', FILTER_SANITIZE_STRING);
		$errorRedirectURL = remove_query_arg(array('action', 'post', 'nonce'));

		// Check if nonce is correct and user has the correct privileges
		if (!wp_verify_nonce($nonce, 'duplicate-slideshow_' . $postID) ||
			!current_user_can('slideshow-reloaded-add-slideshows') ||
			$postType !== self::$postType) {
			wp_redirect($errorRedirectURL);

			die();
		}

		$post = get_post($postID);

		// Check if the post was retrieved successfully
		if (!$post instanceof WP_Post ||
			$post->post_type !== self::$postType) {
			wp_redirect($errorRedirectURL);

			die();
		}

		$current_user = wp_get_current_user();

		// Create post duplicate
		$newPostID = wp_insert_post(array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $current_user->ID,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title . (strlen($post->post_title) > 0 ? ' - ' : '') . __('Copy', 'slideshow-reloaded'),
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order,
		));

		if (is_wp_error($newPostID)) {
			wp_redirect($errorRedirectURL);

			die();
		}

		// Get all taxonomies
		$taxonomies = get_object_taxonomies($post->post_type);

		// Add taxonomies to new post
		foreach ($taxonomies as $taxonomy) {
			$postTerms = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'slugs'));

			wp_set_object_terms($newPostID, $postTerms, $taxonomy, false);
		}

		// Get all post meta
		$postMetaRecords = get_post_meta($post->ID);

		// Add post meta records to new post
		foreach ($postMetaRecords as $postMetaKey => $postMetaValues) {
			foreach ($postMetaValues as $postMetaValue) {
				update_post_meta($newPostID, $postMetaKey, maybe_unserialize($postMetaValue));
			}
		}

		wp_redirect(admin_url('post.php?action=edit&post=' . $newPostID));

		die();
	}
}
