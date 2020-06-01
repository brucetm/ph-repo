<?php
namespace Votes\Listeners;

use Votes\Entities\User\UserRepository;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserRepository.php';

class CookieConsent
{
	/**
	* Consented?
	* @var bool
	*/
	private $consent;

	/**
	* User Repo
	*/
	private $user_repo;

	public function __construct()
	{
		$this->user_repo = new UserRepository;
		$this->setConsent();
		$this->respond();
	}

	private function setConsent()
	{
		$this->consent = ( isset($_POST['consent']) && $_POST['consent'] == 'true' ) ? true : false;
		if ( $this->consent ){
			$this->setApprove();
			return;
		}
		$this->setDeny();
	}

	private function setApprove()
	{
		$cookie = [];
		if ( isset($_COOKIE['simplevotes']) ) {
			$cookie = json_decode(stripslashes($_COOKIE['simplevotes']), true);
		} else {
			$cookie = $this->user_repo->getAllVotes();
		}
		$cookie[0]['consent_provided'] = time();
		setcookie( 'simplevotes', json_encode( $cookie ), time() + apply_filters( 'simplevotes_cookie_expiration_interval', 31556926 ), '/' );
	}

	private function setDeny()
	{
		$cookie = [];
		$cookie[0]['consent_denied'] = time();
		setcookie( 'simplevotes', json_encode( $cookie ), time() + apply_filters( 'simplevotes_cookie_expiration_interval', 31556926 ), '/' );
	}

	private function respond()
	{
		wp_send_json([
			'status' => 'success',
			'consent' => $this->consent
		]);
	}
}