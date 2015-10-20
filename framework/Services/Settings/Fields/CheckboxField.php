<?php
namespace Baseline\Services\Settings\Fields;

class CheckboxField {

	protected $options;

	public function __construct($options)
	{
		$this->options = $options;
		$this->makeCheckboxField();
	}

	private function makeCheckboxField()
	{
		echo '<fieldset>';
		echo '<p>' . $this->options['before'] . '</p>';
		foreach($this->options['boxes'] as $value => $label) {
			$this->makeCheckbox($value, $label);
		}
		echo '<p>' . $this->options['after'] . '</p>';
		echo '<p class="description">' . $this->options['subtitle'] . '</p>';
		echo '</fieldset>';
	}

	private function makeCheckbox($value, $label)
	{
		$section_settings = get_option($this->options['section']);
		echo '<label for="' . $value . '">';
			echo '<input type="checkbox"';
				echo 'name="' . $this->options['section'] . '[' . $this->options['id'] . '_' . $value . ']"';
				echo 'id="' . $value . '"';
				echo 'value="1"';

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
				
				echo checked( 1, $section_settings[$this->options['id'] . '_' . $value], false );
			echo '>';
			echo $label;
		echo '</label><br>';
	}

}