<?php if ($data instanceof stdClass) : ?>
	<table>
		<?php if (count($data->settings) > 0): $i = 0 ?>

		<?php foreach ($data->settings as $key => $value): ?>

		<?php if (!isset($value, $value['type'], $value['default'], $value['description']) || !is_array($value)) {
	continue;
} ?>

		<tr <?php if (isset($value['dependsOn'])) {
	echo 'style="display:none;"';
} ?>>
			<td><?php echo $value['description'] ?></td>
			<td><?php echo SlideshowReloadedSlideshowSettingsHandler::getInputField(esc_html(SlideshowReloadedSlideshowSettingsHandler::$styleSettingsKey), $key, $value) ?></td>
			<td><?php _e('Default', 'slideshow-reloaded') ?>: &#39;<?php echo (isset($value['options']))? $value['options'][$value['default']]: $value['default'] ?>&#39;</td>
		</tr>

		<?php endforeach ?>

		<?php endif ?>
	</table>
<?php endif ?>
