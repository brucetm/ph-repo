<?php
namespace Votes\Config;

use Votes\Helpers;

class SettingsRepository
{
	/**
	* Output Dependency
	* @return boolean
	* @param string - css/js
	* @since 1.0
	*/
	public function outputDependency($dependency = 'css')
	{
		$option = get_option('simplevotes_dependencies');
		return ( isset($option[$dependency]) && $option[$dependency] == 'true' ) ? true : false;
	}

	/**
	* Is Development Mode Enabled
	* @return boolean
	* @since 2.1.2
	*/
	public function devMode()
	{
		$option = get_option('simplevotes_dev_mode');
		return ( isset($option) && $option == 'true' ) ? true : false;
	}

	/**
	* Anonymous Display Options
	* @param string option (array key)
	* @since 1.0
	* @return boolean
	*/
	public function anonymous($option = 'display')
	{
		$anon_option = get_option('simplevotes_users');
		if ( isset($anon_option['anonymous'][$option])
			&& $anon_option['anonymous'][$option] == 'true') {
			return true;
		}
		return false;
	}

	/**
	* User Consent
	* @since 2.2.0
	* @return boolean
	*/
	public function consent($option)
	{
		$consent_option = get_option('simplevotes_users');
		if ( $option == 'require' ){
			return (
				isset($consent_option['consent'])
				&& isset($consent_option['consent']['require'])
				&& $consent_option['consent']['require'] == 'true') ? true : false;
		}
		if ( $option == 'modal' && isset($consent_option['consent']['modal']) ){
			return ( $consent_option['consent']['modal'] !== '' ) ? apply_filters('the_content', $consent_option['consent']['modal']) : false;
		}
		return ( isset($consent_option['consent'][$option]) ) ? $consent_option['consent'][$option] : false;
	}

	/**
	* Require Login? Shows button to unauthenticated users, but opens modal when trying to save
	* @since 2.0.3
	* @return boolean
	*/
	public function requireLogin()
	{
		$option = get_option('simplevotes_users');
		if ( isset($option['anonymous']['display'])
			&& $option['anonymous']['display'] == 'true') {
			return false;
		}
		if ( isset($option['require_login'])
			&& $option['require_login'] == 'true') {
			return true;
		}
		return false;
	}

	/**
	* Redirect anonymous users to a page?
	* @since 2.2.6
	* @return boolean
	*/
	public function redirectAnonymous()
	{
		$option = get_option('simplevotes_users');
		if ( isset($option['anonymous']['display'])
			&& $option['anonymous']['display'] == 'true') {
			return false;
		}
		if ( isset($option['redirect_anonymous'])
			&& $option['redirect_anonymous'] == 'true') {
			return true;
		}
		return false;
	}

	/**
	* Redirect post id for anonmyous users
	* @since 2.2.6
	* @return boolean
	*/
	public function redirectAnonymousId()
	{
		$option = get_option('simplevotes_users');
		if ( isset($option['anonymous']['display'])
			&& $option['anonymous']['display'] == 'true') {
			return false;
		}
		if ( isset($option['anonymous_redirect_id'])
			&& $option['anonymous_redirect_id'] !== '') {
			return $option['anonymous_redirect_id'];
		}
		return false;
	}

	/**
	* Authentication Gate Modal Content
	* @since 2.0.3
	* @return html
	*/
	public function authenticationModalContent($raw = false)
	{
		$option = get_option('simplevotes_users');
		if ( isset($option['authentication_modal'])
			&& $option['authentication_modal'] !== '') {
			$content = $option['authentication_modal'];
			if ( $raw ) return $content;
			add_filter('votes/authentication_modal_content', 'wptexturize');
			add_filter('votes/authentication_modal_content', 'convert_smilies');
			add_filter('votes/authentication_modal_content', 'convert_chars');
			add_filter('votes/authentication_modal_content', 'wpautop');
			add_filter('votes/authentication_modal_content', 'prepend_attachment');
			add_filter('votes/authentication_modal_content', 'shortcode_unautop');
			add_filter('votes/authentication_modal_content', 'do_shortcode');
			return apply_filters('votes/authentication_modal_content', $content);
		}
		$html = '<p>' . __('Please login to add votes.', 'votes') . '</p>';
		$html .= '<p><a href="#" data-votes-modal-close>' . __('Dismiss this notice', 'votes') . '</a></p>';
		return $html;
	}

	/**
	* Method of saving votes for anonymous users
	* @return string - cookie/session
	*/
	public function saveType()
	{
		$option = get_option('simplevotes_users');
		if ( !isset($option['anonymous']['saveas']) ) return 'cookie';
		return $option['anonymous']['saveas'];
	}

	/**
	* Display in a given Post Type?
	* @param string - post type name
	*/
	public function displayInPostType($posttype)
	{
		$types = get_option('simplevotes_display');
		if ( !empty($types['posttypes']) && $types !== "" ){
			foreach ( $types['posttypes'] as $key => $type ){
				if ( $key == $posttype && isset($type['display']) && $type['display'] == 'true' ) return $type;
			}
		}
		return false;
	}

	/**
	* Vote Button Text
	* @return string
	*/
	public function buttonText()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['buttontext']) || $option['buttontext'] == "" )
			return __('Vote', 'votes');
		return esc_html($option['buttontext']);
	}

	/**
	* Vote Button Text (Active state)
	* @return string
	*/
	public function buttonTextVoted()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['buttontextvoted']) || $option['buttontextvoted'] == "" )
			return __('Voted', 'votes');
		return esc_html($option['buttontextvoted']);
	}

	/**
	* Clear Votes Button Text
	* @return string
	*/
	public function clearVotesText()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['clearvotes']) || $option['clearvotes'] == "" )
			return __('Clear Votes', 'votes');
		return esc_html($option['clearvotes']);
	}

	/**
	* Post Types to show meta box on
	* @return array
	*/
	public function metaEnabled()
	{
		$posttypes = [];
		$types = get_option('simplevotes_display');
		if ( !isset($types['posttypes']) || $types['posttypes'] == "" ) return $posttypes;
		foreach ( $types['posttypes'] as $key => $type ){
			if ( isset($type['postmeta']) && $type['postmeta'] == 'true' ) array_push($posttypes, $key);
		}
		return $posttypes;
	}

	/**
	* Does the button get the vote count
	* @return boolean
	* @since 1.1.1
	*/
	public function includeCountInButton()
	{
		$option = get_option('simplevotes_display');
		return ( isset($option['buttoncount']) && $option['buttoncount'] == "true" ) ? true : false;
	}

	/**
	* Does the button get loading indication?
	* @return boolean
	* @since 1.1.1
	*/
	public function includeLoadingIndicator()
	{
		$option = get_option('simplevotes_display');
		return ( isset($option['loadingindicator']['include']) && $option['loadingindicator']['include'] == "true" ) ? true : false;
	}

	/**
	* Does the button get loading indication on page load?
	* @return boolean
	* @since 1.1.3
	*/
	public function includeLoadingIndicatorPreload()
	{
		$option = get_option('simplevotes_display');
		return ( isset($option['loadingindicator']['include_preload']) && $option['loadingindicator']['include_preload'] == "true" ) ? true : false;
	}

	/**
	* Loading Text
	* @return string
	* @since 1.1.1
	*/
	public function loadingText()
	{
		$option = get_option('simplevotes_display');
		return ( isset($option['loadingindicator']['text']) ) ? esc_html($option['loadingindicator']['text']) : __('Loading', 'votes');
	}

	/**
	* Loading Image
	* @return string
	* @param $state string
	* @uses simplevotes_spinner_url filter, simplevotes_spinner_url_active filter
	* @since 1.1.1
	*/
	public function loadingImage($state = 'inactive')
	{
		$option = get_option('simplevotes_display');
		if ( isset($option['loadingindicator']['include_html']) && $option['loadingindicator']['include_html'] ) return $this->loadingHtml($state);
		if ( !isset($option['loadingindicator']['include_image']) || $option['loadingindicator']['include_image'] !== 'true' ) return false;
		$image_url = Helpers::plugin_url() . '/assets/images/loading.gif';

		if ( $state == 'inactive' ){
			$image_url = apply_filters('simplevotes_spinner_url', $image_url);
			$image_url = apply_filters('votes/button/loading/image_url', $image_url);
			// deprecated filter
			$image = '<img src="' . $image_url . '" class="simplevotes-loading" aria-hidden="true" />';
			$image = '<img src="' . $image_url . '" class="simplevotes-loading" aria-hidden="true" />';
			return $image;
		}

		// active state (some users might want different color for active)

		// deprecated filter
		$image_url = apply_filters('simplevotes_spinner_url_active', $image_url);
		$image_url = apply_filters('votes/button/loading/image_url_active', $image_url);

		$image = '<img src="' . $image_url . '" class="simplevotes-loading" aria-hidden="true" />';
		$image = '<img src="' . $image_url . '" class="simplevotes-loading" aria-hidden="true" />';
		return $image;
	}

	/**
	* Loading indicator type
	* @return boolean
	* @param $state string
	* @since 2.0.2
	*/
	public function loadingIndicatorType($type = 'include_image')
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['loadingindicator'][$type]) || $option['loadingindicator'][$type] !== 'true' ) return false;
		return true;
	}

	/**
	* Loading CSS/Icon
	* @return string
	* @param $state string
	* @uses simplevotes_spinner_html filter, simplevotes_spinner_html_active filter
	* @since 2.0.2
	*/
	public function loadingHtml($state = 'inactive')
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['loadingindicator']['include_html']) || $option['loadingindicator']['include_html'] !== 'true' ) return false;

		if ( $state == 'inactive' )	{
			// Deprecated filter
			$html = apply_filters('simplevotes_spinner_html', '<span class="sf-icon-spinner-wrapper"><i class="sf-icon-spinner"></i></span>');
			$html = apply_filters('votes/button/loading/html', $html);
			return $html;
		}

		// Deprecated filter
		$html = apply_filters('simplevotes_spinner_html_active', '<span class="sf-icon-spinner-wrapper active"><i class="sf-icon-spinner active"></i></span>');
		$html = apply_filters('votes/button/loading/html_active', $html);
		return $html;
	}

	/**
	* Get text to display in lists if no votes are saved
	* @return string
	* @since 1.2
	*/
	public function noVotesText()
	{
		$option = get_option('simplevotes_display');
		return ( isset($option['novotes']) && $option['novotes'] !== "" ) ? $option['novotes'] : __('No Votes', 'votes');
	}

	/**
	* Is cache enabled on the site
	* @return boolean
	* @since 1.3.0
	*/
	public function cacheEnabled()
	{
		$option = get_option('simplevotes_cache_enabled');
		return ( isset($option) && $option == "true" ) ? true : false;
	}

	/**
	* Get the button type
	* @return string
	* @since 2.0.3
	*/
	public function getButtonType()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['buttontype']) || $option['buttontype'] == "" ) return 'custom';
		return $option['buttontype'];
	}

	/**
	* Get the button element type
	* @return string
	* @since 2.1.2
	*/
	public function getButtonHtmlType()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['button_element_type']) || $option['button_element_type'] == "" ) return 'button';
		return $option['button_element_type'];
	}

	/**
	* Preset Buttons
	* @param string $button - which button to return
	* @return array
	* @since 2.0.3
	*/
	public function presetButton($button = 'all')
	{
		$buttons = [
			'vote' => [
				'label' => __('Vote', 'votes'),
				'icon' => apply_filters('votes/button/icon', '<i class="sf-icon-vote"></i>', 'vote'),
				'icon_class' => apply_filters('votes/button/icon-class', 'sf-icon-vote', 'vote'),
				'state_default' => apply_filters('votes/button/text/default', __('Vote', 'votes'), 'vote'),
				'state_active' => apply_filters('votes/button/text/active', __('Voted', 'votes'), 'vote')
			],
			'like' => [
				'label' => __('Like', 'votes'),
				'icon' => apply_filters('votes/button/icon', '<i class="sf-icon-like"></i>', 'like'),
				'icon_class' => apply_filters('votes/button/icon-class', 'sf-icon-like', 'like'),
				'state_default' => apply_filters('votes/button/text/default', __('Like', 'votes'), 'like'),
				'state_active' => apply_filters('votes/button/text/active', __('Liked', 'votes'), 'like')
			],
			'love' => [
				'label' => __('Love', 'votes'),
				'icon' => apply_filters('votes/button/icon', '<i class="sf-icon-love"></i>', 'love'),
				'icon_class' => apply_filters('votes/button/icon-class', 'sf-icon-love', 'love'),
				'state_default' => apply_filters('votes/button/text/default', __('Love', 'votes'), 'love'),
				'state_active' => apply_filters('votes/button/text/active', __('Loved', 'votes'), 'love')
			],
			'bookmark' => [
				'label' => __('Bookmark', 'votes'),
				'icon' => apply_filters('votes/button/icon', '<i class="sf-icon-bookmark"></i>', 'bookmark'),
				'icon_class' => apply_filters('votes/button/icon-class', 'sf-icon-bookmark', 'bookmark'),
				'state_default' => apply_filters('votes/button/text/default', __('Bookmark', 'votes'), 'bookmark'),
				'state_active' => apply_filters('votes/button/text/active', __('Bookmarked', 'votes'), 'bookmark')
			],
			'wishlist' => [
				'label' => __('Wishlist', 'votes'),
				'icon' => apply_filters('votes/button/icon', '<i class="sf-icon-wishlist"></i>', 'wishlist'),
				'icon_class' => apply_filters('votes/button/icon-class', 'sf-icon-wishlist', 'wishlist'),
				'state_default' => apply_filters('votes/button/text/default', __('Add to Wishlist', 'votes'), 'wishlist'),
				'state_active' => apply_filters('votes/button/text/active', __('Added to Wishlist', 'votes'), 'wishlist')
			]
		];
		if ( $button == 'all' ) return $buttons;
		if ( isset($buttons[$button]) ) return $buttons[$button];
		return $buttons['vote'];
	}

	/**
	* Custom Colors
	* @return bool if no custom colors
	* @return array if custom colors
	*/
	public function buttonColors($color = 'background_default')
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['button_colors']) ) $option['button_colors'] = [];
		$option = $option['button_colors'];

		if ( $color == 'custom' )
			return ( !isset($option['custom']) || $option['custom'] !== "true" ) ? false : true;

		if ( $color == 'box_shadow' )
			return ( !isset($option['box_shadow']) || $option['box_shadow'] !== "true" ) ? false : true;

		return ( isset($option[$color]) ) ? $option[$color] : false;
	}

	/**
	* Color Options
	* @return array
	*/
	public function colorOptions($group = 'default')
	{
		$options = [
			'default' => [
				'background_default' => __('Background Color', 'votes'),
				'border_default' => __('Border Color', 'votes'),
				'text_default' => __('Text Color', 'votes'),
				'icon_default' => __('Icon Color', 'votes'),
				'count_default' => __('Count Color', 'votes')
			],
			'active' => [
				'background_active' => __('Background Color', 'votes'),
				'border_active' => __('Border Color', 'votes'),
				'text_active' => __('Text Color', 'votes'),
				'icon_active' => __('Icon Color', 'votes'),
				'count_active' => __('Count Color', 'votes')
			]
		];
		return $options[$group];
	}

	/**
	* Format colors
	*/
	public function formattedButtonOptions()
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['button_colors']) ) $option['button_colors'] = [];
		$option = $option['button_colors'];

		// Button Type
		$button_type = $this->getButtonType();
		if ( $button_type == 'custom' ) {
			$values['button_type'] = 'custom';
		} else {
			$values['button_type'] = $this->presetButton($button_type);
		}

		// Use Custom Colors?
		$values['custom_colors'] = $this->buttonColors('custom');

		// Box shadow
		$values['box_shadow'] = ( isset($option['box_shadow']) && $option['box_shadow'] == 'true' ) ? true : false;

		// Include the count?
		$values['include_count'] = $this->includeCountInButton();

		// Default colors
		foreach( $this->colorOptions('default') as $key => $label ){
			$values['default'][$key] = ( isset($option[$key]) && $option[$key] !== '' ) ? $option[$key] : false;
		}
		// Active colors
		foreach( $this->colorOptions('active') as $key => $label ){
			$values['active'][$key] = ( isset($option[$key]) && $option[$key] !== '' ) ? $option[$key] : false;
		}
		return $values;
	}

	/**
	* List Customization
	*/
	public function listCustomization($setting = 'customize')
	{
		$option = get_option('simplevotes_display');
		if ( !isset($option['listing']['customize']) || $option['listing']['customize'] !== 'true' ) return false;
		if ( $setting == 'all' && isset($option['listing']) ) return $option['listing'];
		if ( $setting == 'customize' ) return true;
		$option = $option['listing'];

		return ( isset($option[$setting]) ) ? $option[$setting] : false;
	}
}