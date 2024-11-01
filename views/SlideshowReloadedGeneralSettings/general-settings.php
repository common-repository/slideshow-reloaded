<?php

if ($data instanceof stdClass) :

	// Path to the General Settings' views folder
	$generalSettingsViewsPath = SlideshowReloadedFunctions::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'SlideshowReloadedGeneralSettings' . DIRECTORY_SEPARATOR;

	?>

	<div class="wrap">
		<form method="post" action="options.php">
			<?php settings_fields(SlideshowReloadedGeneralSettings::$settingsGroup) ?>

			<div class="icon32" style="background: url('<?php echo SlideshowReloadedFunctions::getPluginUrl() . '/public/images/admin-icon32.png' ?>');"></div>
			<h2 class="nav-tab-wrapper">
				<a href="#general-settings-tab" class="nav-tab nav-tab-active"><?php _e('General Settings', 'slideshow-reloaded') ?></a>
				<a href="#default-slideshow-settings-tab" class="nav-tab"><?php _e('Default Slideshow Settings', 'slideshow-reloaded') ?></a>

				<?php submit_button(null, 'primary', null, false, 'style="float: right;"') ?>
			</h2>

			<?php

			// General Settings
			SlideshowReloadedMain::outputView('SlideshowReloadedGeneralSettings' . DIRECTORY_SEPARATOR . 'general-settings-tab.php');

			// Default slideshow settings
			SlideshowReloadedMain::outputView('SlideshowReloadedGeneralSettings' . DIRECTORY_SEPARATOR . 'default-slideshow-settings-tab.php');

			?>

			<p>
				<?php submit_button(null, 'primary', null, false) ?>
			</p>
		</form>
	</div>
<?php endif ?>
