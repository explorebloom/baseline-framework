<?php
namespace Baseline\Services\Settings\Callbacks;

use Baseline\Services\Settings\Traits\HandlesSections;

class SubtabCallbacks {

	use HandlesSections;

	/**
	 * Holds the page's options.
	 */
	public $options;

	/**
	 * Holds the type of direct children this page has.
	 */
	public $child_type;

	/**
	 * An array of all of the sections that are children to this subtab.
	 */
	protected $sections = array();

	/**
	 * This is the function shows the tab's content.
	 */
	public function callback()
	{
		// Call wordpress functions that display the settings.
		$this->sections();
	}

	/**
	 * This sets all of the page's properties to the callback class.
	 */
	public function setProperties($options)
	{
		$this->options = $options;
	}

	/**
	 * Registers the child element and it's callback to the correct
	 * property of the page callback class.
	 */
	public function registerChild($type, $id, $callback_class) {
		
		// Is it a section?
		if ($type == 'section') {

			// Add it to the sections array
			$this->sections[$id] = $callback_class;
		
		}
	}
}