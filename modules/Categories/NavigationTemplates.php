<?php
namespace Westco\Modules\Categories;

use Framework\Contracts\ContentModuleCategory;

class NavigationTemplates implements ContentModuleCategory {

	public function configuration() {
		return array(
			'name'	=> 'Navigation Templates',
			'slug'	=> 'navigation_template'
		);
	}

}