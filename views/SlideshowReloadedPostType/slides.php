<?php if ($data instanceof stdClass) : ?>

	<p style="text-align: center; font-style: italic"><?php _e('Insert', 'slideshow-reloaded') ?>:</p>
	<p style="text-align: center;">
		<input type="button" class="button slideshow-insert-image-slide" value="<?php _e('Media slide', 'slideshow-reloaded') ?>" />
		<input type="button" class="button slideshow-insert-text-slide" value="<?php _e('Text slide', 'slideshow-reloaded') ?>" />
		<input type="button" class="button slideshow-insert-video-slide" value="<?php _e('Youtube slide', 'slideshow-reloaded') ?>" />
	</p>

	<p style="text-align: center;">
		<a href="#" class="open-slides-button"><?php _e('Open all', 'slideshow-reloaded') ?></a> |
		<a href="#" class="close-slides-button"><?php _e('Close all', 'slideshow-reloaded') ?></a>
	</p>

	<?php if (count($data->slides) <= 0) : ?>
		<p><?php _e('Add slides to this slideshow by using one of the buttons above.', 'slideshow-reloaded') ?></p>
	<?php endif ?>

	<div class="sortable-slides-list">

		<?php

		if (is_array($data->slides)) {
			$i = 0;

			foreach ($data->slides as $slide) {
				$data             = new stdClass();
				$data->name       = SlideshowReloadedSlideshowSettingsHandler::$slidesKey . '[' . $i . ']';
				$data->properties = $slide;

				SlideshowReloadedMain::outputView('SlideshowReloadedSlideshowSlide' . DIRECTORY_SEPARATOR . 'backend_' . $slide['type'] . '.php', $data);

				$i++;
			}
		}

		?>

	</div>

	<?php SlideshowReloadedMain::outputView('SlideshowReloadedSlideshowSlide' . DIRECTORY_SEPARATOR . 'backend_templates.php') ?>

<?php endif ?>
