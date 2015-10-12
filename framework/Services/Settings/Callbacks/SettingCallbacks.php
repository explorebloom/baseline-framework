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
	 * An instance of the Setting Fields class.
	 */
	protected $fields;

	/**
	 * Builds up the Class and sets up the settings.
	 */
	public function __construct()
	{
		$this->fields = MakesSettingFields::getInstance();
	}
	/**
	 * This is the function shows the tab's content.
	 */
	public function callback()
	{
		$type = $this->options['setting_type'];
		if ($type = 'text') {
			$this->fields->text($this->options);
		}
	}

	/**
	 * This sets all of the page's properties to the callback class.
	 */
	public function setProperties($options)
	{
		$this->options = $options;
	}

}