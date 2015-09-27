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
	public static function getInstance($param = null) {
	    if (!self::$instance) {
	    	if ($param) {
	        	self::$instance = new self($param);
	    	} else {
	    		self::$instance = new self;
	    	}
	    }
	    return self::$instance;
	}

}