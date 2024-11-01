<?php

if ($data instanceof stdClass) :

	$properties = $data->properties;

	$name = esc_attr($data->name);

	$videoId           = '';
	$showRelatedVideos = 'false';

	if (isset($properties['videoId'])) {
		$videoId = esc_attr($properties['videoId']);
	}

	if (isset($properties['showRelatedVideos']) &&
		$properties['showRelatedVideos'] === 'true') {
		$showRelatedVideos = 'true';
	}

	?>

	<div class="widefat sortable-slides-list-item postbox">

		<div class="handlediv" title="<?php _e('Click to toggle') ?>"><br></div>

		<div class="hndle">
			<div class="slide-icon video-slide-icon"></div>
			<div class="slide-title">
				<?php _e('Youtube slide', 'slideshow-reloaded') ?>
			</div>
			<div class="clear"></div>
		</div>

		<div class="inside">

			<div class="slideshow-group">

				<div class="slideshow-label"><?php _e('Youtube Video ID', 'slideshow-reloaded') ?></div>
				<input type="text" name="<?php echo $name ?>[videoId]" value="<?php echo $videoId ?>" style="width: 100%;" />

			</div>

			<div class="slideshow-group">

				<div class="slideshow-label"><?php _e('Show related videos', 'slideshow-reloaded') ?></div>
				<label><input type="radio" name="<?php echo $name ?>[showRelatedVideos]" value="true" <?php checked('true', $showRelatedVideos) ?>><?php _e('Yes', 'slideshow-reloaded') ?></label>
				<label><input type="radio" name="<?php echo $name ?>[showRelatedVideos]" value="false" <?php checked('false', $showRelatedVideos) ?>><?php _e('No', 'slideshow-reloaded') ?></label>

			</div>

			<div class="slideshow-group slideshow-delete-slide">
				<span><?php _e('Delete slide', 'slideshow-reloaded') ?></span>
			</div>

			<input type="hidden" name="<?php echo $name ?>[type]" value="video" />

		</div>

	</div>
<?php endif ?>
