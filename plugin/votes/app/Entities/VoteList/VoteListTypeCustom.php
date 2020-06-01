<?php
namespace Votes\Entities\VoteList;

use Votes\Entities\User\UserVotes;
use Votes\Config\SettingsRepository;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteListTypeBase.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteListBase.php';

/**
* Generate the list for custom markup list
*/
class VoteListTypeCustom extends VoteListTypeBase
{

	public function __construct($list_options)
	{
		parent::__construct($list_options);
	}

	public function listing($vote)
	{
		return $this->listing_presenter->present($this->list_options, $this->list_options->custom_markup_html, $vote);
	}
}