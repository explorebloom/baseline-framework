<?php
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Core\Registrar;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Customizer;

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
	 * Holds an instance of the Customizer Service Class
	 */
	protected $customizer;

	/**
	 * Holds all the config data for the custoimzer.
	 */
	protected $customizer_config;

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
	 * Holds all of the settings that have been successfully registered
	 * within the options page.
	 */
	protected $registered_option_settings;

	// Builds up the class and sets all of it's properties.
	private function __construct($registrar)
	{
		// Core Registrar Class
		$this->registrar = $registrar;
		
		// Main Config Instance
		$config = Config::getInstance();


		// Customizer settings from the config file.
		$this->customizer_config = $config->getCustomizerConfig();

		// Storage type for different settings.
		$this->storage_type = array(
			'theme_settings' => $config->getFrameworkConfig('save_theme_settings_as'),
			'module_settings' => $config->getFrameworkConfig('save_module_settings_as'),
		);

		// Customizer Service Class
		$this->customizer = new Customizer;

	}

	/**
	 * Registers all of the different settings from the config files.
	 */
	public function registerSettings()
	{
		// Register all of the theme customizer settings.
		add_action('customize_register', array($this, 'registerCustomizerSettings'));
	}

	/**
	 * Main function for registering all of the customizer fields
	 *
	 * @param object $wp_customize
	 */
	public function registerCustomizerSettings($wp_customize)
	{
		// All theme settings from the customizer config file.
		echo '<pre>';
		$this->customizer->register($this->customizer_config, $this->storage_type['theme_settings'], null, $wp_customize);
		echo '</pre>';

		// All content modules.
		echo '<pre>';
		var_dump($modules = $this->registrar->getModulesForCustomizer());
		foreach($modules as $module => $options) {
			$this->customizer->register($modules, $this->storage_type['module_settings'], $options['section'], $wp_customize);
		}
		echo '</pre>';
		// $this->customizer->register($modules, $this->storage_type['module_settings'], )s
	}

}