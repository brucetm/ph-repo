<?php
namespace Votes\Entities\Vote;

use Votes\Entities\User\UserRepository;
use Votes\Helpers;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Helpers.php';

/**
* Sync a single vote to a given save type
*/
class SyncSingleVote
{
	/**
	* The Post ID
	*/
	private $post_id;

	/**
	* The Site ID
	*/
	private $site_id;

	/**
	* The Group ID
	*/
	private $group_id;

	/**
	* User Repository
	*/
	private $user;

	public function __construct($post_id, $site_id, $group_id = 1)
	{
		$this->user = new UserRepository;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
		$this->group_id = $group_id;
	}

	/**
	* Sync a Session Vote
	*/
	public function session()
	{
		if ( $this->user->isVote($this->post_id, $this->site_id) ) return $_SESSION['simplevotes'] = $this->removeVote();
		return $_SESSION['simplevotes'] = $this->addVote();
	}

	/**
	* Sync a Cookie Vote
	*/
	public function cookie()
	{
		if ( $this->user->isVote($this->post_id, $this->site_id) ){
			$votes = $this->removeVote();
			setcookie( 'simplevotes', json_encode( $votes ), time() + apply_filters( 'simplevotes_cookie_expiration_interval', 31556926 ), '/' );
			return;
		}
		$votes = $this->addVote();
		setcookie( 'simplevotes', json_encode( $votes ), time() + apply_filters( 'simplevotes_cookie_expiration_interval', 31556926 ), '/' );
		return;
	}

	/**
	* Update User Meta (logged in only)
	*/
	public function updateUserMeta($votes)
	{
		if ( !is_user_logged_in() ) return;
		update_user_meta( intval(get_current_user_id()), 'simplevotes', $votes );
	}

	/**
	* Remove a Vote
	*/
	private function removeVote()
	{
		$votes = $this->user->getAllVotes($this->site_id);

		foreach($votes as $key => $site_votes){
			if ( $site_votes['site_id'] !== $this->site_id ) continue;
			foreach($site_votes['posts'] as $k => $fav){
				if ( $fav == $this->post_id ) unset($votes[$key]['posts'][$k]);
			}
			if ( !Helpers::groupsExist($site_votes) ) return;
			foreach( $site_votes['groups'] as $group_key => $group){
				if ( $group['group_id'] !== $this->group_id ) continue;
				foreach ( $group['posts'] as $k => $g_post_id ){
					if ( $g_post_id == $this->post_id ) unset($votes[$key]['groups'][$group_key]['posts'][$k]);
				}
			}
		}
		$this->updateUserMeta($votes);
		return $votes;
	}

	/**
	* Add a Vote
	*/
	private function addVote()
	{
		$votes = $this->user->getAllVotes($this->site_id);
		if ( !Helpers::siteExists($this->site_id, $votes) ){
			$votes[] = [
				'site_id' => $this->site_id,
				'posts' => []
			];
		}
		// Loop through each site's votes, continue if not the correct site id
		foreach($votes as $key => $site_votes){
			if ( $site_votes['site_id'] !== $this->site_id ) continue;
			$votes[$key]['posts'][] = $this->post_id;

			// Add the default group if it doesn't exist yet
			if ( !Helpers::groupsExist($site_votes) ){
				$votes[$key]['groups'] = [
					[
						'group_id' => 1,
						'site_id' => $this->site_id,
						'group_name' => __('Default List', 'votes'),
						'posts' => array()
					]
				];
			}
			foreach( $votes[$key]['groups'] as $group_key => $group){
				if ( $group['group_id'] == $this->group_id )
					$votes[$key]['groups'][$group_key]['posts'][] = $this->post_id;
			}
		}
		$this->updateUserMeta($votes);
		return $votes;
	}
}