<?php

/*
|--------------------------------------------------------------------------
| Local Content Module Registration
|--------------------------------------------------------------------------
|
| This is the main config file where all of the different content modules
| will be set. It returns an associative array in which the key is the
| path to the file and the value is the class name for that module.
|
*/

return array(

	// The base directory where the framework will search is
	// src/Modules/Content, so define from there.

	// All of the navigation content modules.
	'Nav\DefaultNav',
	'Nav\CoolNav',
	'Nav\BoldNav',

	// All of the header content modules.
	'Header\DefaultHeader',
	'Header\CoolHeader',
	'Header\BoldHeader',
);