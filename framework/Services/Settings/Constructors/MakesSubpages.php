<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Helper\IsSingleton;
use Baseline\Services\Settings\ValidatesSettingOptions;
use Baseline\Services\Settings\Traits\RegistersChildren;
use Baseline\Services\Settings\Callbacks\SubpageCallbacks;

class MakesSubpages {

	use RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * Holds the subpage id for reference by the wordpress action.
	 */
	protected $subpage_id;

	/**
	 * Holds all of teh subpage options for reference by the wordpress action.
	 */
	protected $subpage_options;

	/**
	 * Holds the subpage callback for reference by the wordpress action.
	 */
	protected $subpage_callback;

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
	public function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');

		// Set the validation class.
		$this->validator = ValidatesSettingOptions::getInstance();

	}

	/**
	 * Makes a subpage using Wordpress's Settings Api.
	 */
	public function make($id, $options, $parent, $initial)
	{
		// Tack on the prefix.
		if ($initial) {
			$this->subpage_id = $parent->options['id'];
		} else {
			$this->subpage_id = $this->setting_prefix . $id;
		}

		// Bring out the variables.
		extract($options);

		// Is subtab style set?
		if (!$subtab_style) {
			$options['subtab_style'] = !is_null($parent) ? $parent->options['subtab_style'] : 'independent';
		} 
		$options['capability'] = $capability ? $capability : 'manage_options';

		// If there is no parent set then put the settings in wordpress's default settings page.
		$options['parent_id'] = is_null($parent) ? 'options-general.php' : $parent->options['id'];

		// Set the options to the property.
		$this->subpage_options = $options;

		// Set up the subpage callback class.
		$this->subpage_callback = new SubpageCallbacks;
		$this->subpage_callback->setProperties(array(
			'id'			=> $this->subpage_id,
			'page_title'	=> $this->subpage_options['page_title'],
			'menu_title'	=> $this->subpage_options['menu_title'],
			'parent'		=> $parent,
			'tab_style'		=> $parent->options['tab_style'],
			'subtab_style'	=> $this->subpage_options['subtab_style'],
		));

		// Register the subpage with it's parent if it has one.
		if (!is_null($parent)) {
			$parent->registerChild('subpage', $this->subpage_id, $this->subpage_options['menu_title']);
		}

		// Register the page with wordpress.
		add_action('admin_menu', array($this, 'registerSubpage'));

		// Makes its contents.
		$this->makeChildren($contents, $this->subpage_callback);
	}

	/**
	 * Registers the page with the Wordpress Settings Api.
	 */
	public function registerSubpage()
	{
		// At the sub page through the wordpress Settings API.
		add_submenu_page(
			$this->subpage_options['parent_id'],
			$this->subpage_options['page_title'],
			$this->subpage_options['menu_title'],
			$this->subpage_options['capability'],
			$this->subpage_id,
			array($this->subpage_callback, 'callback')
		);
	}

}