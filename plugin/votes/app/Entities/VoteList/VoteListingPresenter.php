<?php
namespace Votes\Entities\VoteList;

/*
* Filters the votes listing markup
*/
class VoteListingPresenter
{
	/**
	* The list options
	* @var object
	*/
	private $list_options;

	/**
	* The Vote Post ID
	* @var int
	*/
	private $vote;

	/**
	* The custom markup
	* @var str
	*/
	private $markup;

	/**
	* The return html
	* @var str
	*/
	private $html;

	/**
	* Primary API Method
	* @param str markup
	* @param int vote
	*/
	public function present($list_options, $markup, $vote)
	{
		$this->list_options = $list_options;
		$this->markup = $markup;
		$this->vote = $vote;

		$this->listingOpening();
		$this->filterMarkup();
		$this->listingClosing();

		return apply_filters('votes/list/listing/html', $this->html, $this->markup, $this->vote, $this->list_options);
	}

	/**
	* Create the listing opening
	*/
	private function listingOpening()
	{
		$css = apply_filters('votes/list/listing/css', $this->list_options->listing_css, $this->list_options);
		$this->html = '<' . $this->list_options->listing_type;
		$this->html .= ' data-postid="' . $this->vote . '" class="' . $css . '">';
	}

	/**
	* Create the listing closing
	*/
	private function listingClosing()
	{
		$this->html .= '</' . $this->list_options->listing_type . '>';
	}

	/**
	* Filter the markup
	*/
	private function filterMarkup()
	{
		$this->html .= apply_filters('the_content', $this->markup);
		$this->replacePostFields();
		$this->replaceVotesFields();
		$this->replaceThumbnails();
		$this->replaceCustomFields();
	}

	/**
	* Replace Post Fields
	*/
	private function replacePostFields()
	{
		$this->html = str_replace('[post_title]', get_the_title($this->vote), $this->html);
		$this->html = str_replace('[post_permalink]', get_permalink($this->vote), $this->html);
		$this->html = str_replace('[permalink]', '<a href="' . get_permalink($this->vote) . '">', $this->html);
		$this->html = str_replace('[/permalink]', '</a>', $this->html);
		$this->html = str_replace('[post_excerpt]', $this->getPostExcerpt(), $this->html);
		$this->html = str_replace('[post_content]', get_the_content($this->vote), $this->html);
	}

	/**
	* Replace Votes Fields
	*/
	private function replaceVotesFields()
	{
		$this->html = str_replace(
			'[votes_count]',
			'<span class="simplevotes-user-count" data-posttypes="' . $this->list_options->post_types . '" data-siteid="' . $this->list_options->site_id . '">' . get_votes_count($this->vote, $this->list_options->site_id) . '</span>',
			$this->html
		);
		$this->html = str_replace('[votes_button]', get_votes_button($this->vote, $this->list_options->site_id), $this->html);
	}

	/**
	* Replace Thumbnails
	*/
	private function replaceThumbnails()
	{
		$sizes = get_intermediate_image_sizes();
		foreach ( $sizes as $size ){
			if ( strpos($this->html, '[post_thumbnail_' . $size) !== false ){
				$thumb = apply_filters('votes/list/thumbnail', $this->getThumbnail($size), $this->vote, $this->list_options->thumbnail_size);
				$this->html = str_replace('[post_thumbnail_' . $size . ']', $thumb, $this->html);
			}
		}
	}

	/**
	* Get a thumbnail
	*/
	private function getThumbnail($size)
	{
		return ( has_post_thumbnail($this->vote) )	? get_the_post_thumbnail($this->vote, $size) : ' ';
	}

	/**
	* Replace custom fields
	*/
	private function replaceCustomFields()
	{
		preg_match_all("/\[[^\]]*\]/", $this->html, $out);
		if ( empty($out) ) return;
		foreach($out[0] as $field){
			$field_bracketed = $field;
			$key = str_replace('[', '', $field);
			$key = str_replace('custom_field:', '', $key);
			$key = str_replace(']', '', $key);
			$meta = get_post_meta($this->vote, $key, true);
			if ( !$meta ) $meta = '';
			if ( is_array($meta) ) $meta = '';
			$this->html = str_replace($field_bracketed, $meta, $this->html);
		}
	}

	/**
	* Get the post excerpt
	*/
	private function getPostExcerpt()
	{
		$post_id = $this->vote;
		$custom_excerpt = get_post_field('post_excerpt', $this->vote);
		if ( $custom_excerpt ) return $custom_excerpt;

    	$the_post = get_post($post_id);
    	$the_excerpt = $the_post->post_content;
    	$excerpt_length = 35;
    	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
		$words = explode(' ', $the_excerpt, $excerpt_length + 1);

		if ( count($words) > $excerpt_length ) :
			array_pop($words);
			array_push($words, '…');
			$the_excerpt = implode(' ', $words);
		endif;
		return $the_excerpt;
	}

}