<?php
namespace Baseline\Services\Settings\Traits;

trait HandlesSubtabs {

	/**
	 * Creates the subtabs for the Setting Object based off of it's set subtabs.
	 */
	private function subtabs()
	{
		// Get the current tab.
		$current_subtab = $_GET['subtab'];
		if (!$current_subtab) {
			reset($this->subtabs);
			$current_subtab = key($this->subtabs);
		}

		// Make the tab bar
		$this->makeSubtabBar($current_subtab);

		$callback_class = $this->subtabs[$current_subtab];

		$callback_class->callback();
	}

	/**
	 * Creates the html for the Setting Object's subtabs.
	 */
	private function makeSubtabBar($current_subtab)
	{
		// Set up the page and subtab value.
		if ($this->type == 'tab') {
			$page = '?page=' . $this->options['page'];
			$tab = '&tab=' . $this->options['id'];
		} else {
			$page = '?page=' . $this->options['id'];
			$tab = '';
		}

		// Get the last subtab
		end($this->subtabs);
		$last_subtab = key($this->subtabs);
		reset($this->subtabs);
		
		// Open the Subtab bar
		echo '<ul class="subsubsub">';
		
		foreach ($this->subtabs as $id => $callback) {
			
			echo '<li>';
			$subtab = '&subtab=' . $id;
			
			// Create the link
			$href=' href="' . $page . $tab . $subtab . '"';
			
			// Is this the active link?
			$active_class = $current_subtab == $id ? ' class="current"' : '';

			
			// Open the link.
			echo '<a' . $active_class . $href . '>';

			// Set the display;
			echo $callback->options['display'];
			
			// Close the link.
			echo '</a>';

			// Seperator unless it is the last link.
			echo $id == $last_subtab ? '' : ' |';

			echo '</li>';
		}

		// Close the Subtab bar.
		echo '</ul><div style="clear: both;"></div>';
	}
}