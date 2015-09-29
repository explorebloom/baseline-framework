<?php
namespace Baseline\Helper;

/*
|--------------------------------------------------------------------------
| Prepare Modules Items for Customizer
|--------------------------------------------------------------------------
|
| This trait parses an associative array of Registered Module Categories 
| and their respective registered Content Modules into an associative
| array that the Customizer service class can digest and register.
|
*/

trait PrepsModulesForCustomizer {

	/**
	 * Array of allowed customizer keys for Modules
	 */
	protected $allowed_category_customizer_keys = array(
		'capability',
		'theme_supports',
		'default',
		'transport',
		'sanatize_callback',
		'sanatize_js_callback',
		'priorty',
		'section',
		'title',
		'description',
		'active_callback'
	);

	public function getModulesForCustomizer() {
		// Get all categorized modules.
		$items = $this->modules->getRegisteredItems();

		// Return them parsed for customizer.
		return $this->makeModulesForCustomizer($items);
	}

	/**
	 * Main function for parsing through
	 */
	private function makeModulesForCustomizer($categories)
	{
		// Empty array to put formated categories in.
		$for_customizer = array();

		// Loop over categories and format it.
		foreach ($categories as $category => $options) {
			$for_customizer[$category] = $this->parseThroughCategory($options);
		}

		// Return the formatted categories.
		return $for_customizer;
	}

	/**
	 * Goes through a category and parses it's options to work with the wordpress customizer.
	 */
	private function parseThroughCategory($options)
	{
		// Will fill this with parsed objects.
		$parsed = array();
		
		// Set object to setting.
		$parsed['object'] = 'setting';
		
		// Set type to select.
		$parsed['type'] = 'select';

		// Switch title to label.
		$parsed['label'] = $options['name'];

		// Add all allowed values.
		foreach($options as $option => $value) {
			if (in_array($option, $this->allowed_category_customizer_keys)) {
				$parsed[$option] = $value;
			}
		}

		// Parse the content modules
		$parsed['choices'] = $this->parseModulesForCustomizer($options['modules']);

		// Return the parsed variables.
		return $parsed;
	}

	/**
	 * Goes through a categories modules and returns a parsed array for the customizer.
	 */
	private function parseModulesForCustomizer($modules)
	{
		// Will fill this array with the parsed modules.
		$parsed = array();

		// Loop through each of the modules and create value => label array.
		foreach ($modules as $id => $options) {
			// slug is the value and name is the label.
			$parsed[$options['slug']] = $options['name'];
		}

		// Return the parsed modules.
		return $parsed;
	}
}