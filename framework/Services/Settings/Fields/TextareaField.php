<?php
namespace Baseline\Services\Settings\Fields;

class TextareaField {

	protected $options;


	public function __construct($options)
	{
		$this->options = $options;

		// Figure out the size of the text area.
		if ($this->options['size'] == 'small') {

			// Pass in the classes needed for small textarea.
			$this->makeTextareaField('code');

		} else {

			// Pass in the classes needed for regular textareas.
			$this->makeTextareaField('code large-text');
		}
	
	}

	private function makeTextareaField($class)
	{
		echo '<fieldset>';
		echo $this->options['before'];
		$this->makeInput($class);
		echo $this->options['after'];
		$this->makeSubtitle();
		echo '<fieldset>';
	}

	private function makeInput($class)
	{
		$values = get_option($this->options['section']);
		echo '<textarea';
			
			// set the id and the name and the value.
			echo ' id="' . $this->options['id'] . '"';
			echo ' name="' . $this->options['section'] . '[' . $this->options['id'] . ']' . '"';

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

		echo '>';
		echo $values[$this->options['id']];
		echo '</textarea>';
	}

	private function makeSubtitle()
	{
		// Close the text tag.
		if ($this->options['subtitle']) {
			echo '<p class="description">' . $this->options['subtitle'] . '</p>';
		}
	}

}