<?php
namespace Westco\Modules\Content\Header;

use Framework\Contracts\ContentModule;

class CoolHeader implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Cool Header',
			'slug'			=> 'cool_header',
			'category'		=> 'heading_template',
			'template_path'	=> '/Header/Templates/',
		);
	}

}