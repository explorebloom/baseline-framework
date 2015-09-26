<?php

/*
|--------------------------------------------------------------------------
| Customizer Panel Registration
|--------------------------------------------------------------------------
|
| This is the main configuration file for setting up the Initial Customizer
| panels for the framework. It simply returns an associative array that
| will map out all of the additional panels and sections of the theme.
|
*/


// Return an array of data
return array(

	// Panel: Theme Styles
	'theme_styles' => array(

		'object'		=> 'panel',
		'title'			=> 'Theme Styles',
		'description'	=> 'This is where you can set all properties for your theme and how you want it styled.',
		'priority'		=> 160,
		'contents'		=> array(
			
			// Section: Header Styles
			'header_styles' => array(

				'title'				=> 'Header Styles',
				'object'			=> 'section',
				'priority'			=> 160,
				'description'		=> 'Define your header styles here.',
				'active_callback' 	=> 'is_front_page',
				'contents'	=> array(

					// Setting: Display Site Title?
					'main_logo'	=> array(

						'object'			=> 'setting',
						'label'				=> 'Site Logo',
						'description' 		=> 'Upload your logo here.',
						'type'				=> 'media',
						'mime_type'			=> 'image',
						'capability'		=> 'manage_options',
						'default'			=> '',

					),

					// Setting: Display Site Title?
					'display_logo'	=> array(

						'object'			=> 'setting',
						'label'				=> 'Display Site Logo?',
						'description' 		=> 'If checked the site will not display the title, but rather the uploaded logo.',
						'type'				=> 'checkbox',
						'capability'		=> 'manage_options',
						'default'			=> '',
						'sanitize_callback'	=> '',

					),
				),
			),
		),
	),

	// Panel: Theme Layouts
	'theme-layout'	=> array(

		'object'		=> 'panel',
		'title'			=> 'Theme Layout',
		'description'	=> 'This is where all the different layouts for the theme are controlled',
		'contents'		=> array(

			// Section: Header Layouts
			'header-layout'	=> array(

				'object'		=> 'section',
				'title'			=> 'Header Layout',
				'description'	=> 'This is where all of the different header layouts are set.',
				'contents'		=> array(

					// Will register these from module content

				),
			),
		),
	),
);

/* Setting */

/*
	default

	capability

	theme_supports

	transport

	sanitize_callback

	sanitize_js_callback
*/



/* Control */

/*
	label

	description

	section

	priority

	type
*/



/* Sections */

/*
	title

	priority

	description

	active_callback
*/



/* Panels */

/*
	title

	description

	priority
*/