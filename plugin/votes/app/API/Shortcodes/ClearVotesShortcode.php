<?php
namespace Votes\API\Shortcodes;

class ClearVotesShortcode
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('clear_votes_button', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'site_id' => null,
			'text' => null
		], $options);
	}

	/**
	* Render the Button
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);
		return get_clear_votes_button($this->options['site_id'], $this->options['text']);
	}
}