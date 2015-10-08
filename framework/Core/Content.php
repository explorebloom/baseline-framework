<?php 
namespace Baseline\Core;

use Baseline\Core\Settings;
use Baseline\Helper\IsSingleton;
use Baseline\Helper\GetsCurrentPage;
use Baseline\Services\TemplateComposer;
use Baseline\Helper\HelperModuleQueries;

/*
|--------------------------------------------------------------------------
| Main Routing Class for managing different types of content
|--------------------------------------------------------------------------
|
| This is the main content class for the theme. It's main responsibility
| is to register all of the different avaliable modules and based on
| the circumstances that it is called display the right content.
|
*/

class Content {

	use IsSingleton, HelperModuleQueries;

	/**
	 * An instance of the Settings class
	 *
	 * @var Baseline\Core\Settings
	 */
	protected $settings;

	/**
	 * An array of all of the registered module categories and their modules and all their options.
	 *
	 * @var Baseline\Core\Registrar
	 */
	protected $registered_modules;

	/**
	 * An array of all of the registered modules and their options.
	 *
	 * @var array
	 */
	protected $modules_list;

	/**
	 * Sets up the class by registering all of the modules and getting all of their options.
	 */
	private function __construct()
	{
		// Set all the registerd modules as a property
		$registrar = Registrar::getInstance();
		$this->registered_modules = $registrar->getRegisteredModules();
		$this->modules_list = $registrar->listRegisteredModules();
		$this->currentPage = new GetsCurrentPage;

		// Core Settings class
		$this->settings = Settings::getInstance();
	}

	/**
	 * Returns the content for a specific module or false if it doesnt exist.
	 *
	 * @param string $category
	 * @param string $module
	 * @return mixed
	 */
	public function getModule($module_slug)
	{
		// Does the given module exist?
		if (!$this->moduleExists($module_slug)) {
			return false;
		}

		// Get all the module's options
		$module_options = $this->modules_list[$module_slug];

		// returns the content of the template file.
		return new TemplateComposer($module_options);
	}

	/**
	 * Returns the currently set content module for a specific module category
	 *
	 * @param string $category_slug
	 * @return mixed
	 */
	public function getModuleFor($category_slug)
	{
		// Does the given category exist?
		if (!$this->categoryExists($category_slug)) {
			return false;
		}


		// Then get the module from the settings.
		$set_module = $this->settings->get($category_slug);

		// If something is set return it.
		if ($set_module != 'not_set') {
			return $this->getModule($set_module);
		}

		// If it isn't set then get the default
		$category_options = $this->registered_modules[$category_slug];
		return $this->getModule($category_options['default']);
	}

	/**
	 * Checks to see if a module exists
	 */
	public function moduleExists($module_slug)
	{
		if ($this->modules_list[$module_slug]) {
			return true;
		}
		return false;
	}

	/**
	 * Checks to see if a module category exists
	 */
	public function categoryExists($category_slug)
	{
		if ($this->registered_modules[$category_slug]) {
			return true;
		}
		return false;
	}
}