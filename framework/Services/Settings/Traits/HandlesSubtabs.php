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
			// Open the Subtab bar
			echo '<ul class="subsubsub" style="margin-top: -5px;">';
			
			// Construct each link.
			end($this->subtabs);
			$last_subtab = key($this->subtabs);
			reset($this->subtabs);
			foreach ($this->subtabs as $id => $callback) {
				$active = $current_subtab == $id ? ' class="current"' : '';
				$link = '?page=' . $this->options['page'] . '&tab=' . $this->options['id'] . '&subtab=' . $id;
				$display = $callback->options['display'];
				$seperator = $id == $last_subtab ? '' : ' |';
				echo '<li><a href="' . $link . '"' . $active . '>' . $display . '</a>' . $seperator . '</li>';
			}

			// Close the Subtab bar.
			echo '</ul><div style="clear: both;"></div>';
		}
}