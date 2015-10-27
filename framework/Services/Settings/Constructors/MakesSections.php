<?php
namespace Baseline\Services\Settings\Constructors;

use Baseline\Core\Config;
use Baseline\Registrars\SettingsRegistrar;
use Baseline\Services\Settings\Traits\RegistersChildren;
use Baseline\Services\Settings\Callbacks\SectionCallbacks;

class MakesSections {

	use RegistersChildren;

	/**
	 * Holds the setting prefix defined in the framework config.
	 */
	protected $setting_prefix;

	/**
	 * Holds the sections id for reference by the wordpress action hook.
	 */
	protected $section_id;

	/**
	 * Holds the sections options for reference by the wordpress action hook.
	 */
	protected $section_options;

	/**
	 * Holds the sections callback class.
	 */
	protected $section_callback;

	/**
	 * An array of all of the allowed child types.
	 */
	protected $allowed_child_types = array(
		'setting'
	);

	/**
	 * Constructs the class and sets all of the properties.
	 */
	public function __construct()
	{
		// Set the prefix.
		$this->setting_prefix = Config::getInstance()->getFrameworkConfig('setting_prefix');
	}

	public function make($id, $options, $parent)
	{
		// Tack on the prefix.
		$this->section_id = $this->setting_prefix . $id;

		// Bring out the options.
		extract($options);

		// Is the parent set?
		if (!is_null($parent)) {
		
			// If subtab style is independent, then the section will just be grouped in the parent id.
			// If subtabs should be build off of sections, then the group will be the section id.
			$options['parent_id'] = $parent->options['subtab_style'] == 'independent' ? $parent->options['id'] : $id;

		// If there is no parent set then just put the sections in the general settings.
		} else {
			$options['parent_id'] = 'general';
		}

		$this->section_options = $options;

		// Set up the callback class.
		$this->section_callback = new SectionCallbacks;
		$this->section_callback->setProperties(array(
			'id'			=> $this->section_id,
			'title'			=> $this->section_options['title'],
			'description' 	=> $this->section_options['description'],
			'group'			=> $this->section_options['parent_id']
		));

		if ($parent) {
			$parent->registerChild('section', $id, $options);
		}

		// Create the Section with the Wordpress Settings Api.
		add_action('admin_init', array($this, 'addSection'));

		// Register it later when all of it's children are registered.
		add_action('admin_init', array($this, 'registerSection'), 11);
		
		// Register the setting section with the setting registrar.
		SettingsRegistrar::getInstance()->registerSettingSection($this, $this->section_id, $this->section_options);
		
		// Create the children
		$this->makeChildren($contents, $this->section_callback);
	}

	/**
	 * Adds the setting section to the wordpress Api.
	 */
	public function addSection()
	{
		// Create the setting section with the WordPress Settings Api
		add_settings_section(
			$this->section_id,
			$this->section_options['title'],
			array($this->section_callback, 'callback'),
			$this->section_options['parent_id']
		);
	}

	/**
	 * Registers this section and all settings under it with the wordpress settings api. 
	 */
	public function registerSection()
	{
		// Register everything.
		register_setting(
			$this->section_options['parent_id'],
			$this->section_id
		);
	}

}