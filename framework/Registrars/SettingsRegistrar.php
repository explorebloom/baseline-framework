<?php 
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\MakesSettingObjects;
use Baseline\Services\Settings\Constructors\MakesSections;

class SettingsRegistrar {

	use IsSingleton;

	/**
	 * Holds all of the settings that are in the settings config file.
	 */
	protected $config;

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

		// Set the config object as a property.
		$this->config = Config::getInstance();

		// Set the settings prefix from the framework config.
		$this->setting_prefix = $this->config->getFrameworkConfig('setting_prefix');

		// Create an instance of the settings class.
		$this->settings = MakesSettingObjects::getInstance();
	}

	/**
	 * Registers all of the different settings that the framework needs to build up.
	 */
	public function register()
	{
		// Register all of the settings from the config file.
		add_action('admin_menu', array($this, 'registerSettingsFromConfig'));
	}

	/**
	 * Handles registering all of the different settings that are located in the settings config file.
	 */
	public function registerSettingsFromConfig()
	{
		// Creates all of the settings using the wordpress's Settings API
		$settings_config = $this->config->getSettingsConfig();
		$this->settings->make($settings_config);

		// Allow Child themes and plugins to add to the settings registration.
		do_action('baseline_settings_register', $this->settings);
	}


	/**
	 * Adds a registered setting section to the property from a Setting Section Constuctor.
	 */
	public function registerSettingSection($section_object, $id, $options)
	{
		if ($section_object instanceof MakesSections) {
			$this->registered_settings[$id] = $options;
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