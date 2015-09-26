<?php
namespace Baseline\Helper;

trait IsSingleton {
	
	/*
	 * Static property to hold singelton instance
	 */
	protected static $instance = false;

	/**
	 * Returns a singelton instance of the class
	 *
	 * @return Westco_Support
	 */
	public static function getInstance() {
	    if (!self::$instance) {
	        self::$instance = new self;
	    }
	    return self::$instance;
	}

}