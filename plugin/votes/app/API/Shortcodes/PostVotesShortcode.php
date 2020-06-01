<?php
namespace Votes\API\Shortcodes;

class PostVotesShortcode
{
	/**
	* Shortcode Options
	* @var array
	*/
	private $options;

	public function __construct()
	{
		add_shortcode('post_votes', [$this, 'renderView']);
	}

	/**
	* Shortcode Options
	*/
	private function setOptions($options)
	{
		$this->options = shortcode_atts([
			'post_id' => '',
			'site_id' => '',
			'separator' => 'list',
			'include_anonymous' => 'true',
			'anonymous_label' => __('Anonymous Users', 'votes'),
			'anonymous_label_single' => __('Anonymous User', 'votes')
		], $options);
	}

	/**
	* Render the HTML list
	* @param $options, array of shortcode options
	*/
	public function renderView($options)
	{
		$this->setOptions($options);

		$this->options['include_anonymous'] = ( $this->options['include_anonymous'] == 'true' ) ? true : false;

		return the_users_who_voted_post(
			$post_id = $this->options['post_id'],
			$site_id = $this->options['site_id'],
			$separator = $this->options['separator'],
			$include_anonymous = $this->options['include_anonymous'],
			$anonymous_label = $this->options['anonymous_label'],
			$anonymous_label_single =  $this->options['anonymous_label_single']
		);
	}
}