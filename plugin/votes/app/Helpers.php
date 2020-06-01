<?php
namespace Votes;

/**
* Static Helper Methods
*/
class Helpers
{
	/**
	* Plugin Root Directory
	*/
	public static function plugin_url()
	{
		return plugins_url() . '/' . dirname( plugin_basename( VOTES_PLUGIN_FILE ) );
	}

	/**
	* Views
	*/
	public static function view($file)
	{
		return dirname(__FILE__) . '/Views/' . $file . '.php';
	}

	/**
	* Plugin Version
	*/
	public static function version()
	{
		global $votes_version;
		return $votes_version;
	}

	/**
	* Get File Contents
	*/
	public static function getFileContents($file)
	{
		return file_get_contents( dirname( dirname(__FILE__) ) . '/' . $file);
	}

	/**
	* Multidemensional array key search
	* @since 1.1
	* @return boolean
	*/
	public static function keyExists($needle, $haystack)
	{
		if ( array_key_exists($needle, $haystack) || in_array($needle, $haystack) ){
			return true;
		} else {
			$return = false;
			foreach ( array_values($haystack) as $value ){
				if ( is_array($value) && !$return ) $return = self::keyExists($needle, $value);
			}
			return $return;
		}
	}

	/**
	* Site ID Exists
	* checks if site id is in votes array yet
	* @since 1.1
	* @return boolean
	*/
	public static function siteExists($site_id, $meta)
	{
		foreach ( $meta as $key => $site ){
			if ( $site['site_id'] == $site_id ) return true;
		}
		return false;
	}

	/**
	* Groups Exists
	* checks if groups array is in votes array yet
	* @since 2.2
	* @return boolean
	*/
	public static function groupsExist($site_votes)
	{
		if ( isset($site_votes['groups']) && !empty($site_votes['groups']) ) return true;
		return false;
	}

	/**
	* Pluck the site votes from saved meta array
	* @since 1.1
	* @param int $site_id
	* @param array $votes (user meta)
	* @return array
	*/
	public static function pluckSiteVotes($site_id, $all_votes)
	{
		foreach($all_votes as $site_votes){
			if ( $site_votes['site_id'] == $site_id && isset($site_votes['posts']) ) return $site_votes['posts'];
		}
		return array();
	}

	/**
	* Pluck the site votes from saved meta array
	* @since 1.1
	* @param int $site_id
	* @param array $votes (user meta)
	* @return array
	*/
	public static function pluckGroupVotes($group_id, $site_id, $all_votes)
	{
		foreach($all_votes as $key => $site_votes){
			if ( $site_votes['site_id'] !== $site_id ) continue;
			foreach ( $all_votes[$key]['groups'] as $group ){
				if ( $group['group_id'] == $group_id ){
					return $group['posts'];
				}
			}
		}
		return array();
	}
}