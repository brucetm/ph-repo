<?php
namespace Votes\Entities\VoteList;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteListBase.php';
/**
* Get a full list of user votes
* @param array of options from shortcode/api function
*/
class VoteList extends VoteListBase
{
	public function __construct($options)
	{
		parent::__construct($options);
	}

	/**
	* Get the list
	*/
	public function getList()
	{
		$list = ( !$this->list_options->customize_markup || !$this->list_options->custom_markup_html )
			? new VoteListTypeDefault($this->list_options)
			: new VoteListTypeCustom($this->list_options);
		return $list->getListMarkup();
	}

	/**
	* Get a single listing
	*/
	public function getListing($post_id)
	{
		$list = ( !$this->list_options->customize_markup || !$this->list_options->custom_markup_html )
			? new VoteListTypeDefault($this->list_options)
			: new VoteListTypeCustom($this->list_options);
		return $list->listing($post_id);
	}
}