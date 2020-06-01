<?php
namespace Votes\Entities\User;

use Votes\Config\SettingsRepository;
use Votes\Helpers;
use Votes\Entities\Vote\VotesArrayFormatter;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Helpers.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VotesArrayFormatter.php';

class UserRepository
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Display button for current user
	* @return boolean
	*/
	public function getsButton()
	{
		if ( is_user_logged_in() ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return true;
		if ( $this->settings_repo->redirectAnonymous() ) return true;
		return false;
	}

	/**
	* Get All of current user's votes (includes all sites)
	* @return array (multidimensional)
	*/
	public function getAllVotes()
	{
		if ( is_user_logged_in() ) {
			$all_votes = $this->getLoggedInVotes();
		} else {
			$saveType = $this->settings_repo->saveType();
			$votes = ( $saveType == 'cookie' ) ? $this->getCookieVotes() : $this->getSessionVotes();
			$all_votes = $this->votesWithSiteID($votes);
		}

		/**
		 * Filter All of current user's votes.
		 *
		 * @since	1.3.0
		 * @param	array	The original current user's votes.
		 */
		$all_votes = apply_filters('votes/user/votes/all', $all_votes);

		return $all_votes;
	}

	/**
	* Get User's Votes by Site ID (includes a single site)
	* @return array (flat)
	*/
	public function getVotes($user_id = null, $site_id = null, $group_id = null)
	{
		if ( is_user_logged_in() && !$user_id ) $user_id = get_current_user_id();
		if ( is_user_logged_in() || $user_id ) {
			$votes = $this->getLoggedInVotes($user_id, $site_id, $group_id);
		} else {
			$saveType = $this->settings_repo->saveType();
			$votes = ( $saveType == 'cookie' )
				? $this->getCookieVotes($site_id, $group_id)
				: $this->getSessionVotes($site_id, $group_id);
		}

		/**
		 * Filter a User's Votes.
		 *
		 * @since	1.3.0
		 * @param	array	The original User's Votes.
		 */
		$votes = apply_filters('votes/user/votes', $votes);

		return $votes;
	}

	/**
	* Check for Site ID in user's votes
	* Multisite Compatibility for >1.1
	* 1.2 compatibility with new naming structure
	* @since 1.1
	*/
	private function votesWithSiteID($votes)
	{
		if ( Helpers::keyExists('site_votes', $votes) ){
			foreach($votes as $key => $site_votes){
				if ( !isset($votes[$key]['site_votes']) ) continue;
				$votes[$key]['posts'] = $votes[$key]['site_votes'];
				unset($votes[$key]['site_votes']);
				if ( isset($votes[$key]['total']) ) unset($votes[$key]['total']);
			}
		}
		if ( Helpers::keyExists('site_id', $votes) ) return $votes;
		$new_votes = [
			[
				'site_id' => 1,
				'posts' => $votes
			]
		];
		return $new_votes;
	}

	/**
	* Check for Groups array in user's votes
	* Add all votes to the default group if it doesn't exist
	* Compatibility for < 2.2
	* @since 2.2
	*/
	private function votesWithGroups($votes)
	{
		if ( Helpers::groupsExist($votes[0]) ) return $votes;
		$data = [
			'group_id' => 1,
			'site_id' => $votes[0]['site_id'],
			'group_name' => __('Default List', 'votes'),
			'posts' => $votes[0]['posts']
		];
		$votes[0]['groups'] = [
			$data
		];
		return $votes;
	}

	/**
	* Get Logged In User Votes
	*/
	private function getLoggedInVotes($user_id = null, $site_id = null, $group_id = null)
	{
		$user_id = ( is_null($user_id) ) ? get_current_user_id() : $user_id;
		$votes = get_user_meta($user_id, 'simplevotes');
		if ( empty($votes) ) return array(array('site_id'=> 1, 'posts' => [], 'groups' => [] ));

		$votes = $this->votesWithSiteID($votes[0]);
		$votes = $this->votesWithGroups($votes);

		if ( !is_null($site_id) && is_null($group_id) ) $votes = Helpers::pluckSiteVotes($site_id, $votes);
		if ( !is_null($group_id) ) $votes = Helpers::pluckGroupVotes($group_id, $site_id, $votes);

		return $votes;
	}

	/**
	* Get Session Votes
	*/
	private function getSessionVotes($site_id = null, $group_id = null)
	{
		if ( !isset($_SESSION['simplevotes']) ) $_SESSION['simplevotes'] = [];
		$votes = $_SESSION['simplevotes'];
		$votes = $this->votesWithSiteID($votes);
		$votes = $this->votesWithGroups($votes);
		if ( !is_null($site_id) && is_null($group_id) ) $votes = Helpers::pluckSiteVotes($site_id, $votes);
		if ( !is_null($group_id) ) $votes = Helpers::pluckGroupVotes($group_id, $site_id, $votes);
		return $votes;
	}

	/**
	* Get Cookie Votes
	*/
	private function getCookieVotes($site_id = null, $group_id = null)
	{
		if ( !isset($_COOKIE['simplevotes']) ) $_COOKIE['simplevotes'] = json_encode([]);
		$votes = json_decode(stripslashes($_COOKIE['simplevotes']), true);
		$votes = $this->votesWithSiteID($votes);
		$votes = $this->votesWithGroups($votes);
		if ( isset($_POST['user_consent_accepted']) && $_POST['user_consent_accepted'] == 'true' ) $votes[0]['consent_provided'] = time();
		if ( !is_null($site_id) && is_null($group_id) ) $votes = Helpers::pluckSiteVotes($site_id, $votes);
		if ( !is_null($group_id) ) $votes = Helpers::pluckGroupVotes($group_id, $site_id, $votes);
		return $votes;
	}

	/**
	* Has the user voted a specified post?
	* @param int $post_id
	* @param int $site_id
	* @param int $user_id
	* @param int $group_id
	*/
	public function isVote($post_id, $site_id = 1, $user_id = null, $group_id = null)
	{
		$votes = $this->getVotes($user_id, $site_id, $group_id);
		if ( in_array($post_id, $votes) ) return true;
		return false;
	}

	/**
	* Does the user count in total votes?
	* @return boolean
	*/
	public function countsInTotal()
	{
		if ( is_user_logged_in() ) return true;
		return $this->settings_repo->anonymous('save');
	}

	/**
	* Format an array of votes
	* @param $post_id - int, post to add to array (for session/cookie votes)
	* @param $site_id - int, site id for post_id
	*/
	public function formattedVotes($post_id = null, $site_id = null, $status = null)
	{
		$votes = $this->getAllVotes();
		$formatter = new VotesArrayFormatter;
		return $formatter->format($votes, $post_id, $site_id, $status);
	}

	/**
	* Has the user consented to cookies (if applicable)
	*/
	public function consentedToCookies()
	{
		if ( $this->settings_repo->saveType() !== 'cookie' ) return true;
		if ( isset($_POST['user_consent_accepted']) && $_POST['user_consent_accepted'] == 'true' ) return true;
		if ( !$this->settings_repo->consent('require') ) return true;
		if ( isset($_COOKIE['simplevotes']) ){
			$cookie = json_decode(stripslashes($_COOKIE['simplevotes']), true);
			if ( isset($cookie[0]['consent_provided']) ) return true;
			if ( isset($cookie[0]['consent_denied']) ) return false;
		}
		return false;
	}

	/**
	* Has the user denied consent to cookies explicitly
	*/
	public function deniedCookies()
	{
		if ( $this->settings_repo->saveType() !== 'cookie' ) return false;
		if ( !$this->settings_repo->consent('require') ) return false;
		if ( isset($_COOKIE['simplevotes']) ){
			$cookie = json_decode(stripslashes($_COOKIE['simplevotes']), true);
			if ( isset($cookie[0]['consent_denied']) ) return true;
		}
		return false;
	}
}