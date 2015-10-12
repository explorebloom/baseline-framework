<?php
namespace Baseline\Services\Settings;

use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Constructors\MakesPages;
use Baseline\Services\Settings\Constructors\MakesSubpages;
use Baseline\Services\Settings\Constructors\MakesSections;


/*
|--------------------------------------------------------------------------
| Main Setting Object Constructor
|--------------------------------------------------------------------------
|
| This is the entry point for constructing the different settings. It goes
| through the array of settings returned by the settings config file and
| parses through it, and passes field to their specific constructors.
|
*/

class MakesSettingObjects {

	use IsSingleton;

	/**
	 * Holds the instance of our page constructor
	 */
	protected $page;

	/**
	 * Holds an instance of our subpage constructor.
	 */
	protected $subpage;

	/**
	 * Holds an instance of our section constructor.
	 */
	protected $section;


	/**
	 * Constructs the class
	 */
	private function __construct()
	{
		$this->validator = ValidatesSettingOptions::getInstance();
		$this->page = MakesPages::getInstance();
		$this->subpage = MakesSubpages::getInstance();
		$this->section = MakesSections::getInstance();
	}

	/**
	 * Takes an array of setting items and creates them.
	 *
	 * @param array $object
	 * @param string $parent
	 */
	public function make(array $object)
	{
		// loop over the array of items.
		foreach($object as $id => $options) {
			
			// Validate the optoins.
			$valid_optoins = $this->validator->validate($options);
			
			// If invalid move on to the next option.
			if ($valid_options === false) {
				continue;
			}

			// These types are not allowed as starting points for the array.
			if (
				$options['type'] == 'tab' ||
				$options['type'] == 'subtab' ||
				$options['type'] == 'setting'
			) {
				// Move on to the next option.
				continue;
			}
			
			// Call the necesarry constructor based off of the type.
			if ($options['type'] === 'page') {
				// Make a page
				$this->page->make($id, $options);
			} else if ($options['type'] === 'subpage') {
				// Make a subpage
				$this->subpage->make($id, $options, null);
			} else if ($options['type'] === 'setting') {
				// Make a section
				$this->section->make($id, $options);
			}

		}

	}

}