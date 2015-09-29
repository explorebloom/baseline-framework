<?php
namespace Baseline\Core;

use Baseline\Services\Options;
use Baseline\Helper\IsSingleton;
use Baseline\Registrars\ModulesRegistrar;
use Baseline\Registrars\CustomizerRegistrar;
use Baseline\Helper\PrepsModulesForCustomizer;

/*
|--------------------------------------------------------------------------
| Baseline Main Registrar Class
|--------------------------------------------------------------------------
|
| This is the main class for registration settings. It is responsible for
| calling all sub registrar classes to make sure everything is properly 
| registered. It also makes actions for registering from a child them.
|
*/


class Registrar {
	
	// Make this class a singleton instance.	
	use IsSingleton, PrepsModulesForCustomizer;

	/**
	 * Holds and instance of the customizer registrar.
	 */
	protected $customizer;

	/**
	 * Holds an instance of the module registrar.
	 */
	protected $modules;

	/**
	 * Constructs up the class and sets all of the properties.
	 */
	private function __construct()
	{
	
		// Get together all our Registrars.
		$this->modules = ModulesRegistrar::getInstance();
		$this->customizer = CustomizerRegistrar::getInstance($this);
	
		// Start registering things.
		$this->init();
	}

	/**
	 * Registers everythig from config and sets up actions for child theme extension;
	 */
	private function init()
	{
		// Register all the different content modules in the config file.
		$this->modules->registerFromConfig();

		// Register all the different settings from the config file.
		$this->customizer->registerSettings();
	}
}