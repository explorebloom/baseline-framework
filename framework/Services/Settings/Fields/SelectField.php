<?php
namespace Baseline\Services\Settings\Fields;

class SelectField {

	protected $options;

	public function __construct($options)
	{
		$this->options = $options;
		$this->makeSelectField();
	}

	private function makeSelectField()
	{
		echo $this->options['before'];
		echo '<select name="' . $this->options['section'] . '[' . $this->options['id'] . ']'. '"';
			echo 'id="' . $this->options['id'] . '"';
			
			// Add any additional attributes
			foreach ($this->options['attributes'] as $attribute => $value) {
				echo ' ' . $attribute . '="' . $value . '"';
			}

			// Add any additional classes
			if ($this->options['class']) {
				echo $class = ' class="' . $this->options['class'] . '"';
			}

			// Add the base class and the extra class given.
			echo ' class="' . $class . $extra_class . '"';
			echo '>';
			$index = 1;
			foreach ($this->options['select_options'] as $value => $label) {
				// Is it opening a group?
				if (substr($label, 0, 12 ) === "_group_open_") {
					$label = str_replace('_group_open_', '', $label);
					$this->openSelectGroup($label);

				// Is it a closing a group?
				} elseif ($label == '_group_close') {
					$this->closeSelectGroup();
				
				// Otherwise it is just an option.
				} else {
					$this->makeSelectOption($value, $label);
				}

			}
		echo '</select>';
		echo $this->options['after'];
		echo '<p class="description">' . $this->options['subtitle'] . '</p>';
	}

	private function openSelectGroup($label)
	{
		echo '<optgroup label="' . $label . '">';
	}

	private function closeSelectGroup()
	{
		echo '</optgroup>';
	}

	private function makeSelectOption($value, $label)
	{
		$section_value = get_option($this->options['section']);
		$current_select = $section_value[$this->options['id']];
		$selected = $value == $current_select ? ' selected="selected"' : '';
		echo '<option value="' . $value . '"';
		echo $selected;
		echo '>';
		echo $label;
		echo '</option>';
	}

}