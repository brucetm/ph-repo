<?php
namespace Votes\Entities\Post;

use Votes\Config\SettingsRepository;
use Votes\Entities\Post\VoteCount;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/VoteCount.php';

class PostMeta
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_action( 'add_meta_boxes', [$this, 'voteCountBox']);
	}

	/**
	* Add the Vote Count Meta Box for enabled Types
	*/
	public function voteCountBox()
	{
		foreach ( $this->settings_repo->metaEnabled() as $type ){
			add_meta_box(
				'votes',
				__( 'Votes', 'votes' ),
				[$this, 'voteCount'],
				$type,
				'side',
				'low'
			);
		}
	}

	/**
	* The vote count
	*/
	public function voteCount()
	{
		global $post;
		$count = new VoteCount;
		echo '<strong>' . __('Total Votes', 'votes') . ':</strong> ';
		echo $count->getCount($post->ID);
		echo '<input type="hidden" name="simplevotes_count" value="' . $count->getCount($post->ID) . '">';
	}
}