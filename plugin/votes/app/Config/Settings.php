<?php
namespace Votes\Config;

use Votes\Config\SettingsRepository;
use Votes\Entities\PostType\PostTypeRepository;
use Votes\Helpers;

/**
* Plugin Settings
*/
class Settings
{
	/**
	* Plugin Name
	*/
	private $plugin_name;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Post Type Repository
	*/
	private $post_type_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->post_type_repo = new PostTypeRepository;
		$this->setName();
		add_action( 'admin_init', [$this, 'registerSettings']);
		add_action( 'admin_menu', [$this, 'registerSettingsPage']);
	}

	/**
	* Set the plugin name
	*/
	private function setName()
	{
		global $votes_name;
		$this->plugin_name = $votes_name;
	}

	/**
	* Register the settings page
	*/
	public function registerSettingsPage()
	{
		add_options_page(
			$this->plugin_name . ' ' . __('Settings', 'votes'),
			$this->plugin_name,
			'manage_options',
			'simple-votes',
			[$this, 'settingsPage']
		);
	}

	/**
	* Display the Settings Page
	*/
	public function settingsPage()
	{
		$tab = ( isset($_GET['tab']) ) ? sanitize_text_field($_GET['tab']) : 'general';
		include( Helpers::view('settings/settings') );
	}

	/**
	* Register the settings
	*/
	public function registerSettings()
	{
		register_setting( 'simple-votes-general', 'simplevotes_dependencies' );
		register_setting( 'simple-votes-general', 'simplevotes_cache_enabled' );
		register_setting( 'simple-votes-general', 'simplevotes_dev_mode');
		register_setting( 'simple-votes-users', 'simplevotes_users' );
		register_setting( 'simple-votes-display', 'simplevotes_display' );
	}
}