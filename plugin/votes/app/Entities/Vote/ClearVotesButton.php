<?php
namespace Votes\Entities\Vote;

use Votes\Entities\User\UserRepository;
use Votes\Config\SettingsRepository;

require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Entities/User/UserRepository.php';
require_once $_SERVER['DOCUMENT_ROOT'].'wp-content/plugins/votes/app/Config/SettingsRepository.php';

class ClearVotesButton
{
	/**
	* Site ID
	*/
	private $site_id;

	/**
	* User Respository
	*/
	private $user;

	/**
	* The Button Text
	*/
	private $text;

	/**
	* Settings Repository
	*/
	private $settings_repo;

	public function __construct($site_id, $text)
	{
		$this->user = new UserRepository;
		$this->settings_repo = new SettingsRepository;
		$this->site_id = $site_id;
		$this->text = $text;
	}

	/**
	* Display the button
	*/
	public function display()
	{
		if ( !$this->user->getsButton() ) return false;
		if ( !$this->text ) $this->text = html_entity_decode($this->settings_repo->clearVotesText());
		if ( !$this->site_id ) $this->site_id = 1;
		$out = '<button class="simplevotes-clear" data-siteid="' . $this->site_id . '">' . $this->text . '</button>';
		return $out;
	}
}