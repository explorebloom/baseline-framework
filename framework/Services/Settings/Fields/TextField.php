<?php
namespace Baseline\Services\Settings\Fields;

class TextField {

	protected $options;

	/**
	 * Constructs the objects and sets all of the values from the passed options.
	 */
	public function __construct($options)
	{
		// Set all of the options here.
		$this->options = $options;
		
		// Figure out the size of the input.
		if ($this->options['size'] == 'small') {
		
			// Pass the small-text class to the input
			$this->makeTextField('small-text');
		
		} else if ($this->options['size'] == 'medium') {
		
			// Medium is made with no class.
			$this->makeTextField('');
		
		} else if ($this->options['size'] == 'large') {
		
			// Pass in the large-text class.
			$this->makeTextField('large-text');

		} else {

			// Pass the default regular-text class to the input.
			$this->makeTextField('regular-text');

		}
	}

	/**
	 * Makes a small text field.
	 */
	private function makeTextField($size_class)
	{
		echo $this->options['before'];
		$this->makeInput($size_class);
		echo $this->options['after'];
		$this->subtitle();
	}


	/**
	 * Makes a regular sized text field.
	 */
	private function makeInput($class)
	{
		$values = get_option($this->options['section']);

		echo '<input type="text"';
			
			// set the id and the name and the value.
			echo ' id="' . $this->options['id'] . '"';
			echo ' name="' . $this->options['section'] . '[' . $this->options['id'] . ']' . '"';
			echo ' value="' . $values[$this->options['id']] . '"';

			// Set any additional attributes.
			if (is_array($this->options['attributes'])) {
				foreach($this->options['attributes'] as $attribute => $value) {
					echo ' ' . $attribute . '="' . $value . '"';
				}
			}

			// If the extra class is set add on a space so it isn't connected to the base class.
			$extra_class = $this->options['class'] ? ' ' . $this->options['class'] : '';

			// Add the base class and the extra class given.
			echo ' class="' . $class . $extra_class . '"';

		// Close the input tag.
		echo '>';
	}

	private function subtitle()
	{
		// Close the text tag.
		if ($this->options['subtitle']) {
			echo '<p class="description">' . $this->options['subtitle'] . '</p>';
		}
	}

}