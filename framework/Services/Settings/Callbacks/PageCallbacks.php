<?php
namespace Baseline\Services\Settings\Callbacks;

use Baseline\Services\Settings\Traits\HandlesTabs;
use Baseline\Services\Settings\Traits\HandlesSubtabs;
use Baseline\Services\Settings\Traits\HandlesSections;

class PageCallbacks {

	use HandlesTabs, HandlesSubtabs, HandlesSections;

	/**
	 * Holds the page's options.
	 */
	public $options;

	/**
	 * Holds the value of this Setting Objects type.
	 */
	public $type = 'page';

	/**
	 * Holds the type of direct children this page has.
	 */
	public $child_type;

	/**
	 * An array of all of the subpages that are children to this page. It is public so that
	 * the direct subpages can see all their siblings for creating tab bar's based off of
	 * subpages.
	 */
	public $subpages = array();

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
		if ($this->child_type != 'subpage') {
			echo '<h1>' . $this->options['page_title'] . '</h1>';
		}

		if ($this->child_type == 'subpage') {

			// Subpage callbacks get called automatically by wordpress.
			return;

		} else if ($this->child_type == 'tab' && $this->options['tabs'] == 'independent') {

			$this->tabs();

		} else if ($this->child_type == 'subtab') {

			// Do the subtabs.
			$this->subtabs();

		} else if ($this->child_type == 'section') {

			// Do the sections
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
	public function registerChild($type, $key, $value) {
		
		if ($type == 'subpage') {

			// Add it to the subpages array.
			$this->subpages[$key] = $value;

		// Is it a tab?
		} else if ($type == 'tab') {

			// Add it to the tabs array.
			$this->tabs[$key] = $value;

		// Is it a subtab?
		} else if ($type == 'subtab') {

			// Add it to the subtabs array
			$this->subtabs[$key] = $value;

		// Is it a section?
		} else if ($type == 'section') {

			// Add it to the sections array
			$this->sections[$key] = $value;
		
		}
	}
}