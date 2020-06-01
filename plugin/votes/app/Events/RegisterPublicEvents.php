<?php
namespace Votes\Events;

use Votes\Listeners\VoteButton;
use Votes\Listeners\VotesArray;
use Votes\Listeners\ClearVotes;
use Votes\Listeners\VoteCount;
use Votes\Listeners\VoteList;
use Votes\Listeners\CookieConsent;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/VoteButton.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/VotesArray.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/ClearVotes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/VoteList.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Listeners/CookieConsent.php';

class RegisterPublicEvents
{
	public function __construct()
	{
		// Front End Vote Button
		add_action( 'wp_ajax_nopriv_votes_vote', [$this, 'voteButton']);
		add_action( 'wp_ajax_votes_vote', [$this, 'voteButton']);

		// User's Voted Posts (array of IDs)
		add_action( 'wp_ajax_nopriv_votes_array', [$this, 'votesArray']);
		add_action( 'wp_ajax_votes_array', [$this, 'votesArray']);

		// Clear Votes
		add_action( 'wp_ajax_nopriv_votes_clear', [$this, 'clearVotes']);
		add_action( 'wp_ajax_votes_clear', [$this, 'clearVotes']);

		// Total Vote Count
		add_action( 'wp_ajax_nopriv_votes_totalcount', [$this, 'voteCount']);
		add_action( 'wp_ajax_votes_totalcount', [$this, 'voteCount']);

		// Single Vote List
		add_action( 'wp_ajax_nopriv_votes_list', [$this, 'voteList']);
		add_action( 'wp_ajax_votes_list', [$this, 'voteList']);

		// Accept/Deny Cookies
		add_action( 'wp_ajax_nopriv_votes_cookie_consent', [$this, 'cookiesConsented']);
		add_action( 'wp_ajax_votes_cookie_consent', [$this, 'cookiesConsented']);

	}

	/**
	* Vote Button
	*/
	public function voteButton()
	{
		new VoteButton;
	}

	/**
	* Generate a Nonce
	*/
	public function nonce()
	{
		new NonceHandler;
	}

	/**
	* Get an array of current user's votes
	*/
	public function votesArray()
	{
		new VotesArray;
	}

	/**
	* Clear all Votes
	*/
	public function clearVotes()
	{
		new ClearVotes;
	}

	/**
	* Vote Count for a single post
	*/
	public function voteCount()
	{
		new VoteCount;
	}

	/**
	* Single Vote List for a Specific User
	*/
	public function voteList()
	{
		new VoteList;
	}

	/**
	* Cookies were either accepted or denied
	*/
	public function cookiesConsented()
	{
		new CookieConsent;
	}
}