<?php
namespace Baseline\Services\Settings\Traits;

trait HandlesTabs {

	/**
	 * Makes tabs based off of the Objects set tabs.
	 */
	private function tabs()
	{
		// Get the current tab.
		$current_tab = $_GET['tab'];

		// If the tab is not set, get the first one.
		if (!$current_tab) {
			reset($this->tabs);
			$current_tab = key($this->tabs);
		}

		// Make the tab bar
		$this->makeTabBar($current_tab);

		// Get the callback class
		$tab_callback = $this->tabs[$current_tab];

		// Call it's callback function.
		$tab_callback->callback();
	}

	/**
	 * Constructs the html for the tab bar.
	 */
	private function makeTabBar($current_tab)
	{
		// Open the tab bar.
		echo '<h2 class="nav-tab-wrapper">';

		foreach($this->tabs as $id => $tab_class)
		{
			// Set up the variables.
			$active = $current_tab == $id ? ' nav-tab-active' : '';
			$link = '?page=' . $this->options['id'] . '&tab=' . $id;
			$class = 'nav-tab' . $active;
			$display = $tab_class->options['display'];

			// Echo out the tab.
			echo '<a href="' . $link . '" class="' . $class . '">' . $display . '</a>';
		}

		// Close the tab bar.
		echo'</h2>';
	}

	/**
	 * Make Subpage Tab Bar.
	 */

}