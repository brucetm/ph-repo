<?php
namespace Votes\Entities\Vote;

use Votes\Config\SettingsRepository;
use Votes\Entities\Vote\SyncSingleVote;
use Votes\Entities\Post\SyncVoteCount;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/SyncSingleVote.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/SyncVoteCount.php';

class Vote
{
	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* Save Type
	*/
	private $save_type;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
	}

	/**
	* Save the Vote
	*/
	public function update($post_id, $status, $site_id, $group_id = 1)
	{
		$this->save_type = $this->settings_repo->saveType();
		$usersync = new SyncSingleVote($post_id, $site_id, $group_id);
		$saveType = $this->save_type;
		$usersync->$saveType();

		$postsync = new SyncVoteCount($post_id, $status, $site_id);
		$postsync->sync();
	}

	/**
	* Get the Save Type
	*/
	public function saveType()
	{
		return $this->save_type;
	}
}