<?php
namespace Votes\Listeners;

use Votes\Entities\Post\VoteCount as VoteCounter;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/AJAXListenerBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/VoteCount.php';

/**
* Return the total number of votes for a specified post
*/
class VoteCount extends AJAXListenerBase
{
	/**
	* Vote Counter
	*/
	private $vote_counter;

	public function __construct()
	{
		parent::__construct();
		$this->vote_counter = new VoteCounter;
		$this->setData();
		$this->sendCount();
	}

	private function setData()
	{
		$this->data['postid'] = ( isset($_POST['postid']) ) ? intval($_POST['postid']) : null;
		$this->data['siteid'] = ( isset($_POST['siteid']) ) ? intval($_POST['siteid']) : null;
	}

	private function sendCount()
	{
		$this->response([
			'status' => 'success',
			'count' => $this->vote_counter->getCount($this->data['postid'], $this->data['siteid'])
		]);
	}
}