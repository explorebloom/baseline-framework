<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Traits\RegistersChildren;
use Baseline\Services\Settings\Callbacks\SubpageCallbacks;

class MakesSubpages {

	use IsSingleton, RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * An array of all of the allowed child types.
	 */
	protected $allowed_child_types = array(
		'tab',
		'subtab',
		'section',
	);

	/**
	 * Constructs the class and sets all of the properties.
	 */
	private function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');

		// Set the validation class.
		$this->validator = ValidatesSettingOptions::getInstance();

		// Set the constructor classes.
		$this->tab = MakesTabs::getInstance();
		$this->subtab = MakesSubtabs::getInstance();
		$this->section = MakesSections::getInstance();
	}

	/**
	 * Makes a subpage using Wordpress's Settings Api.
	 */
	public function make($id, $options, $parent, $initial)
	{
		// Tack on the prefix.
		if ($initial) {
			$id = $parent->options['id'];
		} else {
			$id = $this->setting_prefix . $id;
		}

		// Bring out the variables.
		extract($options);

		// Is subtab style set?
		if (!$subtab_style) {
			$subtab_style = !is_null($parent) ? $parent->options['subtab_style'] : 'independent';
		} 
		$capability = $capability ? $capability : 'manage_options';

		// If there is no parent set then put the settings in wordpress's default settings page.
		$parent_id = is_null($parent) ? 'options-general.php' : $parent->options['id'];

		// Set up the subpage callback class.
		$subpage_callback = new SubpageCallbacks;
		$subpage_callback->setProperties(array(
			'id'			=> $id,
			'page_title'	=> $page_title,
			'menu_title'	=> $menu_title,
			'parent'		=> $parent,
			'tab_style'		=> $parent->options['tab_style'],
			'subtab_style'	=> $subtab_style,
		));

		// Register the Tab with it's parent.
		if (!is_null($parent)) {
			$parent->registerChild('subpage', $id, $menu_title);
		}

		// A
		// At the sub page through the wordpress Settings API.
		add_submenu_page(
			$parent_id,
			$page_title,
			$menu_title,
			$capability,
			$id,
			array($subpage_callback, 'callback')
		);

		// Makes its contents.
		$this->makeChildren($contents, $subpage_callback);
	}

	/**
	 * Registers the page with the Wordpress Settings Api.
	 */
	public function registerWithApi($parent_id, $page_title, $menu_title, $capability, $id, $subpage_callback)
	{
		// Register with wordpress
		add_submenu_page(
			$page_title,
			$menu_title,
			$capability,
			$id,
			array($page_callback, 'callback'),
			$icon_url,
			$position
		);
	}

}