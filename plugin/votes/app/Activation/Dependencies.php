<?php
namespace Votes\Activation;

use Votes\Helpers;
use Votes\Config\SettingsRepository;

/**
* Plugin Dependencies
*/
class Dependencies
{
	/**
	* Plugin Directory
	*/
	private $plugin_dir;

	/**
	* Plugin Version
	*/
	private $plugin_version;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->setPluginVersion();
		$this->plugin_dir = Helpers::plugin_url();
		add_action( 'admin_enqueue_scripts', [$this, 'adminStyles']);
		add_action( 'admin_enqueue_scripts', [$this, 'adminScripts']);
		add_action( 'wp_enqueue_scripts', [$this, 'frontendStyles']);
		add_action( 'wp_enqueue_scripts', [$this, 'frontendScripts']);
	}

	/**
	* Set the Plugin Version
	*/
	private function setPluginVersion()
	{
		global $votes_version;
		$this->plugin_version = $votes_version;
	}

	/**
	* Admin Styles
	*/
	public function adminStyles()
	{
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style(
			'simple-votes-admin',
			$this->plugin_dir . '/assets/css/votes-admin.css',
			[],
			$this->plugin_version
		);
	}

	/**
	* Admin Scripts
	*/
	public function adminScripts()
	{
		$screen = get_current_screen();
		$settings_page = ( strpos($screen->id, 'simple-votes') ) ? true : false;
		if ( !$settings_page ) return;
		wp_enqueue_script(
			'simple-votes-admin',
			$this->plugin_dir . '/assets/js/votes-admin.min.js',
			['jquery', 'wp-color-picker'],
			$this->plugin_version
		);
	}

	/**
	* Front End Styles
	*/
	public function frontendStyles()
	{
		if ( !$this->settings_repo->outputDependency('css') ) return;
		wp_enqueue_style(
			'simple-votes',
			$this->plugin_dir . '/assets/css/votes.css',
			[],
			$this->plugin_version
		);
	}

	/**
	* Front End Scripts
	*/
	public function frontendScripts()
	{
		if ( !$this->settings_repo->outputDependency('js') ) return;
		$file = ( $this->settings_repo->devMode() ) ? 'votes.js' : 'votes.min.js';
		wp_enqueue_script(
			'votes',
			$this->plugin_dir . '/assets/js/' . $file,
			['jquery'],
			$this->plugin_version
		);
		$localized_data = [
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('simple_votes_nonce'),
			'vote' => apply_filters('votes/button/html', $this->settings_repo->buttonText(), null, false, null),
			'voted' => apply_filters('votes/button/html', $this->settings_repo->buttonTextVoted(), null, true, null),
			'includecount' => $this->settings_repo->includeCountInButton(),
			'indicate_loading' => $this->settings_repo->includeLoadingIndicator(),
			'loading_text' => $this->settings_repo->loadingText(),
			'loading_image' => $this->settings_repo->loadingImage(),
			'loading_image_active' => $this->settings_repo->loadingImage('active'),
			'loading_image_preload' => $this->settings_repo->includeLoadingIndicatorPreload(),
			'cache_enabled' => $this->settings_repo->cacheEnabled(),
			'button_options' => $this->settings_repo->formattedButtonOptions(),
			'authentication_modal_content' => $this->settings_repo->authenticationModalContent(),
			'authentication_redirect' => $this->settings_repo->redirectAnonymous(),
			'dev_mode' => $this->settings_repo->devMode(),
			'logged_in' => is_user_logged_in(),
			'user_id' => get_current_user_id()
		];
		$redirect_url = $this->settings_repo->redirectAnonymousId();
		$localized_data['authentication_redirect_url'] = ( $redirect_url ) ? get_the_permalink($redirect_url) : apply_filters( 'votes/authentication_redirect_url', wp_login_url() );
		wp_localize_script(
			'votes',
			'votes_data',
			$localized_data
		);
	}
}