<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\Callbacks\TabCallbacks;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Traits\RegistersChildren;

class MakesTabs {

	use IsSingleton, RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * An array of all of the allowed child types.
	 */
	protected $allowed_child_types = array(
		'subtab',
		'section',
	);

	/**
	 * Constructs the class and sets all of the properties.
	 */
	private function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');

		// Set the validation class.
		$this->validator = ValidatesSettingOptions::getInstance();

		// Set the constructor classes.
		$this->subtab = MakesSubtabs::getInstance();
		$this->section = MakesSections::getInstance();
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix.
		$id = $this->setting_prefix . $id;

		// Bring out the variable
		extract($options);

		// Set up the Tabs Callback Class.
		$tab_callback = new TabCallbacks;
		$tab_callback->setProperties(array(
			'id' 		=> $id,
			'display' 	=> $display,
			'page'		=> $parent->options['id']
		));

		// Register the Tab with it's parent.
		$parent->registerChild('tab', $id, $tab_callback);

		// Make the tab's contents
		$this->makeChildren($contents, $tab_callback);
	}

}