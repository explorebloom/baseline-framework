<?php
namespace Baseline\Helper;

/*
|--------------------------------------------------------------------------
| Helper trait For Querying Modules
|--------------------------------------------------------------------------
|
| This trait adds the ability for a user to rather than calling getSetModule()
| and passing in the category slug they can just call getFooBar() and it
| will return getSetModule('foo-bar'). Below are all the requirenments.
|
|--------------------------------------------------------------------------
|
| 1. Function must begin with get, so getFooBar() will work, and likewise
|	 calling iReallyWantFooBar() will not work.
|
| 2. Must be a 'PascalCase' version of the category slug. So if the slug is 
|    'cool-category' the function call would be getCoolCategory();
|
| 3. Must not have any arguments passed to it. Will return false if that
|	 happens. So getFooBar('pointless-argument') will return false.
|
| 4. Calling a category that doesn't exist will still work. But the method
|	 it ends up calling is getSetModule('doesnt-exist'), which checks if
|	 a category exists and will simply return false if it doesnt.
|
*/

trait HelperModuleQueries {
	
	/**
	 * Magic method that will make a function for any method call to the class under the right conditions.
	 */
	public function __call($name, $args)
	{
		// Turns 'getFooBar' into 'get-foo-bar'.
		$hypenated = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1_', $name));
		$name_array = explode('_', $hypenated);

		// Does it begin with get?
		// Was the function just get()?
		// Did the user pass pointless arguments?
		if ( $name_array[0] === 'get' && count($name_array) > 1 && count($args) == 0) {
			// remove get from the name_array.
			unset($name_array[0]);

			// Make the category slug from the leftovers.
			$slug = implode('_', $name_array);
			// Get the set module for the given category.
			return $this->getModuleFor($slug);
		}

		// Not a valid function call.
		return false;
	}

}
