<?php
namespace Baseline\Core;

use Baseline\Core\Config;
use Baseline\Core\Registrar;
use Baseline\Helper\IsSingleton;

/*
|--------------------------------------------------------------------------
| Baseline Core Settings Class
|--------------------------------------------------------------------------
|
| This is the Core Settings class for the baseline framework. It serves the
| purpose of getting and setting values stored in the database, managing
| prefixing, and using theme_mods or options for registered settings.
|
*/

class Settings {

	use IsSingleton;

	/**
	 * Holds the prefix that is defined in the framework config file that all settings have.
	 */
	protected $setting_prefix;

	/**
	 * An associative array of the storage types for theme settings and module settings.
	 */
	protected $storage_type;

	/**
	 * Holds an array of all of the registered module category slugs
	 */
	protected $registered_categories = array();

	/**
	 * Holds an array of all of the registered theme settings
	 */
	protected $registered_settings = array();



	private function __construct()
	{
		// Instance of the Core Config class.
		$config = Config::getInstance();

		// Setting prefix from the config file.
		$this->setting_prefix = $config->getFrameworkConfig('setting_prefix');

		// Storage type from config file.
		$this->storage_type = array(
			'theme_settings' => $config->getFrameworkConfig('save_theme_settings_as'),
			'module_settings' => $config->getFrameworkConfig('save_module_settings_as'),
		);

		$this->registered_settings = array();

	}

	/**
	 * Called by the framework after everything is registered.
	 */
	public function addRegisteredSettings()
	{
		// Registered Categories
		$registrar = Registrar::getInstance();
		$this->registered_categories = array_keys($registrar->getRegisteredModules());

		// Retistered Settings
		// $this->registered_settings = array_keys($registrar->getRegisteredSettings());
	}

	/**
	 * Main function for getting values from the database.
	 */
	public function get($key, $return_value = 'not_set', $prefix = '')
	{
		$storage_type = $this->getStorageType($key);

		$key = $prefix . $key;
		// Is it saved as an option?
		if ($storage_type == 'option') {

			return $this->getOption($key, $return_value);

		// Is it saved as a them mod?
		} elseif ($storage_type == 'theme_mod') {
			
			return $this->getThemeMod($key, $return_value);

		// Is it not registered?
		} else {

			return $this->getSetting($key, $return_value);
		
		}
	}

	/**
	 * Main function setting values to the database.
	 */
	public function set($name, $key)
	{

	}

	/**
	 * Gets an option to the database with the framework's prefix.
	 */
	public function getOption($key, $default = null)
	{
		$key = $this->setting_prefix . $key;
		return get_option($key, $default);
	}

	/**
	 * Gets a theme_mod from the database with the framework's prefix.
	 */
	public function getThemeMod($key, $default = null)
	{
		$key = $this->setting_prefix . $key;
		return get_theme_mod($key, $default);
	}

	/**
	 * Gets a setting regardless if it is an option or a theme mod.
	 */
	public function getSetting($key, $default)
	{
		// Is it an option?
		$option_value = $this->getOption($key, 'not_found');
		if ($option_value !== 'not_found') {
			return $option_value;
		}

		// Is it a theme_mod?
		$mod_value = $this->getThemeMod($key, 'not_found');
		if ($mod_value !== 'not_found') {
			return $mod_value;
		}

		// Not set.
		return $default;

	}
	/**
	 * Figures out if the key relates to a registered setting and sets the correct storage type.
	 */
	private function getStorageType($key)
	{
		// Is this a registered Module Category?
		if (in_array($key, $this->registered_categories)) {
			return $this->storage_type['module_settings'];

		// Is this a registered Theme Setting?
		} elseif (in_array($key, $this->registered_settings)) {
			return $this->storage_type['theme_settings'];
		
		// Not registered.
		} else {
			return false;
		}
	}

}