<?php 
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\MakesSettingObjects;
use Baseline\Services\Settings\Constructors\MakesSettings;

class SettingsRegistrar {

	use IsSingleton;

	/**
	 * Holds all of the settings that are in the settings config file.
	 */
	protected $settings_config;

	/**
	 * The settings prefix that is used for all database items.
	 */
	protected $setting_prefix;

	/**
	 * Holds an instand of the MakesSettingFields class that interfaces with wordpresses settings api.
	 */
	protected $settings;

	/**
	 * Holds a list of all validated and registered settings and their options.
	 */
	protected $registered_settings = array();

	/**
	 * Builds up the class with all of the needed dependencies.
	 */
	private function __construct()
	{
		// Get an instance of config.
		$config = Config::getInstance();

		// Set the settings config data from the config file.
		$this->settings_config = $config->getSettingsConfig();

		// Set the settings prefix from the framework config.
		$this->setting_prefix = $config->getFrameworkConfig('setting_prefix');

		// Create an instance of the settings class.
		$this->settings = MakesSettingObjects::getInstance();
	}

	/**
	 * Registers all of the different settings that the framework needs to build up.
	 */
	public function register()
	{
		// Adds all of the registered settings to the registered settings array.
		$this->addRegisterSettings($this->settings_config);
		
		// Register all of the settings from the config file.
		add_action('admin_menu', array($this, 'registerSettingsFromConfig'));

		// Register all of the additional settings.
		add_action('admin_menu', array($this, 'registerAdditionalSettings'));
	}

	/**
	 * Handles registering all of the different settings that are located in the settings config file.
	 */
	public function registerSettingsFromConfig()
	{
		// Creates all of the settings using the wordpress's Settings API
		$this->settings->make($this->settings_config);

	}

	/**
	 * Handles registering all other additional settings that are called within the theme outside of the config files.
	 */
	public function registerAdditionalSettings()
	{
		// Will implement later.
	}

	/**
	 * Adds a registered setting to the property from a Setting Constuctor.
	 */
	private function addRegisterSettings($settings_object)
	{
		foreach ($settings_object as $id => $options) {
			if ($options['type'] == 'setting') {
				$this->registered_settings[$id] = $options;
			} else if ($options['contents']) {
				$this->addRegisterSettings($options['contents']);
			}
		}
	}

	/**
	 * Returns all of the registered settings.
	 */
	public function getRegisteredSettings()
	{
		return $this->registered_settings;
	}

}