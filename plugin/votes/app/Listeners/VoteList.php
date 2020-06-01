<?php
namespace Votes\Listeners;

use Votes\Entities\User\UserVotes;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/AJAXListenerBase.php';

class VoteList extends AJAXListenerBase
{
	/**
	* Form Data
	* @var array
	*/
	protected $data;

	/**
	* List HTML
	*/
	private $list;

	public function __construct()
	{
		parent::__construct();
		$this->setData();
		$this->getList();
		wp_send_json(['status' => 'success', 'list' => $this->list, 'data' => $this->data]);
	}

	/**
	* Set the User ID & Site ID
	*/
	private function setData()
	{
		$this->data['user_id'] = ( isset($_POST['userid']) ) ? intval($_POST['userid']) : null;
		$this->data['site_id'] = ( isset($_POST['siteid']) ) ? intval($_POST['siteid']) : null;
		$this->data['include_links'] = ( isset($_POST['include_links']) && $_POST['include_links'] == 'true' ) ? true : false;
		$this->data['include_buttons'] = ( isset($_POST['include_buttons']) && $_POST['include_buttons'] == 'true' ) ? true : false;
		$this->data['include_thumbnails'] = ( isset($_POST['include_thumbnails']) && $_POST['include_thumbnails'] == 'true' ) ? true : false;
		$this->data['thumbnail_size'] = ( isset($_POST['thumbnail_size']) && $_POST['thumbnail_size'] != '' ) ? sanitize_text_field($_POST['thumbnail_size']) : 'thumbnail';
		$this->data['include_excerpt'] = ( isset($_POST['include_excerpt']) && $_POST['include_excerpt'] == 'true' ) ? true : false;
		$this->data['no_votes'] = ( isset($_POST['no_votes']) ) ? sanitize_text_field($_POST['no_votes']) : '';
		$this->data['post_types'] = ( isset($_POST['post_types']) ) ? explode(',', $_POST['post_types']) : array();
	}

	/**
	* Get the votes list
	*/
	private function getList()
	{
		global $blog_id;
		$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
		if ( !is_multisite() ) $site_id = 1;

		$filters = ( !empty($this->data['post_types']) ) ? ['post_type' => $this->data['post_types']] : null;

		$votes = new UserVotes(
			$this->data['user_id'],
			$this->data['site_id'],
			$this->data['include_links'],
			$filters
		);
		$this->list = $votes->getVotesList(
			$include_button = $this->data['include_buttons'],
			$this->data['include_thumbnails'],
			$this->data['thumbnail_size'],
			$this->data['include_excerpt'],
			$this->data['no_votes']
		);
	}
}