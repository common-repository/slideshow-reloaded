<?php

if ($data instanceof stdClass) :

	$properties = $data->properties;

	$title = $description = $textColor = $color = $url = $urlTarget = $noFollow = '';

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

	if (isset($properties['textColor'])) {
		$textColor = $properties['textColor'];

		if (substr($textColor, 0, 1) != '#') {
			$textColor = '#' . $textColor;
		}

		$textColor = esc_attr($textColor);
	}

	if (isset($properties['color'])) {
		$color = $properties['color'];

		if (substr($color, 0, 1) != '#') {
			$color = '#' . $color;
		}
	}

	if (isset($properties['url'])) {
		$url = $properties['url'];
	}

	if (isset($properties['urlTarget'])) {
		$urlTarget = $properties['urlTarget'];
	}

	if (isset($properties['noFollow'])) {
		$noFollow = 'rel="nofollow"';
	}

	$anchorTag = $endAnchorTag = $anchorTagAttributes = '';

	if (strlen($url) > 0) {
		$anchorTagAttributes =
			'href="' . esc_attr($url) . '" ' .
			(strlen($urlTarget) > 0 ? 'target="' . esc_attr($urlTarget) . '" ' : '') .
			(strlen($textColor) > 0 ? 'style="color: ' . $textColor . '" ' : '') .
			$noFollow;

		$anchorTag    = '<a ' . $anchorTagAttributes . '>';
		$endAnchorTag = '</a>';
	}

	?>

	<div class="slideshow_slide slideshow_slide_text" style="<?php echo $color ? 'background-color: ' . esc_attr($color) : '' ?>">
		<?php if (strlen($title) > 0): ?>
		<<?php echo $titleElementTag ?> class="slideshow_title" style="<?php echo strlen($textColor) > 0 ? 'color: ' . $textColor . ';' : '' ?>">
			<?php echo $anchorTag ?>
				<?php echo $title ?>
			<?php echo $endAnchorTag ?>
		</<?php echo $titleElementTag ?>>
		<?php endif ?>

		<?php if (strlen($description) > 0): ?>
		<<?php echo $descriptionElementTag ?> class="slideshow_description" style="<?php echo strlen($textColor) > 0 ? 'color: ' . $textColor . ';' : '' ?>">
			<?php echo $anchorTag ?>
				<?php echo do_shortcode(wpautop($description)) ?>
			<?php echo $endAnchorTag ?>
		</<?php echo $descriptionElementTag ?>>
		<?php endif ?>

		<a <?php echo $anchorTagAttributes ?> class="slideshow_background_anchor"></a>
	</div>
<?php endif ?>
