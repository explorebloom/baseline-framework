<?php
namespace Baseline\Services\Settings;

use Baseline\Services\Settings\Fields\TextField;
use Baseline\Services\Settings\Fields\TextareaField;
use Baseline\Services\Settings\Fields\CheckboxField;
use Baseline\Services\Settings\Fields\SelectField;
use Baseline\Services\Settings\Fields\RadioField;
use Baseline\Services\Settings\Fields\Html5Field;

/*
|--------------------------------------------------------------------------
| Main Setting Field Constructor
|--------------------------------------------------------------------------
|
| This is the main entry class for making different setting fields. It handles
| directing different types of settings to their correct fields class which
| will handle the construction of the field's html code from its options.
|
*/

class MakesSettingFields {

	/**
	 * All of the additional options that are allowed for a text field.
	 */
	protected $setting_defaults = array(
	
		// Al the defaults for a text field.
		'text'	=> array(
			'size' => 'regular', // small, regular
			'before' => '', // Goes before a small text field. Doesn't show up on a regular text field.
			'after' => '', // goes after a small text field. Doesn't show up on a regular text field.
			'subtitle'	=> '', // Gets placed underneath a regular text field. Doesn't show up on small text fields.
			'class'	=> '', // The custom class(es) that will be added to the input field
			'attributes' => array(), // The custom attributes that you want your setting to have.
		),

		// All the defaults for a textarea field.
		'textarea'	=> array(
			'size'	=> 'regular',
			'before'	=> '',
			'after'		=> '',
			'subtitle'	=> '',
			'class'	=> '',
			'attributes'	=> array(),
		),

		// All the defaults for a checkbox field.
		'checkbox'	=> array(
			'before' => '',
			'after' => '',
			'subtitle' => '',
			'class'	=> '',
			'attributes' => array(),
			'boxes'	=> array(),
		),

		// All the defaults for a select field.
		'select' => array(
			'before' => '',
			'after'	=> '',
			'subtitle' => '',
			'class' => '',
			'attributes' => array(),
			'select_options' => array(),
		),

		// All the defaults for a radio field.
		'radio' => array(
			'before' => '',
			'after' => '',
			'subtitle' => '',
			'class'	=> '',
			'attributes' => array(),
			'radio_options' => array(),
		),

		// All the defaults for an html5 field
		'html5' => array(
			'before' => '',
			'after' => '',
			'subtitle' => '',
			'class' => '',
			'attributes' => array(),
		)

	);

	/**
	 * Creates the setting field based off of the optinos passed.
	 */
	public function makeFromOptions($options)
	{
		// Add on the section and id to the given options.
		$options['given_options']['id'] = $options['id'];
		$options['given_options']['section'] = $options['section'];

		// Get the setting type.
		$type = $options['setting_type'];
		// Is it not set or set to text?
		if (is_null($type) || $type == 'text') {

			// Make a text field.
			return $this->text($options['given_options']);

		// Is it set to textarea?
		} elseif ($type == 'textarea') {

			// Make a textarea field.
			return $this->textarea($options['given_options']);

		// Is it set to a checkbox?
		} else if ($type == 'checkbox') {

			// Make a checkbox field.
			return $this->checkbox($options['given_options']);

		// Is it set to select?
		} else if ($type == 'select') {
			
			// Make a select field.
			return $this->select($options['given_options']);

		// Is it set to radio?
		} else if ($type == 'radio') {

			// Make a radio field.
			return $this->radio($options['given_options']);
		
		// Is it set to html5
		} else if ($type == 'html5') {

			// Make an html5 field
			return $this->html5($options['given_options']);

		}
	}

	/**
	 * Creates a text field.
	 */
	private function text($options)
	{
		$options = $this->setAllDefaults('text', $options);
		return new TextField($options);
	}

	/**
	 * Creates a textarea field
	 */
	private function textarea($options) {

		$options = $this->setAllDefaults('textarea', $options);
		return new TextareaField($options);
	}

	/**
	 * Creates a checkbox field
	 */
	private function checkbox($options)
	{
		$options = $this->setAllDefaults('checkbox', $options);
		return new CheckboxField($options);
	}

	/**
	 * Creates a select field.
	 */
	private function select($options)
	{
		$options = $this->setAllDefaults('select', $options);
		return new SelectField($options);
	}

	/**
	 * Creates a radio field.
	 */
	private function radio($options)
	{
		$options = $this->setAllDefaults('radio', $options);
		return new RadioField($options);
	}

	/**
	 * Creates a html5 field.
	 */
	private function html5($options)
	{
		$options = $this->setAllDefaults('html5', $options);
		return new Html5Field($options);
	}

	/**
	 * Creates the fields defaults for it.
	 */
	private function setAllDefaults($type, $options)
	{
		// Get the defaults for the type.
		$defaults = $this->setting_defaults[$type];


		// Add in the defaults if values are not set.
		foreach($defaults as $option => $default) {
			if (is_null($options[$option])) {
				$options[$option] = $default;
			}
		}

		return $options;
	}

}