<?php
namespace Votes\Entities\Vote;

use Votes\Entities\Post\VoteCount;
use Votes\Entities\Vote\VoteButton;
use Votes\Entities\VoteList\VoteList;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/VoteCount.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VoteButton.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteList.php';

/**
* Format the user's vote array to include additional post data
*/
class VotesArrayFormatter
{
	/**
	* Formatted votes array
	*/
	private $formatted_votes;

	/**
	* Total Votes Counter
	*/
	private $counter;

	/**
	* Post ID to add to return array
	* For adding/removing session/cookie votes for current request
	* @var int
	*/
	private $post_id;

	/**
	* Site ID for post to add to array
	* For adding/removing session/cookie votes for current request
	* @var int
	*/
	private $site_id;

	/**
	* Site ID for post to add to array
	* For adding/removing session/cookie votes for current request
	* @var string
	*/
	private $status;

	public function __construct()
	{
		$this->counter = new VoteCount;
	}

	public function format($votes, $post_id = null, $site_id = null, $status = null)
	{
		$this->formatted_votes = $votes;
		$this->post_id = $post_id;
		$this->site_id = $site_id;
		$this->status = $status;
		$this->resetIndexes();
		$this->addPostData();
		return $this->formatted_votes;
	}

	/**
	* Reset the vote indexes
	*/
	private function resetIndexes()
	{
		foreach ( $this->formatted_votes as $site => $site_votes ){
			// Make older posts compatible with new name
			if ( !isset($site_votes['posts']) ) {
				$site_votes['posts'] = $site_votes['site_votes'];
				unset($this->formatted_votes[$site]['site_votes']);
			}
			foreach ( $site_votes['posts'] as $key => $vote ){
				unset($this->formatted_votes[$site]['posts'][$key]);
				$this->formatted_votes[$site]['posts'][$vote]['post_id'] = $vote;
			}
		}
	}

	/**
	* Add the post type to each vote
	*/
	private function addPostData()
	{
		$this->checkCurrentPost();
		foreach ( $this->formatted_votes as $site => $site_votes ){
			foreach ( $site_votes['posts'] as $key => $vote ){
				$site_id = $this->formatted_votes[$site]['site_id'];
				$this->formatted_votes[$site]['posts'][$key]['post_type'] = get_post_type($key);
				$this->formatted_votes[$site]['posts'][$key]['title'] = get_the_title($key);
				$this->formatted_votes[$site]['posts'][$key]['permalink'] = get_the_permalink($key);
				$this->formatted_votes[$site]['posts'][$key]['total'] = $this->counter->getCount($key, $site_id);
				$this->formatted_votes[$site]['posts'][$key]['thumbnails'] = $this->getThumbnails($key);
				$this->formatted_votes[$site]['posts'][$key]['excerpt'] = apply_filters('the_excerpt', get_post_field('post_excerpt', $key));
				$button = new VoteButton($key, $site_id);
				$this->formatted_votes[$site]['posts'][$key]['button'] = $button->display(false);
			}
			$this->formatted_votes[$site] = array_reverse($this->formatted_votes[$site]);
		}
	}

	/**
	* Make sure the current post is updated in the array
	* (for cookie/session votes, so AJAX response returns array with correct posts without page refresh)
	*/
	private function checkCurrentPost()
	{
		if ( !isset($this->post_id) || !isset($this->site_id) ) return;
		if ( is_user_logged_in() ) return;
		foreach ( $this->formatted_votes as $site => $site_votes ){
			if ( $site_votes['site_id'] == $this->site_id ) {
				if ( isset($site_votes['posts'][$this->post_id]) && $this->status == 'inactive' ){
					unset($this->formatted_votes[$site]['posts'][$this->post_id]);
				} else {
					$this->formatted_votes[$site]['posts'][$this->post_id] = array('post_id' => $this->post_id);
				}
			}
		}
	}

	/**
	* Add thumbnail urls to the array
	*/
	private function getThumbnails($post_id)
	{
		if ( !has_post_thumbnail($post_id) ) return false;
		$sizes = get_intermediate_image_sizes();
		$thumbnails = [];
		foreach ( $sizes as $size ){
			$url = get_the_post_thumbnail_url($post_id, $size);
			$img = '<img src="' . $url . '" alt="' . get_the_title($post_id) . '" class="votes-list-thumbnail" />';
			$thumbnails[$size] = apply_filters('votes/list/thumbnail', $img, $post_id, $size);
		}
		return $thumbnails;
	}
}