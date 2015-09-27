<?php
namespace Baseline\Core;

use Baseline\Helper\IsSingleton;
/*
|--------------------------------------------------------------------------
| Baseline Main Registrar Class
|--------------------------------------------------------------------------
|
| This is the main class for registration settings. It is responsible for
| calling all sub registrar classes to make sure everything is properly 
| registered. It also makes actions for registering from a child them.
|
*/


class Registrar {
	
	use IsSingleton;

	private function __construct()

}