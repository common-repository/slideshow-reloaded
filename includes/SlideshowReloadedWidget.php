<?php
/**
 * Class SlideshowReloadedWidget allows showing one of your slideshows in your widget area.
 *
 * @since 1.0.0
 * @author: Lerougeliet
 */
class SlideshowReloadedWidget extends WP_Widget {
	/** @var string $widgetName */
	public static $widgetName = 'Slideshow Reloaded';

	/**
	 * Initializes the widget
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Settings
		$options = array(
			'classname'   => 'SlideshowWidget',
			'description' => __('Enables you to show your slideshows in the widget area of your website.', 'slideshow-reloaded')
		);

		// Create the widget.
		parent::__construct(
			'slideshowWidget',
			__('Slideshow Widget', 'slideshow-reloaded'),
			$options
		);
	}

	/**
	 * The widget as shown to the user.
	 *
	 * @since 1.0.0
	 * @param mixed array $args
	 * @param mixed array $instance
	 */
	public function widget($args, $instance) {
		// Get slideshowId
		$slideshowId = '';
		if (isset($instance['slideshowId'])) {
			$slideshowId = $instance['slideshowId'];
		}

		// Get title
		$title = '';
		if (isset($instance['title'])) {
			$title = $instance['title'];
		}

		// Prepare slideshow for output to website.
		$output = SlideshowReloaded::prepare($slideshowId);

		$beforeWidget = $afterWidget = $beforeTitle = $afterTitle = '';
		if (isset($args['before_widget'])) {
			$beforeWidget = $args['before_widget'];
		}

		if (isset($args['after_widget'])) {
			$afterWidget = $args['after_widget'];
		}

		if (isset($args['before_title'])) {
			$beforeTitle = $args['before_title'];
		}

		if (isset($args['after_title'])) {
			$afterTitle = $args['after_title'];
		}

		// Output widget
		echo $beforeWidget . (!empty($title) ? $beforeTitle . $title . $afterTitle : '') . $output . $afterWidget;
	}

	/**
	 * The form shown on the admins widget page. Here settings can be changed.
	 *
	 * @since 1.0.0
	 * @param mixed array $instance
	 * @return string
	 */
	public function form($instance) {
		// Defaults
		$defaults = array(
			'title'       => __(self::$widgetName, 'slideshow-reloaded'),
			'slideshowId' => -1
		);

		// Merge database settings with defaults
		$instance = wp_parse_args((array) $instance, $defaults);

		// Get slideshows
		$slideshows = get_posts(array(
			'numberposts' => -1,
			'offset'      => 0,
			'post_type'   => SlideshowReloadedPostType::$postType
		));

		$data              = new stdClass();
		$data->widget      = $this;
		$data->instance   = $instance;
		$data->slideshows = $slideshows;

		// Include form
		SlideshowReloadedMain::outputView(__CLASS__ . DIRECTORY_SEPARATOR . 'form.php', $data);
	}

	/**
	 * Updates widget's settings.
	 *
	 * @since 1.0.0
	 * @param mixed array $newInstance
	 * @param mixed array $instance
	 * @return mixed array $instance
	 */
	public function update($newInstance, $instance) {
		// Update title
		if (isset($newInstance['title'])) {
			$instance['title'] = $newInstance['title'];
		}

		// Update slideshowId
		if (isset($newInstance['slideshowId']) &&
			!empty($newInstance['slideshowId'])) {
			$instance['slideshowId'] = $newInstance['slideshowId'];
		}

		// Save
		return $instance;
	}

	/**
	 * Registers this widget (should be called upon widget_init action hook)
	 *
	 * @since 1.0.0
	 */
	public static function registerWidget() {
		register_widget(__CLASS__);
	}
}
