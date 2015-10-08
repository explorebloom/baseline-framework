<?php

/*
|--------------------------------------------------------------------------
| Framework configuration
|--------------------------------------------------------------------------
| This will be where all of the different configuartions will be for the 
| theme framework. All paths in this file should be based from the 
| directory that holds the src folder of the framework.
|
*/

return array(


	// This is the path to the location of all of the different content modules.
	'modules_path' => 'Modules/Content',


	// This is the path to the location of all of teh different content module categories.
	'module_categories_path' => 'Modules/Categories',


	// This is the prefix that is used for everything that is stored in the database.
	'settings_prefix' => 'westco_framework_',


	// What do you want the content module settings to be saved as?
	'save_module_settings_as' => 'theme_mod',


	// What do you want general theme settings to be saved as?
	'save_theme_settings_as' => 'option'

);