<?php
namespace Votes\API\Shortcodes;

class VoteCountShortcode
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('vote_count', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'post_id' => '',
			'site_id' => ''
		], $options);
	}

	/**
	* Render the count
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		return get_votes_count($this->options['post_id'], $this->options['site_id']);
	}
}