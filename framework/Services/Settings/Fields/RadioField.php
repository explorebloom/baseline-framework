<?php
namespace Baseline\Services\Settings\Fields;

class RadioField {

	protected $options;

	public function __construct($options)
	{
		$this->options = $options;
		$this->makeRadioField();
	}

	private function makeRadioField()
	{
		echo '<fieldset>';
			echo $this->options['before'];
			foreach ($this->options['radio_options'] as $value => $label)
			{
				$this->radioField($value, $label);
			}
			echo $this->options['after'];
			echo '<p class="description">' . $this->options['subtitle'] . '</p>';
		echo '</fieldset>';
	}

	private function radioField($value, $label)
	{
		$section_settings = get_option($this->options['section']);
		echo '<label title="' . $this->options['id'] . '">';
			echo '<input type="radio"';
			echo ' name="' . $this->options['section'] . '[' . $this->options['id'] . ']"';
			echo ' value="' . $value . '"';
			
			// Set any additional attributes.
			if (is_array($this->options['attributes'])) {
				foreach($this->options['attributes'] as $attribute => $value) {
					echo ' ' . $attribute . '="' . $value . '"';
				}
			}

			// Add any additional classes
			if ($this->options['class']) {
				echo $class = ' class="' . $this->options['class'] . '"';
			}
			
			checked( $value, $section_settings[$this->options['id']], true );
			echo '>';
			echo $label;
		echo '</label><br>';
	}

}