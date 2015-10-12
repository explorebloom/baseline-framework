<?php
namespace Baseline\Services\Settings\Traits;

trait HandlesSections {

	public function sections()
	{
		// Call wordpress functions that display the settings.
		echo '<form method="post" action="options.php">';
		settings_fields($this->options['id']);
		do_settings_sections($this->options['id']);
		submit_button();
		echo '</form>';
	}

}