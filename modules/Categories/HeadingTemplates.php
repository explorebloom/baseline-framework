<?php
namespace Westco\Modules\Categories;

use Framework\Contracts\ContentModuleCategory;

class HeadingTemplates implements ContentModuleCategory {

	public function configuration() {
		return array(
			'name'		=> 'Heading Templates',
			'slug'		=> 'heading_template',
			'default'	=> 'default_header',
		);
	}

}