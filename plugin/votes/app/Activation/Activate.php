<?php
namespace Votes\Activation;

/**
* Plugin Activation
*/
class Activate
{
	public function __construct()
	{
		$this->setOptions();
	}

	/**
	* Default Plugin Options
	*/
	private function setOptions()
	{
		if ( !get_option('simplevotes_dependencies')
			&& get_option('simplevotes_dependencies') !== "" ){
			update_option('simplevotes_dependencies', [
				'css' => 'true',
				'js' => 'true'
			]);
		}
		if ( !get_option('simplevotes_users')
			&& get_option('simplevotes_users') !== "" ){
			update_option('simplevotes_users', [
				'anonymous' => [
					'display' => 'true',
					'save' => 'true'
				],
				'saveas' => 'cookie'
			]);
		}
		if ( !get_option('simplevotes_display')
			&& get_option('simplevotes_display') !== "" ){
			update_option('simplevotes_display', [
				'buttontext' => __('Vote <i class="sf-icon-star-empty"></i>', 'votes'),
				'buttontextvoted' => __('Voted <i class="sf-icon-star-full"></i>', 'votes'),
				'posttypes' => [
					'post' => [
						'display' => true,
						'after_content' => true,
						'postmeta' => true
					]
				]
			]);
		}
		if ( !get_option('simplevotes_cache_enabled')
			&& get_option('simplevotes_cache_enabled') !== "" ){
			update_option('simplevotes_cache_enabled', 'true');
		}
	}
}