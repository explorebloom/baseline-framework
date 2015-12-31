<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\Callbacks\PageCallbacks;
use Baseline\Services\Settings\Traits\RegistersChildren;

class MakesPages {

	use RegistersChildren;

	/**
	 * Holds our setting prefix from the framework config file.
	 */
	protected $setting_prefix;

	/**
	 * Holds the page's id for the action to reference.
	 */
	protected $page_id;

	/**
	 * Holds all of the page's settings for the action to reference.
	 */
	protected $page_options;

	/**
	 * Holds an instance of the page's callback class.
	 */
	protected $page_callback;

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
	public function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');
	}

	/**
	 * Makes the page using the Wordpress Settings API
	 */
	public function make($id, $options)
	{
		// Tack on the prefix and set the page's id.
		$this->page_id = $this->setting_prefix . $id;

		// Bring out all of the variable.
		extract($options);

		// Set defaults if needed.
		$options['tab_style'] = $tab_style ? $tab_style : 'independent';
		$options['subtab_style'] = $subtab_style ? $subtab_style : 'independent';
		$options['capability'] = $capability ? $capability : 'manage_options';
		$options['icon_url'] = $icon_url ? $icon_url : '';
		$options['position'] = $position ? $position : null;

		// Set the options to the class property
		$this->page_options = $options;

		// Set up the page's callback class.
		$this->page_callback = new PageCallbacks;
		$this->page_callback->setProperties(array(
			'id' 				=> $this->page_id,
			'page_title' 		=> $this->page_options['page_title'],
			'menu_title' 		=> $this->page_options['menu_title'],
			'tab_style'			=> $this->page_options['tab_style'],
			'subtab_style'		=> $this->page_options['subtab_style'],
		));

		// Register with wordpress
		$this->registerPage();
		// add_action('admin_menu', array($this, 'registerPage'));

		// Makes it's contents
		$this->makeChildren(
			$this->page_options['contents'], 
			$this->page_callback
		);
	}

	/**
	 * This is the method called by wordpress at the admin_menu action hook to register the page.
	 */
	public function registerPage()
	{
		add_menu_page(
			$this->page_options['page_title'],
			$this->page_options['menu_title'],
			$this->page_options['capability'],
			$this->page_id,
			array($this->page_callback, 'callback'),
			$this->page_options['icon_url'],
			$this->page_options['position']
		);
	}

}