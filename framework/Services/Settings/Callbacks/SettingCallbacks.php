<?php
namespace Baseline\Services\Settings\Callbacks;

use Baseline\Services\Settings\MakesSettingFields;

class SettingCallbacks {

	/**
	 * Holds the page's options.
	 */
	public $options;

	/**
	 * Holds the value of this Setting Objects type.
	 */
	public $type = 'setting';

	/**
	 * This is the function shows the tab's content.
	 */
	public function callback()
	{
		$setting_field = new MakesSettingFields;
		$setting_field->makeFromOptions($this->options);
	}

	/**
	 * This sets all of the page's properties to the callback class.
	 */
	public function setProperties($options)
	{
		$this->options = $options;
	}

}