<?php

if ($data instanceof stdClass) :

	// General settings
	$stylesheetLocation = SlideshowReloadedGeneralSettings::getStylesheetLocation();
	$enableLazyLoading  = SlideshowReloadedGeneralSettings::getEnableLazyLoading();

	// Roles
	global $wp_roles;

	// Capabilities
	$capabilities = array(
		SlideshowReloadedGeneralSettings::$capabilities['addSlideshows']    => __('Add slideshows', 'slideshow-reloaded'),
		SlideshowReloadedGeneralSettings::$capabilities['editSlideshows']   => __('Edit slideshows', 'slideshow-reloaded'),
		SlideshowReloadedGeneralSettings::$capabilities['deleteSlideshows'] => __('Delete slideshows', 'slideshow-reloaded')
	);

	?>

	<div class="general-settings-tab feature-filter">

		<h4><?php _e('User Capabilities', 'slideshow-reloaded') ?></h4>

		<p><?php _e('Select the user roles that will able to perform certain actions.', 'slideshow-reloaded');  ?></p>

		<table>

			<?php foreach ($capabilities as $capability => $capabilityName): ?>

			<tr valign="top">
				<th><?php echo $capabilityName ?></th>
				<td>
					<?php

					if (isset($wp_roles->roles) && is_array($wp_roles->roles)):
						foreach ($wp_roles->roles as $roleSlug => $values):

							$disabled = ($roleSlug == 'administrator') ? 'disabled="disabled"' : '';
							$checked = ((isset($values['capabilities']) && array_key_exists($capability, $values['capabilities']) && $values['capabilities'][$capability] == true) || $roleSlug == 'administrator') ? 'checked="checked"' : '';
							$name = (isset($values['name'])) ? $values['name'] : __('Untitled role', 'slideshow-reloaded');

							?>

							<input
								type="checkbox"
								name="<?php echo esc_attr($capability) ?>[<?php echo esc_attr($roleSlug) ?>]"
								id="<?php echo esc_attr($capability . '_' . $roleSlug) ?>"
								<?php echo $disabled ?>
								<?php echo $checked ?>
							/>
							<label for="<?php echo esc_attr($capability . '_' . $roleSlug) ?>"><?php echo esc_html($name) ?></label>
							<br />

							<?php endforeach ?>
						<?php endif ?>

				</td>
			</tr>

			<?php endforeach ?>

		</table>
	</div>

	<div class="general-settings-tab feature-filter">

		<h4><?php _e('Settings', 'slideshow-reloaded') ?></h4>

		<table>
			<tr>
				<td><?php _e('Stylesheet location', 'slideshow-reloaded') ?></td>
				<td>
					<select name="<?php echo SlideshowReloadedGeneralSettings::$stylesheetLocation ?>">
						<option value="head" <?php selected('head', $stylesheetLocation) ?>>Head (<?php _e('top', 'slideshow-reloaded') ?>)</option>
						<option value="footer" <?php selected('footer', $stylesheetLocation) ?>>Footer (<?php _e('bottom', 'slideshow-reloaded') ?>)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><?php _e('Enable lazy loading', 'slideshow-reloaded') ?></td>
				<td>
					<input type="radio" name="<?php echo SlideshowReloadedGeneralSettings::$enableLazyLoading ?>" <?php checked(true, $enableLazyLoading) ?> value="true" /> <?php _e('Yes', 'slideshow-reloaded') ?>
					<input type="radio" name="<?php echo SlideshowReloadedGeneralSettings::$enableLazyLoading ?>" <?php checked(false, $enableLazyLoading) ?> value="false" /> <?php _e('No', 'slideshow-reloaded') ?>
				</td>
			</tr>
		</table>

	</div>
<?php endif ?>
