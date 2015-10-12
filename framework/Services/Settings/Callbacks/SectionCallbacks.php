<?php
namespace Baseline\Services\Settings\Callbacks;

class SectionCallbacks {

	/**
	 * Holds the page's options.
	 */
	public $options;


	/**
	 * This is the function shows the tab's content.
	 */
	public function callback()
	{
		echo '<p>' . $this->options['description'] . '</p>';
	}

	/**
	 * This sets all of the page's properties to the callback class.
	 */
	public function setProperties($options)
	{
		$this->options = $options;
	}

}