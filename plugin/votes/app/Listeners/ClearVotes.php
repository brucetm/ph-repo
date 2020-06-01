<?php
namespace Votes\Listeners;

use Votes\Entities\Vote\Vote;
use Votes\Entities\Vote\SyncAllVotes;
use Votes\Entities\Post\SyncVoteCount;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/Vote.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/SyncAllVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/SyncVoteCount.php';


class ClearVotes extends AJAXListenerBase
{
	/**
	* Votes Sync
	*/
	private $votes_sync;

	public function __construct()
	{
		parent::__construct();
		$this->votes_sync = new SyncAllVotes;
		$this->setFormData();
		$this->clearVotes();
		$this->sendResponse();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
		$this->data['old_votes'] = $this->user_repo->formattedVotes();
	}

	/**
	* Remove all user's votes from the specified site
	*/
	private function clearVotes()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('votes_before_clear', $this->data['siteid'], $user);
		$votes = $this->user_repo->getAllVotes();
		foreach($votes as $key => $site_votes){
			if ( $site_votes['site_id'] == $this->data['siteid'] ) {
				$this->updateVoteCounts($site_votes);
				unset($votes[$key]);
			}
		}
		$this->votes_sync->sync($votes);

		do_action('votes_after_clear', $this->data['siteid'], $user);
	}

	/**
	* Update all the cleared post vote counts
	*/
	private function updateVoteCounts($site_votes)
	{
		foreach($site_votes['posts'] as $vote){
			$count_sync = new SyncVoteCount($vote, 'inactive', $this->data['siteid']);
			$count_sync->sync();
		}
	}

	/**
	* Set and send the response
	*/
	private function sendResponse()
	{
		$votes = $this->user_repo->formattedVotes();
		$this->response([
			'status' => 'success',
			'old_votes' => $this->data['old_votes'],
			'votes' => $votes
		]);
	}
}