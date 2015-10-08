<?php
namespace Baseline\Services;

class SettingFields {

	/**
	 * Holds the setting prefix that is defined in the config files.
	 */
	protected $setting_prefix;

	public function register($object, $storage_type, $parent = null, $setting_prefix = null)
	{
		if ($setting_prefix) {
			$this->setting_prefix = $setting_prefix;
		}

		

	}

}