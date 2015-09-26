<?php
namespace Westco\Modules\Content;

interface ContentModule {

	/**
	 * Returns array of settings that configure and register the specific module.
	 *
	 * @return array
	 */
	public function configuration();
	/*
	 | Return Array Values
	 |--------------------------------------------------------------------------------------
	 | 'name' => (string) | (required)
	 |     - This is the name that will be used throughout admin menus to display the module
	 |
	 | 'slug' => (string) | (required)
	 |		- The database friendly version of the module name that will identify it.
	 |
	 | 'template' => (string) | (optional)
	 |		- The file name of the module's template. 
	 |		- default: slug + _template.php.
	 |
	 */ 
}