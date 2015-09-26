<?php
namespace Baseline\Registrars;

use Baseline\Services\Config;
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

class ModuleRegistrar {

	use IsSingleton;

	/**
	 * An instance of the WestcoConfig class.
	 *
	 * @var Framework\WescoConfig
	 */
	protected $config;

	/**
	 * Namespace of the content module categories.
	 */
	protected $categories_namespace;

	/**
	 * Namespace of the content modules.
	 */
	protected $modules_namespace;

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
	 * Registers all fo the modules that will be used within the theme.
	 */
	private function __construct()
	{
		// Set properties
		$this->config = WestcoConfig::getInstance();
		$this->categories_namespace = $this->config->getFrameworkConfig('module_categories_namespace');
		$this->modules_namespace = $this->config->getFrameworkConfig('modules_namespace');

		// Register Categories and Modules
		$this->registerLocalCategories();
		$this->registerLocalModules();
		// $this->registerChildThemeCategories();
		// $this->registerChildThemeModules();
	}

	/**
	 * Returns all of the settings for a specific category or all of the categories.
	 * 
	 * @param string $key
	 * @return array
	 */
	public function categoryOptions($key = null)
	{
		if ($key && $this->categoryExists($key)) {
			return $this->registered_categories[$key];
		}
		return $this->registered_categories;
	}

	/**
	 * Returns all of the settings for a specific module or for all modules.
	 * 
	 * @param string $key
	 * @return array
	 */
	public function moduleOptions($key = null)
	{
		if ($key && $this->moduleExists($key)) {
			return $this->registered_modules[$key];
		}
		return $this->registered_modules;
	}

	/**
	 * Checks if a content module exists or not.
	 *
	 * @param string $module_slug
	 */
	public function moduleExists($module_slug)
	{
		if (array_key_exists($module_slug, $this->registered_modules)) {
			return true;
		}
		return false;
	}

	/**
	 * Checks if a content module category exists or not.
	 *
	 * @param string $module_slug
	 */
	public function categoryExists($category_slug)
	{
		if (array_key_exists($category_slug, $this->registered_categories)) {
			return true;
		}
		return false;
	}

	/**
	 * Registeres all of the different local Module categories from the config file.
	 */
	private function registerLocalCategories()
	{
		$category_array = $this->config->getModuleCategories();
		foreach($category_array as $category_class_name) {
			$this->validateAndRegisterCategory($category_class_name);
		}
	}

	/**
	 * Registers all of the the local modules based off of the modules config file.
	 */
	private function registerLocalModules()
	{
		$modules_array = $this->config->getModules();
		foreach($modules_array as $module_class_name) {
			$this->validateAndRegisterModule($module_class_name);
		}
	}

	/**
	 * Registers all of the child theme module categories from the given action.
	 */
	private function registerChildThemeCategories()
	{
		// Bring in child theme categories.
	}

	/**
	 * Registers all of the child theme modules based from the given action.
	 */
	private function registerChildThemeModules()
	{
		// Bring in child theme modules.
	}

	/**
	 * Validates a single category and registers it.
	 */
	private function validateAndRegisterCategory($class_name)
	{
		// Create the category class.
		$category_class = $this->categories_namespace . $class_name;
		$category = new $category_class;

		// Is the category is an instance of the category contract?
		if (!$category instanceof ContentModuleCategory) {
			return false;
		}

		// Register the Category
		$this->registered_categories[$category->configuration()['slug']] = $category->configuration();
	}

	/**
	 * Validates a single module and registers it.
	 */
	private function validateAndRegisterModule($class_name)
	{
		// Create the module class.
		$module_class = $this->modules_namespace . $class_name;
		$module = new $module_class;
		
		// Is the category is an instance of the category contract?
		if (!$module instanceof ContentModule) {
			return false;
		}
		// Register the Category
		$this->registered_modules[$module->configuration()['slug']] = $module->configuration();
	}

}