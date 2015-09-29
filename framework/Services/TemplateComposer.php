<?php
namespace Baseline\Services;

use Baseline\Core\Content;
use Baseline\Core\Settings;
use Baseline\Core\Config;

class TemplateComposer {

	public function __construct($module_options)
	{
		// Get Modules Path from config.
		$modules_path = WestcoConfig::getInstance()->getFrameworkConfig('modules_path');

		// Create the file slug		
		$file_slug = $modules_path . $module_options['template_path'] . $module_options['category'];

		// Set predefined variables for template.
		set_query_var('_content', WestcoContent::getInstance());
		set_query_var('_settings', WestcoSettings::getInstance());
		set_query_var('_module', $module_options);

		// Do custom actions and call template part
		do_action('westco_before_' . $category, $module_options);
		get_template_part($file_slug, $module_options['slug']);
		do_action('westco_after_' . $category, $module_options);
	}

}