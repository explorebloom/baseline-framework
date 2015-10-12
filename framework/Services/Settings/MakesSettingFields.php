<?php
namespace Baseline\Services\Settings;

use Baseline\Helper\IsSingleton;

class MakesSettingFields {

	use IsSingleton;

	public function text($setting_options)
	{
		// Pull out the variables.
		extract($setting_options);

		// Get the option and see if the specific key is set.
		$options = get_option($section);
		$value = '';
		if(isset($options[$id])) {
		    $value = $options[$id];
		}

		// Create the text input.
		echo '<input type="text"';
		echo ' id="' . $id . '"';
		echo ' class="regular-text"';
		echo ' name="' . $section . '[' . $id . ']"';
		echo ' value="' . $value . '" />';
	}

	public function dropdown()
	{

	}

	public function radio()
	{

	}

	public function checkbox()
	{

	}

}