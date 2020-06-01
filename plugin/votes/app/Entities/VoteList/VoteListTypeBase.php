<?php
namespace Votes\Entities\VoteList;

use Votes\Entities\User\UserVotes;
use Votes\Config\SettingsRepository;
use Votes\Entities\VoteList\VoteListingPresenter;
use Votes\Entities\PostType\PostTypeRepository;

/**
* Base class for vote lists
*/
abstract class VoteListTypeBase
{
	/**
	* List options
	* @var object
	*/
	protected $list_options;

	/**
	* User votes object
	*/
	protected $user_votes;

	/**
	* Settings Repo
	*/
	protected $settings_repo;

	/**
	* Post Type Repo
	*/
	protected $post_type_repo;

	/**
	* User's Votes
	*/
	protected $votes;

	/**
	* Listing Presenter
	*/
	protected $listing_presenter;

	public function __construct($list_options)
	{
		$this->settings_repo = new SettingsRepository;
		$this->post_type_repo = new PostTypeRepository;
		$this->user_votes = new UserVotes;
		$this->listing_presenter = new VoteListingPresenter;
		$this->list_options = $list_options;
		$this->setApiOptions();
		$this->setVotes();
		$this->setNoVotesText();
		$this->setPostTypes();
	}

	/**
	* Set the API options (defined in shortcode and api functions)
	*/
	protected function setApiOptions()
	{
		$this->list_options->site_id = ( is_null($this->list_options->site_id) || $this->list_options->site_id == '' )
			? get_current_blog_id() : $this->list_options->site_id;
		$this->list_options->user_id = ( is_null($this->list_options->user_id) || $this->list_options->user_id == '' )
			? null : $this->list_options->user_id;
		$this->list_options->filters = ( is_null($this->list_options->filters) || $this->list_options->filters == '' )
			? null : $this->list_options->filters;
	}

	/**
	* Set the votes
	*/
	protected function setVotes()
	{
		$votes = $this->user_votes->getVotesArray($this->list_options->user_id, $this->list_options->site_id, $this->list_options->filters);
		$this->votes = ( isset($votes[0]['site_id']) ) ? $votes[0]['posts'] : $votes;
	}

	/**
	* Set the no votes text
	*/
	protected function setNoVotesText()
	{
		if ( $this->list_options->no_votes == '' )
			$this->list_options->no_votes = $this->settings_repo->noVotesText();
	}

	/**
	* Set the post types
	*/
	protected function setPostTypes()
	{
		$this->list_options->post_types = implode(',', $this->post_type_repo->getAllPostTypes('names', true));
		if ( isset($this->list_options->filters['post_type']) )
			$this->list_options->post_types = implode(',', $this->list_options->filters['post_type']);
	}

	/**
	* Generate the list opening
	*/
	protected function listOpening()
	{
		$css = apply_filters('votes/list/wrapper/css', $this->list_options->wrapper_css, $this->list_options);
		$out = '<' . $this->list_options->wrapper_type;
		$out .= ' class="votes-list ' . $css . '" data-userid="' . $this->list_options->user_id . '" data-siteid="' . $this->list_options->site_id . '" ';
		$out .= ( $this->list_options->include_button ) ? 'data-includebuttons="true"' : 'data-includebuttons="false"';
		$out .= ( $this->list_options->include_links ) ? ' data-includelinks="true"' : ' data-includelinks="false"';
		$out .= ( $this->list_options->include_thumbnails ) ? ' data-includethumbnails="true"' : ' data-includethumbnails="false"';
		$out .= ( $this->list_options->include_excerpt ) ? ' data-includeexcerpts="true"' : ' data-includeexcerpts="false"';
		$out .= ' data-thumbnailsize="' . $this->list_options->thumbnail_size . '"';
		$out .= ' data-novotestext="' . $this->list_options->no_votes . '"';
		$out .= ' data-posttypes="' . $this->list_options->post_types . '"';
		$out .= '>';
		return $out;
	}

	/**
	* Generate the list closing
	*/
	protected function listClosing()
	{
		return '</' . $this->list_options->wrapper_type . '>';
	}

	/**
	* Generates the no votes item
	*/
	protected function noVotes()
	{
		if ( !empty($this->votes) ) return;
		$out = $this->listOpening();
		$out .= '<' . $this->list_options->wrapper_type;
		$out .= ' data-postid="0" data-novotes class="no-votes">' . $this->list_options->no_votes;
		$out .= '</' . $this->list_options->wrapper_type . '>';
		$out .= $this->listClosing();
		return $out;
	}

	/**
	* Get the markup for a full list
	*/
	public function getListMarkup()
	{
		if ( is_multisite() ) switch_to_blog($this->list_options->site_id);
		if ( empty($this->votes) ) return $this->noVotes();

		$out = $this->listOpening();
		foreach ( $this->votes as $key => $vote ){
			$out .= $this->listing($vote);
		}
		$out .= $this->listClosing();
		if ( is_multisite() ) restore_current_blog();
		return $out;
	}
}