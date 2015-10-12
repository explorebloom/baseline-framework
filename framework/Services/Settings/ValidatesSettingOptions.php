<?php
namespace Baseline\Services\Settings;

use Baseline\Helper\IsSingleton;

class ValidatesSettingOptions {

	use IsSingleton;

	/**
	 * An associative array of all of the valid options for each setting.
	 */
	protected $valid_options = array(
		
		'page' => array(
			'type' => true,
			'contents' => true,
			'page_title' => true,
			'menu_title' => true,
			'tabs' => false,
			'capability' => false,
			'icon_url' => false,
			'position' => false,
		),

		'subpage' => array(
			'type' => true,
			'contents' => true,
			'page_title' => true,
			'menu_title' => true,
			'subtabs' => false,
			'capability' => false,
		),

		'tab' => array(
			'type' => true,
			'contents' => true,
			'display' => true,
		),

		'subtab' => array(
			'type' => true,
			'contents' => true,
			'display' => true,
		),

		'section' => array(
			'type' => true,
			'contents' => true,
			'title' => true,
			'description' => false,
		),

		'setting' => array(
			'type' => true,
			'title' => true,
			'setting_type' => true,
		)
	);

	/**
	 * Takes an associative array of items and removes all of the options that are invalid.
	 */
	public function validate(array $object)
	{
		// Does it have type set? Is it a valid type?
		if (!array_key_exists('type', $object) || is_null($this->valid_options[$object['type']])) {
			return false;
		}

		// Here are the valid options for it's type.
		$valid = $this->valid_options[$object['type']];

		// We will put all valid items here.
		$validated = array();

		// Loop over each item in the object and check if it is a valid option.
		foreach($object as $option => $value) {
			// Is this one of the valid options?
			if (array_key_exists($option, $valid)) {
				// Then add it to our validated options.
				$validated[$option] = $value;
			}
		}

		// Loop over all of the valid options and make sure the required ones are there.
		foreach($valid as $option => $is_required) {
			
			// Is it required but not there?
			if ($is_required && !array_key_exists($option, $object)) {
				return false;
			}

		}

		// Return the validated keys.
		return $validated;
	}

}