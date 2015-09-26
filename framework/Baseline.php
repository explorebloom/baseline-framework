<?php
namespace Baseline;

use Baseline\Core\Content;
use Baseline\Core\Settings;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Customizer;

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

class Baseline {

	use IsSingleton;

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
	 * Instantiates the framework
	 */
	private function __construct()
	{
		// Initializes and registers all of the different content modules for the framework
		$this->content = WestcoContent::getInstance();

		// Initializes and registers all of the different settings for the framework
		$this->settings = WestcoSettings::getInstance();
	}

}

WestcoInit::getInstance();