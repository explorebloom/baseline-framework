<?php
namespace Baseline\Services\Settings\Callbacks;

use Baseline\Services\Settings\Traits\HandlesTabs;
use Baseline\Services\Settings\Traits\HandlesSubtabs;
use Baseline\Services\Settings\Traits\HandlesSections;

class SubpageCallbacks {

	use HandlesTabs, HandlesSubtabs, HandlesSections;

	/**
	 * Holds the page's options.
	 */
	public $options;

	/**
	 * Holds the type of direct children this page has.
	 */
	public $child_type;

	/**
	 * An array of all of the tabs that are children to this page.
	 */
	protected $tabs = array();

	/**
	 * An array of all of the subtabs that are children to this page.
	 */
	protected $subtabs = array();

	/**
	 * An array of all of the sections that are children to this page.
	 */
	protected $sections = array();

	/**
	 * This is the function called by WordPress to display the page's content.
	 */
	public function callback()
	{
		echo '<h2>' . $this->options['page_title'] . '</h2>';

		if ($this->child_type == 'tab') {

			// Make the tabs.
			$this->tabs();

		} else if ($this->child_type == 'subtab') {

			// Make the subtabs.
			$this->subtabs();

		} else if ($this->child_type == 'section') {

			// Make the sections.
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
		
		// Is it a tab?
		if ($type == 'tab') {

			// Add it to the tabs array.
			$this->tabs[$id] = $callback_class;

		// Is it a subtab?
		} else if ($type == 'subtab') {

			// Add it to the subtabs array
			$this->subtabs[$id] = $callback_class;

		// Is it a section?
		} else if ($type == 'section') {

			// Add it to the sections array
			$this->sections[$id] = $callback_class;
		
		}
	}

}