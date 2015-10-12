<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\Callbacks\SettingCallbacks;

class MakesSettings {

	use IsSingleton;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * Constructs the class and sets all of the properties.
	 */
	private function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix.
		$id = $this->setting_prefix . $id;

		// Bring out the options.
		extract($options);

		// Set up the callback class.
		$setting_callback = new SettingCallbacks;
		$setting_callback->setProperties(array(
			'id'			=> $id,
			'section'		=> $parent->options['id'],
			'setting_type'	=> $setting_type,
			'options'		=> $options,
		));

		// Create the Setting with the Wordpress Settings Api.
		add_settings_field(
			$id,
			$title,
			array($setting_callback, 'callback'),
			$parent->options['group'],
			$parent->options['id']
		);
	}

}