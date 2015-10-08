<?php
namespace Baseline\Helper;

/*
|--------------------------------------------------------------------------
| Current Page Parser Class
|--------------------------------------------------------------------------
|
| This is a small class that determines what the current page is using the
| different wordpress functions. It is used by classes to decide by what
| prefix different page specific queries and settings should be using.
|
*/

class GetsCurrentPage {


	public function prefix()
	{
		// Is it the home page?
		if (is_home()) {

			$prefix = get_option('page_for_posts');

		} else if(is_404()) {
		
			$prefix = 'is404';
		
		} else {

			$prefix = get_the_ID();

		}
		return $prefix . '_';
	}
}