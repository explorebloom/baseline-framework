<?php
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Core\Registrar;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Customizer\MakesCustomizerFields;

/*
|--------------------------------------------------------------------------
| Customizer Registrar Class
|--------------------------------------------------------------------------
|
| This is the main customizer registrar class. It registers all customizer
| settings from the config files, and all customizer settings needed for 
| the registered modules. It then registers any child theme fields
|
*/

class CustomizerRegistrar {

	// This class uses a singleton instance.
	use IsSingleton;

	/**
	 * Holds an instance of the registrar class
	 */
	protected $registrar;

	/**
	 * Holds an instance of the Customizer Service Class
	 */
	protected $customizer;

	/**
	 * Holds all the config data for the custoimzer.
	 */
	protected $customizer_config;

	/**
	 * Holds the prefix for everything that goes to the database.
	 */
	protected $setting_prefix;

	/**
	 * An array of storage types for different type of settings.
	 */
	protected $storage_type;

	/**
	 * Holds all of the settings that have been successfully registered 
	 * with the customizer.
	 */
	protected $registered_customizer_settings;

	/**
	 * Constructs the class and sets all properties.
	 */
	private function __construct()
	{
		// Main Config Instance
		$config = Config::getInstance();

		// Core Registrar Class
		$this->registrar = Registrar::getInstance();

		// Customizer Service Class
		$this->customizer = MakesCustomizerFields::getInstance();

		// Customizer settings from the config file.
		$this->customizer_config = $config->getCustomizerConfig();

		// Prefix that will be used for everything in the database.
		$this->setting_prefix = $config->getFrameworkConfig('setting_prefix');

		// Storage type for different settings.
		$this->storage_type = array(
			'customizer_settings' => $config->getFrameworkConfig('save_customizer_settings_as'),
			'module_settings' => $config->getFrameworkConfig('save_module_settings_as'),
		);
	}

	/**
	 * Registers all of the different settings from the config files.
	 */
	public function register()
	{
		// Register all of the registered customizer settings.
		add_action('customize_register', array($this, 'registerCustomizerSettings'));

		// Register all of the external customizer settings.
		add_action('customize_register', array($this, 'additionalCustomizerSettings'));
	}

	/**
	 * Main function for registering all of the customizer fields
	 *
	 * @param object $wp_customize
	 */
	public function registerCustomizerSettings($wp_customize)
	{
		// Register all theme settings from the customizer config file.
		$this->customizer->register(
			$this->customizer_config,
			$this->storage_type['customizer_settings'],
			null,
			$this->setting_prefix,
			$wp_customize
		);

		// All content modules.
		$modules = $this->registrar->getModulesForCustomizer();
		foreach($modules as $module => $options) {
			$this->customizer->register(
				array($module => $options), 
				$this->storage_type['module_settings'],
				$options['section'],
				$this->setting_prefix,
				$wp_customize
			);
		}
	}

	/**
	 * Main function for registering all of the of the customizer settings from the theme.
	 *
	 * @param $wp_customize.
	 */
	public function additionalCustomizerSettings() {

		// First overwrite customizer settings with modifications made by the theme.

		// Then overwrite customizer settings with modifications made by a child theme.

	}
}