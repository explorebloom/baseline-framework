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
	 * Holds the path to the config file.
	 */
	protected $config_path = false;

	/**
	 * Instance of the config class that handles fetching information from the config files.
	 */
	protected $config = false;

	/**
	 * Instance of the registrar class that handles registering new settings.
	 */
	protected $registrar = false;

	/**
	 * Instance of the content class that handles working with content modules.
	 */
	protected $content = false;

	/**
	 * Instance of the settings class that handles all theme settings
	 */
	protected $settings = false;


	/**
	 * Bootstraps the Baseline Framework
	 */
	private function __construct($config_path)
	{
		$this->config_path = $config_path;

		// Initialize the Config right away and confirm the config path.
		$this->config = Config::getInstance($this->config_path);
		
		// Initialize the Settings Class right away for use in the theme setup.
		$this->settings = Settings::getInstance();
		
		// Register everthing after the theme is setup
		add_action('after_setup_theme', array($this, 'initializeRegistration'));
	}

	public function initializeRegistration()
	{
		
		// Register everything after functions.php has executed.
		$this->initializeFrameworkRegistration();

		// Register the Content Class after everything is Registered.
		$this->initializeContentClass();
	}

	/**
	 * Initialize the core Registrar class that will register everything.
	 */
	public function initializeFrameworkRegistration()
	{
		// Initializes the main class responsible for registering all options of the Framework.
		$this->registrar = Registrar::getInstance();
		$this->registrar->init();
		$this->settings->addRegisteredSettings();
	}

	/**
	 * Make the Core
	 */
	public function initializeContentClass()
	{
		// Initializes the main class responsible for displaying content.
		$this->content = Content::getInstance();
	}

	/**
	 * Returns an instance of the Core Config class.
	 */
	public function config()
	{
		return $this->config;
	}

	/**
	 * Returns an instance of the Core Registrar class.
	 */
	public function registrar()
	{
		return $this->registrar;
	}

	/**
	 * Returns and instance of the Core Content class.
	 */
	public function content()
	{
		return $this->content;
	}

	/**
	 * Returns an instance of the Core Settings class.
	 */
	public function settings()
	{
		return $this->settings;
	}

}