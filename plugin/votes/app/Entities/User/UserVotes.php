<?php
namespace Votes\Entities\User;

use Votes\Entities\User\UserRepository;
use Votes\Entities\Vote\VoteFilter;
use Votes\Entities\VoteList\VoteList;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VoteFilter.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteList.php';

class UserVotes
{
	/**
	* User ID
	* @var int
	*/
	private $user_id;

	/**
	* Site ID
	* @var int
	*/
	private $site_id;

	/**
	* Display Links
	* @var boolean
	*/
	private $links;

	/**
	* Filters
	* @var array
	*/
	private $filters;

	/**
	* User Repository
	*/
	private $user_repo;

	public function __construct($user_id = null, $site_id = null, $links = false, $filters = null)
	{
		$this->user_id = $user_id;
		$this->site_id = $site_id;
		$this->links = $links;
		$this->filters = $filters;
		$this->user_repo = new UserRepository;
	}

	/**
	* Get an array of votes for specified user
	*/
	public function getVotesArray($user_id = null, $site_id = null, $filters = null)
	{
		$user_id = ( isset($user_id) ) ? $user_id : $this->user_id;
		$site_id = ( isset($site_id) ) ? $site_id : $this->site_id;
		$votes = $this->user_repo->getVotes($user_id, $site_id);
		if ( isset($filters) ) $this->filters = $filters;
		if ( isset($this->filters) && is_array($this->filters) ) $votes = $this->filterVotes($votes);
		return $this->removeInvalidVotes($votes);
	}

	/**
	* Remove non-existent or non-published votes
	* @param array $votes
	*/
	private function removeInvalidVotes($votes)
	{
		foreach($votes as $key => $vote){
			if ( !$this->postExists($vote) ) unset($votes[$key]);
		}
		return $votes;
	}

	/**
	* Filter the votes
	* @since 1.1.1
	* @param array $votes
	*/
	private function filterVotes($votes)
	{
		$votes = new VoteFilter($votes, $this->filters);
		return $votes->filter();
	}

	/**
	* Return an HTML list of votes for specified user
	* @param $include_button boolean - whether to include the vote button
	* @param $include_thumbnails boolean - whether to include post thumbnails
	* @param $thumbnail_size string - thumbnail size to display
	* @param $include_excerpt boolean - whether to include the post excerpt
	*/
	public function getVotesList($include_button = false, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false, $no_votes = '')
	{
		$list_args = [
			'include_button' => $include_button,
			'include_thumbnails' => $include_thumbnails,
			'thumbnail_size' => $thumbnail_size,
			'include_excerpt' => $include_excerpt,
			'include_links' => $this->links,
			'site_id' => $this->site_id,
			'user_id' => $this->user_id,
			'no_votes' => $no_votes,
			'filters' => $this->filters,
		];
		$list = new VoteList($list_args);
		return $list->getList();
	}

	/**
	* Check if post exists and is published
	*/
	private function postExists($id)
	{
		$allowed_statuses = ( isset($this->filters['status']) && is_array($this->filters['status']) ) ? $this->filters['status'] : array('publish');
		$status = get_post_status($id);
		if ( !$status ) return false;
		if ( !in_array($status, $allowed_statuses) ) return false;
		return true;
	}
}