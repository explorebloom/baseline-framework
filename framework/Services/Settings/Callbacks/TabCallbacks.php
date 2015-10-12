<?php
namespace Baseline\Services\Settings\Callbacks;

use Baseline\Services\Settings\Traits\HandlesSubtabs;
use Baseline\Services\Settings\Traits\HandlesSections;

class TabCallbacks {

	use HandlesSubtabs;
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
	 * An array of all of the subtabs that are children to this page.
	 */
	protected $subtabs = array();

	/**
	 * An array of all of the sections that are children to this page.
	 */
	protected $sections = array();

	/**
	 * This is the function shows the tab's content.
	 */
	public function callback()
	{
		if ($this->child_type === 'subtab') {

			// Display the subtabs.
			$this->subtabs();

		} else if ($this->child_type === 'section') {

			// Display the sections.
			$this->sections();

		}

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
		
		if ($type == 'subtab') {

			// Add it to the subtabs array
			$this->subtabs[$id] = $callback_class;

		// Is it a section?
		} else if ($type == 'section') {

			// Add it to the sections array
			$this->sections[$id] = $callback_class;
		
		}

	}
	
}