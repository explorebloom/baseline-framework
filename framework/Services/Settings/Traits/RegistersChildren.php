<?php
namespace Baseline\Services\Settings\Traits;

use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Constructors\MakesTabs;
use Baseline\Services\Settings\Constructors\MakesSubtabs;
use Baseline\Services\Settings\Constructors\MakesSubpages;
use Baseline\Services\Settings\Constructors\MakesSections;
use Baseline\Services\Settings\Constructors\MakesSettings;

trait RegistersChildren {

	/**
	 * Makes all of the Setting Objects Child Objects.
	 */
	private function makeChildren($children, $parent)
	{
		// Will be set to false after the first valid item in the loop.
		$initial = true;

		foreach($children as $id => $options) {

			// Validate the options. If invalid move on to the next item.
			$valid_options = ValidatesSettingOptions::getInstance()->validate($options);
			if ($valid_options == false) {
				continue;
			}

			// Get the type.
			$type = $valid_options['type'];

			// Make sure for tabs the parent's tab_style is set to independent
			if ($type == 'tab' && $parent->options['tab_style'] != 'independent') {
				// Ignore and move on to the next item.
				continue;
			}

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
			$this->callChildConstructor($id, $valid_options, $parent, $initial);

			// If this was the inital child element, set inital to false for future child elements.
			$initial = $initial ? false : $initial;
		}

	}

	/**
	 * Figures out what child item is and calls it's constructor class.
	 */
	private function callChildConstructor($id, $options, $parent, $initial)
	{
		// Get the type.
		$type = $options['type'];

		// Make sure it is in one of the allowed child types for the object.
		if (!in_array($type, $this->allowed_child_types)) {
			return;
		}

		// Is it a subpage?
		if ($type === 'subpage') {
			// Then make a Subpage.
			MakesSubpages::getInstance()->make($id, $options, $parent, $initial);

		// Is it a tab?
		} else if ($type === 'tab') {

			// Then make a Tab.
			MakesTabs::getInstance()->make($id, $options, $parent);

		// Is it a subtab?
		} else if ($type === 'subtab') {

			// Then make a Subtab.
			MakesSubtabs::getInstance()->make($id, $options, $parent);

		// Is it a section?
		} else if ($type === 'section') {

			// Then make a section
			MakesSections::getInstance()->make($id, $options, $parent);

		// Is it a setting?
		} else if ($type === 'setting') {

			// Then make a setting
			MakesSettings::getInstance()->make($id, $options, $parent);

		}

	}

}