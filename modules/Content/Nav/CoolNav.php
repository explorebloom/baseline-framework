<?php
namespace Westco\Modules\Content\Nav;

use Framework\Contracts\ContentModule;

class CoolNav implements ContentModule {

	public function configuration() {
		return array(
			'name'			=> 'Cool Navigation',
			'slug'			=> 'cool_nav',
			'category'		=> 'navigation_template',
			'template_path'	=> '/Nav/Templates/',
		);
	}

}