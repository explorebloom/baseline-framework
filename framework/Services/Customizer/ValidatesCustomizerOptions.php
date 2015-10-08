<?php
namespace Baseline\Services\Customizer;

use Baseline\Helper\IsSingleton;
use Baseline\Services\Customizer\MakesCustomizerFields;
use Baseline\Services\Customizer\SanitizesCustoimzerFields;

class ValidatesCustomizerOptions {

	use IsSingleton;

	/**
	 * An instance of the customizer class that called this class.
	 */
	protected $customizer_class;

	/**
	 * An array of all of the valid customizer options.
	 */
	protected $valid_customizer_options = array(
		
		// All avaliable panel options.
		'panel' => array(
			'title',
			'description',
			'priority'
		),

		// All avaliable Section options
		'section' => array(
			'title',
			'description',
			'priority',
			'capability',
			'theme_supports',
		),

		// All avaliable Control options
		'control' => array(
			'type',
			'priority',
			'label',
			'description',
			'input_attr',
			'choices',
			'active_callback',
			'mime_type',
			'settings',
		),

		// All avaliable Setting options
		'setting'	=> array(
			'capability',
			'theme_supports',
			'default',
			'transport',
			'sanitize_callback',
			'sanitize_js_callback',
		),

	);

	public function __construct()
	{
		// Sets the instance of the customizer that called this class to a property.
		$this->customizer_class = MakesCustomizerFields::getInstance();
	}

	/**
	 * Main function for validating all of the different options for customizer values.
	 */
	public function validate($type, $options)
	{
		// Get the valid options for that type.
		$valid_options = $this->valid_customizer_options[$type];

		// Will put validated options here.
		$validated_options = array();

		// Validate each option.
		foreach($options as $key => $value) {
			// Is it a valid option?	
			if (in_array($key, $valid_options)) {
				// Then add it.
				$validated_options[$key] = $value;
			}
		}

		// Return the validated options.
		return $validated_options;
	}
}