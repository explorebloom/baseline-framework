<?php
namespace Westco\Modules\Content\Header;

use Framework\Contracts\ContentModule;

class BoldHeader implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Bold Header',
			'slug'			=> 'bold_header',
			'category'		=> 'heading_template',
			'template_path'	=> '/Header/Templates/'
		);
	}

}