<?php
namespace Baseline\Services;

use Baseline\Helper\ValidatesCustomizerOptions;
/*
|--------------------------------------------------------------------------
| Framework Customizer Class
|--------------------------------------------------------------------------
|
| This is the main class that handles all of the customizer fields. It will
| take the associative array that is in Config/customizer.php and create
| all Panels, Section, Controls, and Settings that the file defines.
|
*/

class Customizer {

	use ValidatesCustomizerOptions;

	/**
	 * This is the customizer object that gets passed to the function that is registered by the 'customize_register' action
	 *
	 * @var object
	 */
	protected $wp_customize;


	/**
	 * This is the main function that gets called every time an array of options needs to be registered.
	 *
	 * @param array $objects
	 * @param string $type
	 * @param string $parent
	 */
	public function register($objects, $storage_type, $parent = null, $wp_customize = null)
	{
		if ($wp_customize) {
			$this->wp_customize = $wp_customize;
		}
		// Loop over the array of objects and register them.
		foreach($objects as $id => $options) {
			
			// Is it a Panel? Panel's MUST NOT have parents.
			if ($options['object'] == 'panel' && is_null($parent)) {
				$this->registerPanel($storage_type, $id, $options);

			// Is it a Section? Sections may or may not have parents.
			} elseif ($options['object'] == 'section') {
				$this->registerSection($storage_type, $id, $options, $parent);
			
			// Is it a Setting? Settings MUST have parents.
			} elseif ($options['object'] == 'setting' && $parent) {
				
				// Register Settings and Controls in one go.
				$this->registerSetting($storage_type, $id, $options);
				$this->registerControl($id, $options, $parent);
			}
			// Nothing else is allowed at this level.
		}
	}

	/**
	 * Registers a Panel in the customizer
	 */
	private function registerPanel($storage_type, $id, $options)
	{
		// Validate the options
		$validated_options = $this->parseOptions('panel', $options);

		// Register the Panel
		$this->wp_customize->add_panel($id, $validated_options);
		var_dump(array('Panel: ' . $id => $validated_options));

		// Register it's contents
		$this->register($options['contents'], $storage_type, $id);
	}

	/**
	 * Registers a Section in the customizer
	 */
	private function registerSection($storage_type, $id, $options, $panel)
	{
		// Validate the options
		$validated_options = $this->parseOptions('section', $options);

		// Attach it to a panel if provided.
		if ($panel) {
			$validated_options['panel'] = $panel;
		}

		// Register the section
		$this->wp_customize->add_section($id, $validated_options);
		var_dump(array('Section: ' . $id => $validated_options));

		// Register it's contents
		$this->register($options['contents'], $storage_type, $id);
	}

	/**
	 * Register a Setting in the customizer
	 */
	private function registerSetting($storage_type, $id, $options)
	{
		// Validate the options
		$validated_options = $this->parseOptions('setting', $options);

		// Add in the storage type
		$validated_options['type'] = $storage_type;

		// Register the Setting
		$this->wp_customize->add_setting($id, $validated_options);
		var_dump(array('Setting: ' . $id => $validated_options));

	}

	/**
	 * Registers a Control in the customizer
	 */
	private function registerControl($id, $options, $section)
	{
		// Validate the options
		$validated_options = $this->parseOptions('control', $options);

		// Add it to it's given section
		$validated_options['section'] = $section;
		
		// Is it a color type?
		if ($options['type'] === 'color') {

			// Type is not needed.
			unset($validated_options['type']);

			// Register the control
			$this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, $id, $validated_options));
			var_dump(array('Control: ' . $id => $validated_options));

			return;
		}

		// Is it the file type?
		if ($options['type'] === 'media') {

			// Type is not needed.
			unset($validated_options['type']);

			// Register the control
			$this->wp_customize->add_control(new \WP_Customize_Media_Control($this->wp_customize, $id, $validated_options));
			var_dump(array('Control: ' . $id => $validated_options));

			return;
		}

		// All other types
		$this->wp_customize->add_control($id, $validated_options);
		var_dump(array('Control: ' . $id => $validated_options));

	}

	/**
	 * Parses the options given into an array of valid options that will work for wordpress.
	 *
	 * @param string $object_type
	 * @param associative array $options
	 *
	 * @return associative array
	 */
	private function parseOptions($object_type, $options)
	{
		// Gets all the valid options for the object type.
		$allowed_options = $this->valid_customizer_options[$object_type];

		// Put valid options in here.
		$validated_options = array();

		// Loop over the options and only take what is allowed.
		foreach($options as $option => $value) {
			
			// Is the option allowed?
			if (in_array($option, $allowed_options)) {
				$validated_options[$option] = $value;
			}

		}

		// Return the validated options.
		return $validated_options;
	}

}