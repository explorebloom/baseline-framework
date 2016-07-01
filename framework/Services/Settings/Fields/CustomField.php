<?php
namespace Baseline\Services\Settings\Fields;

class CustomField {

	protected $options;

	/**
	 * Constructs the object and
	 */
	public function __construct($options)
	{
		$this->options = $options;
		echo $this->options['before'];
		$this->makeCustomField();
		echo $this->options['after'];

	}

	private function makeCustomField()
	{
		$values = get_option($this->options['section']);

		// Is the custom content a callable funciton that exists?
		if ($this->options['callable'] && function_exists($this->options['custom_content'])) {
			
			// Then call it and pass the section values and the field options.
			call_user_func($this->options['custom_content'], $values, $this->options);

		// Otherwise just echo it as a string.
		} else {
			echo $this->options['custom_content'];
		}
	}

}