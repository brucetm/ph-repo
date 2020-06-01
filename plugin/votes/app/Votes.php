<?php
/**
* Static Wrapper for Bootstrap Class
* Prevents T_STRING error when checking for 5.3.2
*/
class Votes
{
	public static function init()
	{
		// dev/live
		global $votes_env;
		$votes_env = 'live';

		global $votes_version;
		$votes_version = '2.3.2';

		global $votes_name;
		$votes_name = __('Votes', 'votes');
        require_once('Bootstrap.php');
		$app = new Votes\Bootstrap;
	}
}