<?php
namespace Votes;

use Votes\Config\SettingsRepository;
require_once("Config/SettingsRepository.php");
require_once("Helpers.php");
require_once("Config/Settings.php");
require_once("Entities/PostType/PostTypeRepository.php");
require_once("Activation/Activate.php");
require_once("Activation/Dependencies.php");
require_once("Entities/Post/PostHooks.php");
require_once("Events/RegisterPublicEvents.php");
require_once("Entities/Post/PostMeta.php");
require_once("API/Shortcodes/ButtonShortcode.php");	
require_once("API/Shortcodes/UserVotesShortcode.php");			
require_once("API/Shortcodes/UserVoteCount.php");			
require_once("API/Shortcodes/PostVotesShortcode.php");			
require_once("API/Shortcodes/ClearVotesShortcode.php");		
require_once("API/Shortcodes/VoteCountShortcode.php");	
require_once("Entities/Post/AdminColumns.php");
/**
* Plugin Bootstrap
*/
class Bootstrap
{
	/**
	* Settings Repository
	* @var object
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_action( 'init', [$this, 'init']);
		add_action( 'admin_init', [$this, 'adminInit']);
		add_filter( 'plugin_action_links_' . 'votes/votes.php', [$this, 'settingsLink']);
		add_action( 'plugins_loaded', [$this, 'addLocalization']);
	}

	/**
	* Initialize
	*/
	public function init()
	{
		new Config\Settings;
		new Activation\Activate;
		new Activation\Dependencies;
		new Entities\Post\PostHooks;
		new Events\RegisterPublicEvents;
		new Entities\Post\PostMeta;
		new API\Shortcodes\ButtonShortcode;
		new API\Shortcodes\VoteCountShortcode;
		new API\Shortcodes\UserVotesShortcode;
		new API\Shortcodes\UserVoteCount;
		new API\Shortcodes\PostVotesShortcode;
		new API\Shortcodes\ClearVotesShortcode;
		$this->startSession();
	}

	/**
	* Admin Init
	*/
	public function adminInit()
	{
		new Entities\Post\AdminColumns;
	}

	/**
	* Add a link to the settings on the plugin page
	*/
	public function settingsLink($links)
	{
		$settings_link = '<a href="options-general.php?page=simple-votes">' . __('Settings', 'votes') . '</a>';
		$help_link = '<a href="http://voteposts.com">' . __('FAQ', 'votes') . '</a>';
		array_unshift($links, $help_link);
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	* Localization Domain
	*/
	public function addLocalization()
	{
		load_plugin_textdomain(
			'votes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages' );
	}

	/**
	* Initialize a Session
	*/
	public function startSession()
	{
		if ( $this->settings_repo->saveType() !== 'session' ) return;
		if ( !session_id() ) session_start();
	}
}