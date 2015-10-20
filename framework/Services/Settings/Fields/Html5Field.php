<?php
namespace Baseline\Services\Settings\Fields;

class Html5Field {

	protected $options;

	/**
	 * Constructs the object and
	 */
	public function __construct($options)
	{
		$this->options = $options;
		$this->makeHtml5Field();
	}

	private function makeHtml5Field()
	{
		$values = get_option($this->options['section']);

		echo '<input type="' . $this->options['html5'] . '"';
			
			// set the id and the name and the value.
			echo ' id="' . $this->options['id'] . '"';
			echo ' name="' . $this->options['section'] . '[' . $this->options['id'] . ']' . '"';
			echo ' value="' . $values[$this->options['id']] . '"';

			// Add any additional classes
			if ($this->options['class']) {
				echo $class = ' class="' . $this->options['class'] . '"';
			}

			// Set any additional attributes.
			if (is_array($this->options['attributes'])) {
				foreach($this->options['attributes'] as $attribute => $value) {
					echo ' ' . $attribute . '="' . $value . '"';
				}
			}

		// Close the input tag.
		echo '>';
	}

}