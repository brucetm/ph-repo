<?php
namespace Votes\Entities\Vote;

use Votes\Config\SettingsRepository;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';

/**
* Sync all votes for a specific site
*/
class SyncAllVotes
{
	/**
	* Votes to Save
	* @var array
	*/
	private $votes;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Sync the votes
	*/
	public function sync($votes)
	{
		$this->votes = $votes;
		$saveType = $this->settings_repo->saveType();
		$this->$saveType();
		$this->updateUserMeta();
	}

	/**
	* Sync Session Votes
	*/
	private function session()
	{
		return $_SESSION['simplevotes'] = $this->votes;
	}

	/**
	* Sync a Cookie Vote
	*/
	public function cookie()
	{
		setcookie( 'simplevotes', json_encode( $this->votes ), time() + apply_filters( 'simplevotes_cookie_expiration_interval', 31556926 ), '/' );
		return;
	}

	/**
	* Update User Meta (logged in only)
	*/
	private function updateUserMeta()
	{
		if ( !is_user_logged_in() ) return false;
		return update_user_meta( intval(get_current_user_id()), 'simplevotes', $this->votes );
	}
}