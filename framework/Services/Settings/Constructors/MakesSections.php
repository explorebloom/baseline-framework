<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Traits\RegistersChildren;
use Baseline\Services\Settings\Callbacks\SectionCallbacks;
use Baseline\Services\Settings\Constructors\MakesSettings;

class MakesSections {

	use IsSingleton, RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * Constructs the class and sets all of the properties.
	 */
	private function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');

		// Set the validation class.
		$this->validator = ValidatesSettingOptions::getInstance();

		// Set the constructor class.
		$this->setting = MakesSettings::getInstance();
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix.
		$id = $this->setting_prefix . $id;

		// If parent is not set then we add the sections to the general screen.
		$parent_id = is_null($parent) ? 'general' : $parent->options['id'];

		// die($parent_id);

		// Bring out the options.
		extract($options);

		// Set up the callback class.
		$section_callback = new SectionCallbacks;
		$section_callback->setProperties(array(
			'id'			=> $id,
			'title'			=> $title,
			'description' 	=> $description,
			'group'			=> $parent_id
		));

		if ($parent) {
			$parent->registerChild('section', $id, $options);
		}

		// Create the Section with the Wordpress Settings Api.
		add_action('admin_init', function() use ($id, $options, $section_callback, $parent_id) {
			$this->registerWithSettingsApi($id, $options, $section_callback, $parent_id);
		});
	}

	/**
	 * Adds the setting section to the wordpress Api.
	 */
	public function registerWithSettingsApi($id, $options, $callback_class, $parent_id) {
		// Draw out the options.
		extract($options);

		// Create the setting section with the WordPress Settings Api
		add_settings_section(
			$id,
			$title,
			array($callback_class, 'callback'),
			$parent_id
		);

		// Make section's children
		$this->makeChildren($contents, $callback_class);

		// Register everything.
		register_setting(
			$parent_id,
			$id
		);
	}

	/**
	 * Makes all of the subpage's children.
	 */
	private function makeChildren($children, $parent)
	{
		// Will be set to false after the first valid item in the loop.
		$initial = true;

		foreach($children as $id => $options) {

			// Validate the options. If invalid move on to the next item.
			$valid_options = $this->validator->validate($options);
			if ($valid_options == false) {
				continue;
			}

			// Get the type.
			$type = $valid_options['type'];

			// If it is the first valid child, make sure everything else is this type.
			if ($initial) {
				// Set the page's direct children to the type.
				$parent->child_type = $type;
			}

			// Make sure that they didn't change the type.
			if ($type !== $parent->child_type) {
				// Move on to the next item if they type is not correct.
				continue;
			}

			// Calls the correct constructor.
			$this->callChildConstructor($id, $valid_options, $parent);

			// If this was the inital child element, set inital to false for future child elements.
			$initial = $initial ? false : $initial;
		}

	}

	/**
	 * Figures out what child item is and calls it's constructor class.
	 */
	private function callChildConstructor($id, $options, $parent)
	{
		// Call the constructor class based off of the type
		$type = $options['type'];

		if ($type === 'setting') {

			// Then make a section
			$this->setting->make($id, $options, $parent);

		}
	}

}