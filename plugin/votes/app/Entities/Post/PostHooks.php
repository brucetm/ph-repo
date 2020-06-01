<?php
namespace Votes\Entities\Post;

use Votes\Config\SettingsRepository;
use Votes\Entities\Vote\VoteButton;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VoteButton.php';

/**
* Post Actions and Filters
*/
class PostHooks
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* The Content
	*/
	private $content;

	/**
	* The Post Object
	*/
	private $post;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_filter('the_content', [$this, 'filterContent']);
	}

	/**
	* Filter the Content
	*/
	public function filterContent($content)
	{
		global $post;
		if ( !$post ) return $content;
		$this->post = $post;
		$this->content = $content;

		$display = $this->settings_repo->displayInPostType($post->post_type);
		if ( !$display ) return $content;

		return $this->addVoteButton($display);
	}

	/**
	* Add the Vote Button
	* @todo add vote button html
	*/
	private function addVoteButton($display_in)
	{
		$output = '';

		if ( isset($display_in['before_content']) && $display_in['before_content'] == 'true' ){
			$output .= get_votes_button();
		}

		$output .= $this->content;

		if ( isset($display_in['after_content']) && $display_in['after_content'] == 'true' ){
			$output .= get_votes_button();
		}
		return $output;
	}
}