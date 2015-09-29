<?php
namespace Baseline;

use Baseline\Core\Config;
use Baseline\Core\Content;
use Baseline\Core\Settings;
use Baseline\Core\Registrar;
use Baseline\Helper\IsSingleton;

/*
|--------------------------------------------------------------------------
| Main Class for the Baseline Theme Framework
|--------------------------------------------------------------------------
|
| This is the main entry class for the Westco Theme Framework. It's holds
| the responsibility of booting up the different registrar classes and
| providing getters and setters for the different services classes.
|
*/

class BaselineFramework {

	/**
	 * This class is a singleton instance.
	 */
	use IsSingleton;

	/**
	 * Instance of the config class that handles fetching information from the config files.
	 */
	protected $config;

	/**
	 * Instance of the registrar class that handles registering new settings.
	 */
	protected $registrar;

	/**
	 * Instance of the content class that handles working with content modules.
	 */
	protected $content;

	/**
	 * Instance of the settings class that handles all theme settings
	 */
	protected $settings;


	/**
	 * Bootstraps the Baseline Framework
	 */
	private function __construct($config_path)
	{
		// Initializes the Config and sets up a connection to the config files.
		$this->config = Config::getInstance($config_path);

		// Initializes the main class responsible for registering all options of the Framework.
		$this->registrar = Registrar::getInstance();

		// Initializes the main class responsible for displaying content.
		// $this->content = Content::getInstance();

		// Initializes and main class used for getting and working with settings.
		// $this->settings = Settings::getInstance();
		
	}

	public function config()
	{
		return $this->config;
	}

	public function registrar()
	{
		return $this->registrar;
	}

	public function content()
	{
		return $this->content;
	}

	public function settings()
	{
		return $this->settings;
	}

}