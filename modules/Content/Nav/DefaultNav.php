<?php
namespace Westco\Modules\Content\Nav;

use Framework\Contracts\ContentModule;

class DefaultNav implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Default Navigation',
			'slug'			=> 'default_nav',
			'category'		=> 'navigation_template',
			'template_path'	=> '/Nav/Templates/'
		);
	}

}