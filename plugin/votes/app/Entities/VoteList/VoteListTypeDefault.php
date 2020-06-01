<?php
namespace Votes\Entities\VoteList;

use Votes\Entities\Vote\VoteButton;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VoteButton.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/VoteList/VoteListTypeBase.php';

/**
* Create a votes list using the default markup
*/
class VoteListTypeDefault extends VoteListTypeBase
{
	public function __construct($list_options)
	{
		parent::__construct($list_options);
	}

	private function setTemplate()
	{
		$template = '';
		if ( $this->list_options->include_thumbnails ) $template .= '[post_thumbnail_' . $this->list_options->thumbnail_size . ']';
		$template .= "\n\n";
		if ( $this->list_options->include_links )  $template .= '<a href="[post_permalink]">';
		$template .= '[post_title]';
		if ( $this->list_options->include_links ) $template .= '</a>';
		$template .= "\n\n";
		if ( $this->list_options->include_excerpt ) $template .= "[post_excerpt]\n\n";
		if ( $this->list_options->include_button )$template .= "[votes_button]";
		return $template;
	}

	/**
	* Generate a single listing
	* @param int vote post id
	*/
	public function listing($vote)
	{
		return $this->listing_presenter->present($this->list_options, $this->setTemplate(), $vote);
	}
}