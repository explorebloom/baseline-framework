<?php
namespace Baseline\Services\Customizer;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Registrars\CustomizerRegistrar;
use Baseline\Services\Customizer\ValidatesCustomizerOptions;
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

class MakesCustomizerFields {

	use IsSingleton;

	/**
	 * This holds an instanace of the class that sanitizes the customizer fields.
	 */
	public $sanitizer;

	/**
	 * This holds an instance of the class the validates the customizer field options.
	 */
	public $validator;

	/**
	 * This holds an instance of the class that handles Baseline's Custom Callbacks for customizer field options.
	 */
	public $callbacks;

	/**
	 * This is the customizer object that gets passed to the function that is registered by the 'customize_register' action
	 *
	 * @var object
	 */
	protected $wp_customize;


	/**
	 * This is the prefix that comes from the framework config file that all settings should be registered with.
	 *
	 * @var string
	 */
	protected $setting_prefix;

	/**
	 * This is the main function that gets called every time an array of options needs to be registered.
	 *
	 * @param array $objects
	 * @param string $type
	 * @param string $parent
	 */
	public function register($objects, $storage_type, $parent = null, $prefix = null, $wp_customize = null)
	{
		// Get the helper classes.
		// $this->sanitizer = SanitizesCustomizerFields::getInstance();
		$this->validator = ValidatesCustomizerOptions::getInstance();

		// Set wp_customize as property for first run through.
		if ($wp_customize) {
			$this->wp_customize = $wp_customize;
		}

		// Set the prefix as a property if it 
		if ($prefix) {
			$this->setting_prefix = $prefix;
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

			// Not enough stuff was passed.
		}
	}

	/**
	 * Registers a Panel in the customizer
	 */
	private function registerPanel($storage_type, $id, $options)
	{
		// Add the setting prefix.
		$id = $this->setting_prefix . $id;

		// Validate the options
		$validated_options = $this->validator->validate('panel', $options);

		// Register the Panel
		$this->wp_customize->add_panel($id, $validated_options);
		// var_dump(array('Panel: ' . $id => $validated_options));

		// Register it's contents
		$this->register($options['contents'], $storage_type, $id);
	}

	/**
	 * Registers a Section in the customizer
	 */
	private function registerSection($storage_type, $id, $options, $panel)
	{
		// Add the setting prefix.
		$id = $this->setting_prefix . $id;
		
		// Validate the options
		$validated_options = $this->validator->validate('section', $options);

		// Attach it to a panel if provided.
		if ($panel) {
			$validated_options['panel'] = $panel;
		}

		// Register the section
		$this->wp_customize->add_section($id, $validated_options);
		// var_dump(array('Section: ' . $id => $validated_options));

		// Register it's contents
		$this->register($options['contents'], $storage_type, $id);
	}

	/**
	 * Register a Setting in the customizer
	 */
	private function registerSetting($storage_type, $id, $options)
	{
		// Add the setting prefix.
		$id = $this->setting_prefix . $id;
		
		// Validate the options
		$validated_options = $this->validator->validate('setting', $options);

		// Add in the storage type
		$validated_options['type'] = $storage_type;

		// Register the Setting
		$this->wp_customize->add_setting($id, $validated_options);
	}

	/**
	 * Registers a Control in the customizer
	 */
	private function registerControl($id, $options, $section)
	{
		// Add the setting prefix.
		$id = $this->setting_prefix . $id;
		
		// Validate the options
		$validated_options = $this->validator->validate('control', $options);

		// Add it to it's given section
		$validated_options['section'] = $section;

		// Is it a color type?
		if ($options['type'] === 'color') {

			// Type is not needed.
			unset($validated_options['type']);

			// Register the control
			$this->wp_customize->add_control(new \WP_Customize_Color_Control($this->wp_customize, $id, $validated_options));
			// var_dump(array('Control: ' . $id => $validated_options));

			return;
		}

		// Is it the file type?
		if ($options['type'] === 'media') {

			// Type is not needed.
			unset($validated_options['type']);

			// Register the control
			$this->wp_customize->add_control(new \WP_Customize_Media_Control($this->wp_customize, $id, $validated_options));
			// var_dump(array('Control: ' . $id => $validated_options));

			return;
		}

		// All other types
		$this->wp_customize->add_control($id, $validated_options);
		// var_dump(array('Control: ' . $id => $validated_options));
	}

}