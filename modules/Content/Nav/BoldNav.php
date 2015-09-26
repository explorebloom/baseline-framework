<?php
namespace Westco\Modules\Content\Nav;

use Framework\Contracts\ContentModule;

class BoldNav implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Bold Navigation',
			'slug'			=> 'bold_nav',
			'category'		=> 'navigation_template',
			'template_path'	=> '/Nav/Templates/',
		);
	}

}