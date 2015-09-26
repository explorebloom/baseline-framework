<?php 
namespace Baseline;

use Baseline\Core\Settings;
use Baseline\Services\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\TemplateComposer;
use Baseline\Registrars\ModuleRegistrar;
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
	 * Protected property containing an instance of the ModuleRegistrar class
	 *
	 * @var Framework\Content\ModuleRegistrar
	 */
	protected $module_registrar;

	/**
	 * Protected property containing an instance of the Settings class
	 *
	 * @var Westco\Framework\WestcoSettings
	 */
	protected $settings;

	/**
	 * Protected property containing an instance of the WestcoConfig class
	 *
	 * @var Westco\Framework\WestcoConfig
	 */
	protected $config;

	/**
	 * Path to the location of modules.
	 */
	protected $modules_path;

	/**
	 * Sets up the class by registering all of the modules and getting all of their options.
	 */
	private function __construct()
	{
		$this->config = WestcoConfig::getInstance();
		$this->settings = WestcoSettings::getInstance();
		$this->module_registrar = ModuleRegistrar::getInstance();
		$this->modules_path = $this->config->getFrameworkConfig('modules_path');
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
		if (!$this->module_registrar->moduleExists($module_slug)) {
			return false;
		}

		// Get all the module's options
		$module_options = $this->module_registrar->moduleOptions($module_slug);

		$category = $module_options['category'];
		$template_path = $module_options['template_path'];
		$name = $module_options['slug'];

		// Get the module's template file.
		$file_slug = $this->modules_path . $template_path . $category;

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
		if (!$this->module_registrar->categoryExists($category_slug)) {
			return false;
		}

		// Get the set module for the category
		$module = $this->settings->getModuleFor($category_slug);

		// Return the content of that specific module
		return $this->getModule($module);
	}

}