<?php
namespace Votes\Entities\Post;

use Votes\Entities\Post\VoteCount;
use Votes\Entities\User\UserRepository;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/VoteCount.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserRepository.php';

/**
* Updates the vote count for a given post
*/
class SyncVoteCount
{
	/**
	* Post ID
	* @var int
	*/
	private $post_id;

	/**
	* Site ID
	* @var int
	*/
	private $site_id;

	/**
	* Status
	* @var string
	*/
	private $status;

	/**
	* Vote Count
	* @var object
	*/
	private $vote_count;

	/**
	* User Repository
	*/
	private $user;

	public function __construct($post_id, $status, $site_id)
	{
		$this->post_id = $post_id;
		$this->status = $status;
		$this->site_id = $site_id;
		$this->vote_count = new VoteCount;
		$this->user = new UserRepository;
	}

	/**
	* Sync the Post Total Votes
	*/
	public function sync()
	{
		if ( !$this->user->countsInTotal() ) return false;
		$count = $this->vote_count->getCount($this->post_id, $this->site_id);
		$count = ( $this->status == 'active' ) ? $count + 1 : max(0, $count - 1);
		return update_post_meta($this->post_id, 'simplevotes_count', $count);
	}
}