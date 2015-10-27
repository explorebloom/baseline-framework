<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Traits\RegistersChildren;
use Baseline\Services\Settings\Callbacks\SubtabCallbacks;

class MakesSubtabs {

	use RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * An array of all of the allowed child types.
	 */
	protected $allowed_child_types = array(
		'section',
	);

	/**
	 * Constructs the class and sets all of the properties.
	 */
	public function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');

		// Set the validation class.
		$this->validator = ValidatesSettingOptions::getInstance();
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix
		$id = $this->setting_prefix . $id;

		// Bring out the variables
		extract($options);

		// Figure out the parent type.
		if ($parent instanceof Baseline\Services\Settings\Callbacks\TabCallbacks) {
			$page = $parent->options['page'];
		} else {
			$page = $parent->options['id'];
		}

		// Set up the Subtab Callback Class
		$subtab_callback = new SubtabCallbacks;
		$subtab_callback->setProperties(array(
			'id'		=> $id,
			'display'	=> $display,
			'tab'		=> $parent->options['id'],
			'page'		=> $page,
			'subtab_style' => $parent->options['subtab_style']
		));

		// Register the Subtab with its parent.
		$parent->registerChild('subtab', $id, $subtab_callback);

		// Make the Subtab's Contents.
		$this->makeChildren($contents, $subtab_callback);
	}

}