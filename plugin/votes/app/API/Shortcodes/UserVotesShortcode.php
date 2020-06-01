<?php
namespace Votes\API\Shortcodes;

use Votes\Entities\User\UserVotes;

class UserVotesShortcode
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	/**
	* List Filters
	* @var array
	*/
	private $filters;

	public function __construct()
	{
		add_shortcode('user_votes', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'user_id' => '',
			'site_id' => '',
			'include_links' => 'true',
			'post_types' => '',
			'include_buttons' => 'false',
			'include_thumbnails' => 'false',
			'thumbnail_size' => 'thumbnail',
			'include_excerpts' => 'false',
			'no_votes' => ''
		], $options);
	}

	/**
	* Parse Post Types
	*/
	private function parsePostTypes()
	{
		if ( $this->options['post_types'] == "" ) return;
		$post_types = explode(',', $this->options['post_types']);
		$this->filters = ['post_type' => $post_types];
	}

	/**
	* Render the HTML list
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		$this->parsePostTypes();

		if ( $this->options['user_id'] == "" ) $this->options['user_id'] = null;
		if ( $this->options['site_id'] == "" ) $this->options['site_id'] = null;
		$this->options['include_links'] = ( $this->options['include_links'] == 'true' ) ? true : false;
		$this->options['include_buttons'] = ( $this->options['include_buttons'] == 'true' ) ? true : false;
		$this->options['include_thumbnails'] = ( $this->options['include_thumbnails'] == 'true' ) ? true : false;
		$this->options['include_excerpts'] = ( $this->options['include_excerpts'] == 'true' ) ? true : false;

		$votes = new UserVotes(
			$this->options['user_id'],
			$this->options['site_id'],
			$this->options['include_links'],
			$this->filters
		);
		return $votes->getVotesList(
			$this->options['include_buttons'],
			$this->options['include_thumbnails'],
			$this->options['thumbnail_size'],
			$this->options['include_excerpts'],
			$this->options['no_votes']
		);
	}
}