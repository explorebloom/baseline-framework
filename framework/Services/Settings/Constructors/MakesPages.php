<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\Callbacks\PageCallbacks;
use Baseline\Services\Settings\Traits\RegistersChildren;

class MakesPages {

	use IsSingleton, RegistersChildren;

	/**
	 * Holds our setting prefix from the framework config file.
	 */
	protected $setting_prefix;

	/**
	 * An array of all of the allowed child types.
	 */
	protected $allowed_child_types = array(
		'subpage',
		'tab',
		'subtab',
		'section',
	);

	/**
	 * Constructs the class and sets its properties.
	 */
	private function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');
	}

	/**
	 * Makes the page using the Wordpress Settings API
	 */
	public function make($id, $options)
	{
		// Tack on the prefix.
		$id = $this->setting_prefix . $id;

		// Bring out all of the variable.
		extract($options);

		// Set defaults if needed.
		$tab_style = $tab_style ? $tab_style : 'independent';
		$subtab_style = $subtab_style ? $subtab_style : 'independent';
		$capability = $capability ? $capability : 'manage_options';
		$icon_url = $icon_url ? $icon_url : '';
		$position = $position ? $position : null;

		// Set up the page's callback class.
		$page_callback = new PageCallbacks;
		$page_callback->setProperties(array(
			'id' 				=> $id,
			'page_title' 		=> $page_title,
			'menu_title' 		=> $menu_title,
			'tab_style'			=> $tab_style,
			'subtab_style'		=> $subtab_style,
		));

		// Register with wordpress
		add_menu_page(
			$page_title,
			$menu_title,
			$capability,
			$id,
			array($page_callback, 'callback'),
			$icon_url,
			$position
		);

		// Makes it's contents
		$this->makeChildren($contents, $page_callback);
	}

}