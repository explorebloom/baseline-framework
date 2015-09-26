<?php
namespace Baseline\Helper;

trait ValidatesCustomizerOptions {

	protected $valid_customizer_options = array(
		
		// All avaliable panel options.
		'panel' => array(
			'title',
			'description',
			'priority'
		),

		// All avaliable Section options
		'section' => array(
			'title',
			'description',
			'priority',
			'capability',
			'theme_supports',
		),

		// All avaliable Control options
		'control' => array(
			'type',
			'priority',
			'label',
			'description',
			'input_attr',
			'active_callback',
			'mime_type',
		),

		// All avaliable Setting options
		'setting'	=> array(
			'capability',
			'theme_supports',
			'default',
			'transport',
			'sanatize_callback',
			'sanatize_js_callback',
		),

	);

}