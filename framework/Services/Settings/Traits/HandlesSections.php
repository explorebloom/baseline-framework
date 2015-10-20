<?php
namespace Baseline\Services\Settings\Traits;

trait HandlesSections {

	private function sections()
	{
		// Do we need to make subtabs from the sections?
		if ($this->options['subtab_style'] == 'sections') {
			$current_subtab = $this->getCurrentSubtab();
			$this->makeSubtabsFromSections($current_subtab);
		}

		// Open the form.
		echo '<form method="post" action="options.php">';

		// If we aren't making subtabs show all sections from this object's id.
		if ($this->options['subtab_style'] != 'sections') {
		
			settings_fields($this->options['id']);
			do_settings_sections($this->options['id']);
		
		// If we are making subtabs figure out which one we are on.
		} else {
			settings_fields($current_subtab);
			do_settings_sections($current_subtab);
		}

		submit_button();
		echo '</form>';
	}

	private function getCurrentSubtab()
	{
		// Get the current tab.
		$current_subtab = $_GET['subtab'];

		// If the tab is not set, get the first one.
		if (!$current_subtab) {
			reset($this->sections);
			$current_subtab = key($this->sections);
		}

		return $current_subtab;
	}

	private function makeSubtabsFromSections($current_subtab)
	{
		// Get the last subtab
		end($this->sections);
		$last_subtab = key($this->sections);
		reset($this->sections);

		// If this is under tabs, make sure tab and page are set.
		if (get_class($this) === 'Baseline\Services\Settings\Callbacks\TabCallbacks') {
			$tab = '&tab=' . $this->options['id'];
			$page = '?page=' . $this->options['page'];
		} else {
			$tab = '';
			$page = '?page=' . $this->options['id'];
		}

		// Open the subtab bar.
		echo '<ul class="subsubsub" style="margin-top: -5px;">';

		foreach($this->sections as $section => $options) {

			// Open the subtab
			echo '<li>';
			$subtab = '&subtab=' . $section;
			
			// Create the link
			$href=' href="' . $page . $tab . $subtab . '"';

			// Is this the current subtab?
			$active_class = $current_subtab == $section ? ' class="current"' : '';

			// Open the link.
			echo '<a' . $active_class . $href . '>';

			// Set the display;
			echo $options['title'];
			
			// Close the link.
			echo '</a>';

			// Seperator unless it is the last link.
			echo $section == $last_subtab ? '' : ' |';

			echo '</li>';

		}



	}

}