<?php

if ($data instanceof stdClass):

	$properties = $data->properties;

	$title = $description = $url = $urlTarget = $alternativeText = $noFollow = $postId = '';

	$titleElementTag = $descriptionElementTag = SlideshowReloadedFunctions::getElementTag();

	if (isset($properties['title'])) {
		$title = trim(SlideshowReloadedFunctions::htmlspecialchars_allow_exceptions($properties['title']));
	}

	if (isset($properties['titleElementTagID'])) {
		$titleElementTag = SlideshowReloadedFunctions::getElementTag($properties['titleElementTagID']);
	}

	if (isset($properties['description'])) {
		$description = trim(SlideshowReloadedFunctions::htmlspecialchars_allow_exceptions($properties['description']));
	}

	if (isset($properties['descriptionElementTagID'])) {
		$descriptionElementTag = SlideshowReloadedFunctions::getElementTag($properties['descriptionElementTagID']);
	}

	if (isset($properties['url'])) {
		$url = $properties['url'];
	}

	if (isset($properties['urlTarget'])) {
		$urlTarget = $properties['urlTarget'];
	}

	if (isset($properties['alternativeText'])) {
		$alternativeText = $properties['alternativeText'];
	}

	if (isset($properties['noFollow'])) {
		$noFollow = ' rel="nofollow" ';
	}

	if (isset($properties['postId'])) {
		$postId = $properties['postId'];
	}

	// Post ID should always be numeric
	if (is_numeric($postId)):

		$anchorTag = $endAnchorTag = $anchorTagAttributes = '';

		if ($url) {
			$anchorTagAttributes =
				'href="' . esc_attr($url) . '" ' .
				($urlTarget ? 'target="' . esc_attr($urlTarget) . '" ' : '') .
				$noFollow;

			$anchorTag    = '<a ' . $anchorTagAttributes . '>';
			$endAnchorTag = '</a>';
		}

		// Get post from post id. Post should be able to load
		$attachment = get_post($postId);
		if (!empty($attachment)):

			// If no alternative text is set, get the alt from the original image
			if (empty($alternativeText)) {
				$alternativeText = $title;

				if (empty($alternativeText)) {
					$alternativeText = $attachment->post_title;
				}

				if (empty($alternativeText)) {
					$alternativeText = $attachment->post_content;
				}
			}

			// Prepare image
			$image          = wp_get_attachment_image_src($attachment->ID, 'full');
			$imageSrc       = '';
			$imageWidth     = 0;
			$imageHeight    = 0;
			$imageAvailable = true;

			if (!is_array($image) ||
				!$image ||
				!isset($image[0])) {
				if (!empty($attachment->guid)) {
					$imageSrc = $attachment->guid;
				} else {
					$imageAvailable = false;
				}
			} else {
				$imageSrc = $image[0];

				if (isset($image[1], $image[2])) {
					$imageWidth  = $image[1];
					$imageHeight = $image[2];
				}
			}

			// If image is available
			if ($imageAvailable): ?>

				<div class="slideshow_slide slideshow_slide_image">
					<?php echo $anchorTag ?>
					<?php if (in_array(end(explode('.', $imageSrc)), array('avi', 'flv', 'wmv', 'mov', 'mp4', 'webm', '3gp', 'ogg'))) { ?>
						<video <?php echo ($imageWidth > 0) ? 'width="' . $imageWidth . '"' : '' ?> <?php echo ($imageHeight > 0) ? 'height="' . $imageHeight . '"' : '' ?> controls>
						  <source src="<?php echo esc_attr($imageSrc) ?>#t=0.01" />
						</video>
					<?php } else { ?>
						<?php if (!SlideshowReloadedGeneralSettings::getEnableLazyLoading() || $slide_idx === 0) { ?>
							<img src="<?php echo esc_attr($imageSrc) ?>" alt="<?php echo esc_attr($alternativeText) ?>" <?php echo ($imageWidth > 0) ? 'width="' . $imageWidth . '"' : '' ?> <?php echo ($imageHeight > 0) ? 'height="' . $imageHeight . '"' : '' ?> />
						<?php } else { ?>
							<img data-src="<?php echo esc_attr($imageSrc) ?>" alt="<?php echo esc_attr($alternativeText) ?>" <?php echo ($imageWidth > 0) ? 'width="' . $imageWidth . '"' : '' ?> <?php echo ($imageHeight > 0) ? 'height="' . $imageHeight . '"' : '' ?> />
						<?php } ?>
					<?php } ?>
					<?php echo $endAnchorTag ?>
					<div class="slideshow_description_box slideshow_transparent">
						<?php echo !empty($title) ? '<' . $titleElementTag . ' class="slideshow_title">' . $anchorTag . $title . $endAnchorTag . '</' . $titleElementTag . '>' : '' ?>
						<?php echo !empty($description) ? '<' . $descriptionElementTag . ' class="slideshow_description">' . $anchorTag . do_shortcode(wpautop($description)) . $endAnchorTag . '</' . $descriptionElementTag . '>' : '' ?>
					</div>
				</div>

			<?php endif ?>
		<?php endif ?>
	<?php endif ?>
<?php endif ?>
