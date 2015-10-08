<?php
// Start auto loading...
require 'vendor/autoload.php';

use Baseline\BaselineFramework;

/*
|--------------------------------------------------------------------------
| Westco Framework Initialization
|--------------------------------------------------------------------------
|
| This is the init file. It boots up the framework, and sets up all of the
| different helper functions and variables that will be needed. Include
| this file at the top of functions.php to use it in your theme.
|
*/

/**
 * Initializes the framework with path to your config directory.
 *
 * @param string $config_path
 */
function baseline_init($config_path) {
	global $_baseline;
	$_baseline = BaselineFramework::getInstance($config_path);
	return $_baseline;
}