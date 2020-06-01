<?php
namespace Votes\Listeners;

use Votes\Entities\Vote\Vote;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/AJAXListenerBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/Vote.php';

class VoteButton extends AJAXListenerBase
{
	public function __construct()
	{
		parent::__construct();
		$this->setFormData();
		$this->updateVote();
	}

	/**
	* Set Form Data
	*/
	private function setFormData()
	{
		$this->data['postid'] = intval(sanitize_text_field($_POST['postid']));
		$this->data['siteid'] = intval(sanitize_text_field($_POST['siteid']));
		$this->data['status'] = ( $_POST['status'] == 'active') ? 'active' : 'inactive';
		$this->data['groupid'] = ( isset($_POST['groupid']) && $_POST['groupid'] !== '' ) ? intval($_POST['groupid']) : 1;
	}

	/**
	* Update the Vote
	*/
	private function updateVote()
	{
		try {
			$this->beforeUpdateAction();
			if ( !$this->validates() ) throw new \Exception(__('Invalid post.', 'votes'));
			$vote = new Vote;
			$vote->update($this->data['postid'], $this->data['status'], $this->data['siteid'], $this->data['groupid']);
			$this->afterUpdateAction();
			$this->response([
				'status' => 'success',
				'vote_data' => [
					'id' => $this->data['postid'],
					'siteid' => $this->data['siteid'],
					'status' => $this->data['status'],
					'groupid' => $this->data['groupid'],
					'save_type' => $vote->saveType()
				],
				'votes' => $this->user_repo->formattedVotes($this->data['postid'], $this->data['siteid'], $this->data['status'])
			]);
		} catch ( \Exception $e ){
			return $this->sendError($e->getMessage());
		}
	}

	/**
	* Before Update Action
	* Provides hook for performing actions before a vote
	*/
	private function beforeUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('votes_before_vote', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

	/**
	* After Update Action
	* Provides hook for performing actions after a vote
	*/
	private function afterUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('votes_after_vote', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

	/**
	* Validate the Vote
	*/
	private function validates()
	{
		$post_type = get_post_type($this->data['postid']);
		$enabled = $this->settings_repo->displayInPostType($post_type);
		$post_type_object = get_post_type_object(get_post_type($this->data['postid']));
		if ( !$post_type_object ) return false;
		if ( !$post_type_object->public || !$enabled ) return false;
		return true;
	}
}