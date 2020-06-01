<?php
/**
* Primary plugin API functions
*/

use Votes\Entities\Vote\VoteButton;
use Votes\Entities\Post\VoteCount;
use Votes\Entities\User\UserVotes;
use Votes\Entities\Post\PostVotes;
use Votes\Entities\Vote\ClearVotesButton;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/VoteButton.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/VoteCount.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Post/PostVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/Vote/ClearVotesButton.php';

/**
* Get the vote button
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function get_votes_button($post_id = null, $site_id = null, $group_id = null)
{
	global $blog_id;
	if ( !$post_id ) $post_id = get_the_id();
	if ( !$group_id ) $group_id = 1;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$button = new VoteButton($post_id, $site_id);
	return $button->display();
}


/**
* Echos the vote button
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function the_votes_button($post_id = null, $site_id = null, $group_id = null)
{
	echo get_votes_button($post_id, $site_id, $group_id);
}


/**
* Get the Vote Total Count for a Post
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @param $html bool, whether to return html (returns simple integer if false)
* @return html
*/
function get_votes_count($post_id = null, $site_id = null, $html = true)
{
	global $blog_id;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !$post_id ) $post_id = get_the_id();
	$count = new VoteCount();
	$count = $count->getCount($post_id, $site_id);
	$out = "";
	if ( $html ) $out .= '<span data-votes-post-count-id="' . $post_id . '" data-siteid="' . $site_id . '">';
	$out .= $count;
	if ( $html ) $out .= '</span>';
	return $out;
}


/**
* Echo the Vote Count
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @return html
*/
function the_votes_count($post_id = null, $site_id = null, $html = true)
{
	echo get_votes_count($post_id, $site_id, $html);
}


/**
* Get an array of User Votes
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return array
*/
function get_user_votes($user_id = null, $site_id = null, $filters = null)
{
	global $blog_id;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$votes = new UserVotes($user_id, $site_id, $links = false, $filters);
	return $votes->getVotesArray();
}


/**
* HTML List of User Votes
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @param $include_button boolean, whether to include the vote button for each item
* @param $include_thumbnails boolean, whether to include the thumbnail for each item
* @param $thumbnail_size string, the thumbnail size to display
* @param $include_excpert boolean, whether to include the excerpt for each item
* @return html
*/
function get_user_votes_list($user_id = null, $site_id = null, $include_links = false, $filters = null, $include_button = false, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false)
{
	global $blog_id;
	$site_id = ( is_multisite() && is_null($site_id) ) ? $blog_id : $site_id;
	if ( !is_multisite() ) $site_id = 1;
	$votes = new UserVotes($user_id, $site_id, $include_links, $filters);
	return $votes->getVotesList($include_button, $include_thumbnails, $thumbnail_size, $include_excerpt);
}


/**
* Echo HTML List of User Votes
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @param $include_button boolean, whether to include the vote button for each item
* @param $include_thumbnails boolean, whether to include the thumbnail for each item
* @param $thumbnail_size string, the thumbnail size to display
* @param $include_excpert boolean, whether to include the excerpt for each item
* @return html
*/
function the_user_votes_list($user_id = null, $site_id = null, $include_links = false, $filters = null, $include_button = false, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false)
{
	echo get_user_votes_list($user_id, $site_id, $include_links, $filters, $include_button, $include_thumbnails, $thumbnail_size, $include_excerpt);
}


/**
* Get the number of posts a specific user has voted
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @param $html boolean, whether to output html (important for AJAX updates). If false, an integer is returned
* @return int
*/
function get_user_votes_count($user_id = null, $site_id = null, $filters = null, $html = false)
{
	$votes = get_user_votes($user_id, $site_id, $filters);
	$posttypes = ( isset($filters['post_type']) ) ? implode(',', $filters['post_type']) : 'all';
	$count = ( isset($votes[0]['site_id']) ) ? count($votes[0]['posts']) : count($votes);
	$out = "";
	if ( !$site_id ) $site_id = 1;
	if ( $html ) $out .= '<span class="simplevotes-user-count" data-posttypes="' . $posttypes . '" data-siteid="' . $site_id . '">';
	$out .= $count;
	if ( $html ) $out .= '</span>';
	return $out;
}


/**
* Print the number of posts a specific user has voted
* @param $user_id int, defaults to current user
* @param $site_id int, defaults to current blog/site
* @param $filters array of post types/taxonomies
* @return html
*/
function the_user_votes_count($user_id = null, $site_id = null, $filters = null)
{
	echo get_user_votes_count($user_id, $site_id, $filters);
}


/**
* Get an array of users who have voted a post
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @param $user_role string, defaults to all
* @return array of user objects
*/
function get_users_who_voted_post($post_id = null, $site_id = null, $user_role = null)
{
	$users = new PostVotes($post_id, $site_id, $user_role);
	return $users->getUsers();
}


/**
* Get a list of users who voted a post
* @param $post_id int, defaults to current post
* @param $site_id int, defaults to current blog/site
* @param $separator string, custom separator between items (defaults to HTML list)
* @param $include_anonmyous boolean, whether to include anonmyous users
* @param $anonymous_label string, label for anonymous user count
* @param $anonymous_label_single string, singular label for anonymous user count
* @param $user_role string, defaults to all
*/
function the_users_who_voted_post($post_id = null, $site_id = null, $separator = 'list', $include_anonymous = true, $anonymous_label = 'Anonymous Users', $anonymous_label_single = 'Anonymous User', $user_role = null)
{
	$users = new PostVotes($post_id, $site_id, $user_role);
	echo $users->userList($separator, $include_anonymous, $anonymous_label, $anonymous_label_single);
}

/**
 * Get the number of anonymous users who voted a post
 * @param  $post_id int Defaults to current post
 * @return int Just anonymous users
 */
function get_anonymous_users_who_voted_post( $post_id = null ) {
	$user = new PostVotes( $post_id );
	return $users->anonymousCount();
}

/**
 * Echo the number of anonymous users who voted a post
 * @param  $post_id int Defaults to current post
 * @return string Just anonymous users
 */
function the_anonymous_users_who_voted_post( $post_id = null ) {
	echo get_anonymous_users_who_favourited_post( $post_id );
}

/**
* Get the clear votes button
* @param $site_id int, defaults to current blog/site
* @param $text string, button text - defaults to site setting
* @return html
*/
function get_clear_votes_button($site_id = null, $text = null)
{
	$button = new ClearVotesButton($site_id, $text);
	return $button->display();
}


/**
* Print the clear votes button
* @param $site_id int, defaults to current blog/site
* @param $text string, button text - defaults to site setting
* @return html
*/
function the_clear_votes_button($site_id = null, $text = null)
{
	echo get_clear_votes_button($site_id, $text);
}

/**
* Get the total number of votes, for all posts and users
* @param $site_id int, defaults to current blog/site
* @return html
*/
function get_total_votes_count($site_id = null)
{
	$count = new VoteCount();
	return $count->getAllCount($site_id);
}

/**
* Print the total number of votes, for all posts and users
* @param $site_id int, defaults to current blog/site
* @return html
*/
function the_total_votes_count($site_id = null)
{
	echo get_total_votes_count($site_id);
}