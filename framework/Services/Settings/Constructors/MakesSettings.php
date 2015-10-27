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
	 * Holds the setting's id for the wordpress action to reference.
	 */
	protected $setting_id;

	/**
	 * Holds an instance of the settings parent class.
	 */
	protected $setting_parent;

	/**
	 * Holds the setting's optinos for the wordpress action to reference.
	 */
	protected $setting_options;

	/**
	 * Holds the setting's callback class for the wordpress action to reference.
	 */
	protected $setting_callback;

	/**
	 * Constructs the class and sets all of the properties.
	 */
	public function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix.
		$this->setting_id = $this->setting_prefix . $id;

		// Set the parent to the property
		$this->setting_parent = $parent;

		// Bring out the options.
		$this->setting_options = $options;
		// extract($options);

		// Set up the callback class.
		$this->setting_callback = new SettingCallbacks;
		$this->setting_callback->setProperties(array(
			'id'				=> $this->setting_id,
			'section'			=> $parent->options['id'],
			'setting_type'		=> $this->setting_options['setting_type'],
			'given_options'		=> $this->setting_options,
		));


		add_action('admin_init', array($this, 'registerSetting'));
	}

	public function registerSetting()
	{
		// Create the Setting with the Wordpress Settings Api.
		add_settings_field(
			$this->setting_id,
			$this->setting_options['title'],
			array($this->setting_callback, 'callback'),
			$this->setting_parent->options['group'],
			$this->setting_parent->options['id']
		);

	}

}