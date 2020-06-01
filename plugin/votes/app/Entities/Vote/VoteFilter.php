<?php
namespace Votes\Entities\Vote;

/**
* Filters an array of votes using provided array of filters
*/
class VoteFilter
{
	/**
	* Votes
	* @var array of post IDs
	*/
	private $votes;

	/**
	* Filters
	* @var array
	*
	* Example:
	*
	* array(
	* 	'post_type' => array('post', 'posttypetwo'),
	*	'terms' => array(
	*		'category' => array(
	*			'termone', 'termtwo', 'termthree'
	*		),
	*		'other-taxonomy' => array(
	*			'termone', 'termtwo', 'termthree'
	*		)
	*	)
	* );
	*
	*/
	private $filters;

	public function __construct($votes, $filters)
	{
		$this->votes = $votes;
		$this->filters = $filters;
	}

	public function filter()
	{
		if ( isset($this->filters['post_type']) && is_array($this->filters['post_type']) ) $this->filterByPostType();
		if ( isset($this->filters['terms']) && is_array($this->filters['terms']) ) $this->filterByTerm();
		if ( isset($this->filters['status']) && is_array($this->filters['status']) ) $this->filterByStatus();
		return $this->votes;
	}

	/**
	* Filter votes by post type
	* @since 1.1.1
	* @param array $votes
	*/
	private function filterByPostType()
	{
		foreach($this->votes as $key => $vote){
			$post_type = get_post_type($vote);
			if ( !in_array($post_type, $this->filters['post_type']) ) unset($this->votes[$key]);
		}
	}

	/**
	* Filter votes by status
	* @since 2.1.4
	*/
	private function filterByStatus()
	{
		foreach($this->votes as $key => $vote){
			$status = get_post_status($vote);
			if ( !in_array($status, $this->filters['status']) ) unset($this->votes[$key]);
		}
	}

	/**
	* Filter votes by terms
	* @since 1.1.1
	* @param array $votes
	*/
	private function filterByTerm()
	{
		$taxonomies = $this->filters['terms'];
		$votes = $this->votes;

		foreach ( $votes as $key => $vote ) :

			$all_terms = [];
			$all_filters = [];

			foreach ( $taxonomies as $taxonomy => $terms ){
				if ( !isset($terms) || !is_array($terms) ) continue;
				$post_terms = wp_get_post_terms($vote, $taxonomy, array("fields" => "slugs"));
				if ( !empty($post_terms) ) $all_terms = array_merge($all_terms, $post_terms);
				$all_filters = array_merge($all_filters, $terms);
			}

			$dif = array_diff($all_filters, $all_terms);
			if ( !empty($dif) ) unset($votes[$key]);

		endforeach;

		$this->votes = $votes;
	}
}