<?php 
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\MakesSettingObjects;

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
	 * Builds up the class with all of the needed dependencies.
	 */
	private function __construct()
	{
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
		// Register all of the settings from the config file.
		add_action('admin_menu', array($this, 'registerSettings'));

		// Register all of the additional settings.
		add_action('admin_menu', array($this, 'registerAdditionalSettings'));
	}

	/**
	 * Handles registering all of the different settings that are located in the settings config file.
	 */
	public function registerSettings()
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

}