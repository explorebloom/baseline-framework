<?php
namespace Westco\Modules\Content\Header;

use Framework\Contracts\ContentModule;

class DefaultHeader implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Default Header',
			'slug'			=> 'default_header',
			'category'		=> 'heading_template',
			'template_path'	=> '/Header/Templates/'
		);
	}

}