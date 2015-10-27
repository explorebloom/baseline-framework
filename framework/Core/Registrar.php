<?php
namespace Baseline\Core;

use Baseline\Helper\IsSingleton;
use Baseline\Registrars\ModulesRegistrar;
use Baseline\Registrars\SettingsRegistrar;
use Baseline\Registrars\CustomizerRegistrar;
use Baseline\Helper\RegistrarHelperFunctions;

/*
|--------------------------------------------------------------------------
| Baseline Main Registrar Class
|--------------------------------------------------------------------------
|
| This is the main class for registration settings. It is responsible for
| calling all sub registrar classes to make sure everything is properly 
| registered. And then holds all of the settings for user reference.
|
*/


class Registrar {
	
	// Helper classes.	
	use IsSingleton, RegistrarHelperFunctions;

	/**
	 * Holds an instance of the module registrar.
	 */
	protected $modules;

	/**
	 * Holds and instance of the customizer registrar.
	 */
	protected $customizer;

	/**
	 * Holds an instance of the settings registrar.
	 */	
	protected $settings;

	/**
	 * Registers everythig from config and sets up actions for child theme extension;
	 */
	public function init()
	{
		$this->modules = ModulesRegistrar::getInstance();
		$this->customizer = CustomizerRegistrar::getInstance();
		$this->settings = SettingsRegistrar::getInstance();
		
		// Register all the different content modules in the config file.
		$this->modules->register();

		// Register all the different customizer settings from the config file.
		$this->customizer->register();

		// Registers all of the different settings from the config file.
		$this->settings->register();
	}

}