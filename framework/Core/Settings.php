<?php
namespace Baseline;

use Baseline\Services\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\WestcoCustomizer;

class Settings {

	use IsSingleton;

	protected $config;

	protected $options_prefix;

	protected $customizer;

	protected $options;

	private function __construct()
	{
		$this->config = WestcoConfig::getInstance();
		$this->options_prefix = $this->config->getFrameworkConfig('options_prefix');
		$this->customizer = WestcoCustomizer::getInstance();
	}

	public function getOption($name)
	{
		$option_name = $this->options_prefix . $name;

		// Is it cached?
		if (!wp_cache_get($option_name, '', false, true) === false) {
			return wp_cache_get($option_name);
		}
		// Is it in the databse?
		$option = get_option($option_name, 'not_found');
		if (!$option === 'not_found') {
			// fix the cache
			wp_cache_set($option_name, $option);
			return $option;
		}

		// Here is the default.
		$default = $this->config->getDefaults($name);
		$this->setOption($name, $default);
		return $default;
	}

	public function setOption($name, $value)
	{
		// Overwrite the the database.
		$option_name = $this->options_prefix . $name;
		$check = update_option($option_name, $value);

		// Put it in the cache.
		if ($check) {
			wp_cache_set($option_name, $value);
		}
		return $check;
	}

	public function setModuleFor($category, $module, $id = null)
	{
		// Is it for a specific page?
		if ($id) {
			set_theme_mod($category . '_' . $id, $module);
		}
		// Set the theme_mod
		set_theme_mod($category, $module);
	}

	public function getModuleFor($category)
	{
		// Is a module set for the category for the specific page?

		// Is a module set for the category globaly?

		// Here is the default.



		// Temporarily just hard code return values.
		if ($category == 'navigation_template') {
			return 'default_nav';
		} else if ($category == 'heading_template') {
			return 'default_header';
		}
	}

}