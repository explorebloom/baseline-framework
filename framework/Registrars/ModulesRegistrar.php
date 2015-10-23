<?php
namespace Baseline\Registrars;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Contracts\ContentModule;
use Baseline\Contracts\ContentModuleCategory;

/*
|--------------------------------------------------------------------------
| Local and Child theme Module Registration.
|--------------------------------------------------------------------------
|
| This is the trait that gives the functionality of registring all of the
| different modules that are avaliable in the theme, and also sets up
| an action that a child theme can use to register its own modules.
|
*/

class ModulesRegistrar {

	use IsSingleton;

	/**
	 * An instance of the WestcoConfig class.
	 *
	 * @var Framework\WescoConfig
	 */
	protected $config;

	/**
	 * The path to the module files.
	 * 
	 * @var string
	 */
	protected $module_path;

	/**
	 * The path to the module category files.
	 *
	 * @var string
	 */
	protected $category_path;

	/**
	 * An associative array of all of the different content module categories and their settings.
	 *
	 * @var array
	 */
	protected $registered_categories = [];
	
	/**
	 * An associative array of all of the different content modules and their settings.
	 * 
	 * @var array
	 */
	protected $registered_modules = [];

	/**
	 * An associative array of all categories with their content modules
	 *
	 * @var array
	 */
	protected $registered_items = [];
	/**
	 * Constructs the class and sets all of it's properties.
	 */

	private function __construct()
	{
		// Set properties
		$this->config = Config::getInstance();
		$this->modules_path = $this->config->getFrameworkConfig('modules_path');
		$this->categories_path = $this->config->getFrameworkConfig('module_categories_path');
	}

	/**
	 * Registers all of the modules and module categories from the config files.
	 */
	public function register()
	{
		// First register all of the configuration files.
		$this->registerLocalCategories();
		$this->registerLocalModules();

		// Then regiser any additional things that need to overwrite.
		$this->registerAdditionalCategoires();
		$this->registerAdditionalModules();
	}

	/**
	 * Getter function for getting all of the registered modules
	 */
	public function listRegisteredModules()
	{
		return $this->registered_modules;
	}

	/**
	 * Getter function for returning an array of categories and their content modules.
	 */
	public function getRegisteredModules()
	{
		if ($this->registered_items) {
			return $this->registered_items;
		}
		return $this->makeRegisteredItems();
	}

	/**
	 * Registeres all of the different local Module categories from the config file.
	 */
	private function registerLocalCategories()
	{
		$category_array = $this->config->getCategoriesConfig();
		foreach($category_array as $category_class_name) {
			$this->validateAndRegisterCategory($category_class_name);
		}
	}

	/**
	 * Registers all of the the local modules based off of the modules config file.
	 */
	private function registerLocalModules()
	{
		$modules_array = $this->config->getModulesConfig();
		foreach($modules_array as $module_class_name) {
			$this->validateAndRegisterModule($module_class_name);
		}
	}

	/**
	 * Registers all of the oustide registered theme and child them modules.
	 */
	private function registerAdditionalModules()
	{
		// Register them here in the future.
	}

	/**
	 * Registeres all of the outside registered theme and child theme module categories.
	 */
	private function registerAdditionalCategoires()
	{
		// Register them here in the future.
	}

	/**
	 * Makes an associative array that holds all of the registered categories and their modules.
	 */
	private function makeRegisteredItems()
	{
		// Shorten them for now.
		$categories = $this->registered_categories;
		$modules = $this->registered_modules;
		$items = $this->registered_items;

		foreach($modules as $module => $options) {
			$category = $options['category'];
			
			// Does the category exist?
			if (!$categories[$category]) {
				continue;
			}

			// Is the category already in the registered items?
			if ($items[$category]) {

				// Add the item to the existing category's modules.
				$items[$category]['modules'][$module] = $options;
				continue;
			}

			// Add the category to the array first.
			$items[$category] = $categories[$category];

			// Then add the module to the category.
			$items[$category]['modules'][$module] = $options;
		}

		// Add the items to the property and return it.
		$this->registered_items = $items;
		return $this->registered_items;
	}

	/**
	 * Validates a single category and registers it.
	 */
	private function validateAndRegisterCategory($class_name, $custom_location = null)
	{
		// Create the category class.
		$category_path = get_template_directory() . '/' . $this->categories_path . '/' . $class_name . '.php';
		
		// Was a custom location passed in?
		if ($custom_location) {
			$category_path = $custom_location . '/' . $class_name . '.php';
		}

		include_once($category_path);

		// Make sure that if they provided a directory, only the class name is given.
		$class_name = end(explode('/', $class_name));
		
		// Does the class exist?
		if (!class_exists($class_name)) {
			return false;
		}

		// Then Create the object.
		$category = new $class_name;

		// Does the class have a configuration method?
		if (!method_exists($category, 'configuration')) {
			return false;
		}

		// Then add the Category to the registered_categories array property.
		// Its given slug is the key and its returned configuration array is the value.
		$this->registered_categories[$category->configuration()['slug']] = $category->configuration();
	}

	/**
	 * Validates a single module and registers it.
	 */
	private function validateAndRegisterModule($class_name, $custom_location = null)
	{
		// Create the module class.
		$module_path = get_template_directory() . '/' . $this->modules_path . '/' . $class_name . '.php';
		
		// Was a custom location passed in?
		if ($custom_location) {
			$module_path = $custom_location . '/' . $class_name . '.php';
		}

		include_once($module_path);

		// Make sure that if they provided a directory only the class name is taken.
		$class_name = end(explode('/', $class_name));

		// Does the class exist?
		if (!class_exists($class_name)) {
			return false;
		}

		// Then instantiate the object.
		$module = new $class_name;
		
		// Does the module have a configuration method?
		if (!method_exists($module, 'configuration')) {
			return false;
		}

		// Then add the Module to the registered_modules array property.
		// Its given slug is the key and its returned configuration array is the value.
		$this->registered_modules[$module->configuration()['slug']] = $module->configuration();
	}

}