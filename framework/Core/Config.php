<?php
namespace Baseline\Core;

use Baseline\Helper\IsSingleton;

class Config {

	use IsSingleton;

	/**
	 * Protected property that has the url to the different config files
	 */
	protected $config_path;

	/**
	 * Public function that instantiates the class and sets up the configuration path.
	 */
	private function __construct($config_path)
	{
		$this->config_path = $config_path;
		$this->confirmConfigFiles();
	}

	/**
	 * Public function to get the defaults for the theme options.
	 * An optional key may be passed to return only one section.
	 *
	 * @param string $key
	 * @return array
	 */
	public function getThemeDefaults($key = null)
	{
		$data = $this->getConfigData('defaults');
		return $key ? $data[$key] : $data;
	}

	/**
	 * Public function to get the defaults for the theme options.
	 * An optional key may be passed to return only one section.
	 *
	 * @return array
	 */
	public function getModules()
	{
		return $this->getConfigData('modules');
	}

	/**
	 * Public function to get a list of all of the different module
	 * categories that are registered in the categories config file.
	 *
	 * @return array
	 */
	public function getModuleCategories()
	{
		return $this->getConfigData('categories');
	}

	/**
	 * Public funciton to get a list of all of the differen core
	 * framework configurations, or a specific passed option.
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function getFrameworkConfig($key)
	{
		$data = $this->getConfigData('framework');
		return $key ? $data[$key] : $data;
		
	}

	/**
	 * Public function to get all of the customizer config data
	 *
	 * @return array
	 */
	public function getCustomizerConfig()
	{
		return $this->getConfigData('customizer');
	}

	/**
	 * Private funcion that will return the array of data from the config file passed in.
	 *
	 */
	private function getConfigData($file_name)
	{
		$data = include $this->config_path . '/' .$file_name . '.php';
		return $data;
	}

	/**
	 * Function that finds all config files or kills the framework.
	 */
	private function confirmConfigFiles()
	{
		// Set the variables.
		$path = $this->config_path;
		$missing = array();
		$files = array(
			'categories.php',
			'customizer.php',
			'framework.php',
			'modules.php',
			'options.php',
		);

		// Check for the files
		foreach ($files as $file) {
			if(!file_exists($path . '/' . $file)) {
				$missing[] = $file;
			}
		}

		// If any missing die.
		if (count($missing)) {
			$missing_files = implode(', ' , $missing);
			die('ERROR: Could not find the following config files. [' . $missing_files . ']');
		}

	}
}