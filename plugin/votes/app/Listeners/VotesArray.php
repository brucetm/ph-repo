<?php
namespace Votes\Listeners;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/AJAXListenerBase.php';
/**
* Return an array of user's voted posts
*/
class VotesArray extends AJAXListenerBase
{
	/**
	* User Votes
	* @var array
	*/
	private $votes;

	public function __construct()
	{
		parent::__construct(false);
		$this->setVotes();
		$this->response(['status'=>'success', 'votes' => $this->votes]);
	}

	/**
	* Get the Votes
	*/
	private function setVotes()
	{
		$votes = $this->user_repo->formattedVotes();
		$this->votes = $votes;
	}
}