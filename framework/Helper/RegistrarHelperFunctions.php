<?php
namespace Baseline\Helper;

use Baseline\Services\Customizer\MakesCustomizerFieldsFromModules;
/*
|--------------------------------------------------------------------------
| Core Registrar Helper Functions
|--------------------------------------------------------------------------
|
| This is a trait that creates multiple helper functions for baseline's core
| registrar class that involve working with other registrar classes' data.
| This simplifies the main Registrar file to controlling registration.
|
*/


trait RegistrarHelperFunctions {

	/**
	 * Returns an array of all of registered categories and their modules.
	 */
	public function getRegisteredModules()
	{
		return $this->modules->getRegisteredModules();
	}

	/**
	 * Returns an array of all of the registered modules.
	 */
	public function listRegisteredModules()
	{
		return $this->modules->listRegisteredModules();
	}

	/**
	 * Gets all of the registered modules formated for the customizer.
	 */
	public function getModulesForCustomizer() {
		// Get all categorized modules.
		$modules = $this->getRegisteredModules();
		
		// Get an instance of the Module to Customizer Adapter
		$modulesToCustomizer = new MakesCustomizerFieldsFromModules;
		
		// Return them parsed for customizer.
		return $modulesToCustomizer->make($modules);
	}

	public function getRegisteredCustomizerSettings()
	{
		
	}

	/**
	 * Gets all of the registered settings that have been constructed with the settings api.
	 */
	public function getRegisteredSettings()
	{
		return $this->settings->getRegisteredSettings();
	}

}