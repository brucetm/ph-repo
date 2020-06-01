=== Votes ===
Contributors: kylephillips
Donate link: http://voteposts.com/
Tags: votes, like, bookmark, vote, likes, bookmarks, favourite, favourites, multisite, wishlist, wish list
Requires at least: 3.8
Requires PHP: 5.4
Tested up to: 5.4
Stable tag: 2.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Votes for any post type. Easily add voting/liking, wishlists, or any other similar functionality using the developer-friendly API.

== Description ==

**Why Votes?**

Votes is designed for end users and theme developers. It provides an easy-to-use API for adding vote button functionality to any post type.

The plugin can provide a way to save votes, likes, bookmarks, or any other similar types of data with its customizable button text.

Visit [voteposts.com](http://voteposts.com) for a full list of available template functions and shortcodes.

**Features**

**Use with Any Post Type** - Enable or disable vote functionality per post type while automatically adding a vote button before and/or after the content. Or, use the included functions to display the button anywhere in your template.

**Available for All Users** – Don't want to hide functionality behind a login? Votes includes an option to save anonymous users' votes by either Session or Cookie. Logged-In users' votes are also saved as user meta

**Designed for Developers** - Votes works great out-of-the-box for beginners, but a full set of template functions unlocks just about any sort of custom functionality developers may need. Votes outputs the minimum amount of markup needed, putting the style and control in your hands.

**GDPR**

As of version 2.2, a setting is provided to help comply with GDPR standards. To enable this setting, visit Settings > Votes > Users, and check the field under "User Cookie Consent." When this setting is enabled, the content saved under the setting displays in a modal window, and the user must agree to the terms you provide before vote cookies can be saved. Note: There is no language provided by default. This should be supplied by a qualified attorney or legal entity. Once the user has approved or denied cookies, that is saved in the "simplevotes" cookie along with the timestamp of approval or denial. If the site is part of a multi-site installation, the setting will carry through to all sites.

If your site already has a cookie compliance solution in place, there are two document-level jQuery events that may be triggered in order to approve or deny cookies in the background.

To approve the use of cookies, trigger the event "votes-user-consent-approved". To deny the use of cookies, trigger the event "votes-user-consent-denied".

**Multisite Compatible** - As of version 1.1.0, Votes is multisite compatible. User votes are saved on a site/blog basis, and may be retrieved and displayed across sites.

For more information visit [voteposts.com](http://voteposts.com).

**Important: Votes requires WordPress version 3.8 or higher, and PHP version 5.4 or higher.**


== Installation ==

1. Upload the votes plugin directory to the wp-content/plugins/ directory
2. Activate the plugin through the Plugins menu in WordPress
3. Visit the plugin settings to configure display options
4. Use the template functions, display settings, or shortcodes to display the vote button, vote counts, and/or user votes.

== Frequently Asked Questions ==

= Does this worked on cached pages? =
Yes, although the buttons may display the incorrect state momentarily. Button states are updated via an AJAX call after page load in order to accommodate cached pages. This may be noticeable on slower servers.

= Is this plugin compatible with Multisite? =
As of version 1.1.0, Votes is compatible with multisite installations. By default, all shortcodes and template functions will pull data from the site being viewed. Specific site IDs may be passed as parameters for more control. See the documentation for more information.


== Screenshots ==

1. Developer-friendly – easily unenqueue styles if you are combining and minifying your own.

2. Enable for anonymous users and save in the session or a browser cookie. Logged-in users' votes are saved in a custom user meta field.

3. Optionally add a modal authentication gate for unauthenticated users

4. Enable and display per post type, or use the functions/shortcodes to manually add to templates.

5. Customize the button markup to fit your theme

6. Or use a predefined button type and customize colors to fit your site's style.

7. Every option is customizable, including the loading state for vote buttons.

8. Customize vote lists


== Changelog ==

= 2.3.2 =
* Security update.

= 2.3.1 =
* Bug Fix: Fixes issue where only one custom field was being output when multiple were specified in the custom list html.
* Security Update: Removes potential security issue where user may vote a non-public post, or a post where votes are not enabled.

= 2.3.0 =
* Security Update: Removes ability for users to manipulate votes belonging to other users. Thanks to Arne van Hoorn for uncovering this issue.

= 2.2.0 =
* Fixes multisite issue where votes were not being retrieved correctly.
* Adds setting to require consenting to cookies before saving votes, in an effort to adhere to GDPR compliance. Note: the modal content that displays when this setting is enabled should be provided by a qualified attorney or legal entity. Should your site already have a cookie consent protocol in place, document-level events are provided for triggering the user preferences.

= 2.1.6 =
* Adds option of redirecting to a page/post by ID if an anonmyous user attempts to vote an item
* Adds shortcode processing to authentication gate modal content
* Bug fix that was causing unexpected errors in content filtered by 'the_content' filter.

= 2.1.5 =
* Bug fix where button HTML filters were not being applied to AJAX/cache enabled sites. The post id is not available as a parameter on button html on cache-enabled sites.
* Adds additional compatibility for logged-in voting on hosts with aggressive server-side caching

= 2.1.4 =
* Post vote counts using the shortcode [vote_count] or the functions get_votes_count/the_votes_count now update without page reload.
* Adds parameter to the [user_votes] shortcode for "No Votes" text. Overrides the plugin setting. [user_votes no_votes="Your Custom Text"]
* Updated code that was breaking in PHP versions less than 5.4. End-of-life PHP version support will be dropped in Votes v3
* Bug fix where total vote count was showing "1" before a user had voted any posts
* Adds additional permalink field under the customized listing visual editor
* Bug fix where nonce was not loading correctly on some sites, resulting in an "Incorrect form field" error
* Adds status filter to function filter parameters

= 2.1.3 =
* Bug fix where authentication gate modal was not appearing correctly due to a Javascript error on sites with the cache option disabled.
* Bug fix where lists were being emptied on page load with the cache option enabled.

= 2.1.2 =
* Added additional filters for the listing wrapper CSS classes and the listing element CSS classes. See the plugin website for details.
* Added plugin setting and filter for customizing the button html element type.
* Added a "Development Mode" setting for logging various data to the browser console in order to help with support and debugging.
* Reverted default post types in votes list to display all post types.
* Updates filters run on authorization gate modal that were conflicting with some themes and plugins.

= 2.1.1 =
* Fixes bug where Votes admin javascript was loading outside the plugin settings area, causing preventing some sites from saving posts.

= 2.1.0 =
* Option added to enable a modal notification for anonymous users. Modal content is editable under Settings > Votes > Users. Anonymous users must be disabled, and the "Require Login & Show Modal" must be checked. The content is also available via a filter: votes/authentication_modal_content. If the plugin css has been disabled and this feature is required, please see the plugin styles for new css classes required for modals to function.
* Button customization options added, including color settings and preset button types. Visit Settings > Votes > Display & Post Types to customize the button. In addition to now having the ability to choose a preset button type, button colors may be specified to better match your theme without editing CSS files.
* Option added to customize the votes list. Options are now included for specifying the HTML element type and custom CSS classes for both the list wrapper element and individua listing elements. Additionally, the listings may be fully customized using a standard WordPress editor field.
* Various filters have been added. See the plugin website for a detailed list of available filters.

= 2.0.2 =
* Option added to use a css/html loading indicator in place of an image. Additional filters added for theme use
* Shortcode option added to the votes list "user_votes" for including the post thumbnail. To include the thumbnail, pass the option include_thumbnails="true". To specify a thumbnail size, pass it in as an option: thumbnail_size="thumbnail"
* Shortcode option added to the votes list "user_votes" for including the post excerpt. To include the excerpt, pass the option include_excerpt="true"
* Filters added for the list thumbnail and list excerpt. See plugin documentation for names and parameters
* Plugin settings redesigned

= 2.0.1 =
* Javascript callback functions have been deprecated in place of events. Deprecated functions will be removed in a later version. Please see the plugin documentation on using the new events
* App namespace renamed to "Votes". Important: any developers extending the plugin core should update any references in PHP namespaces to \Votes\
* Plugin text domain updated to "votes" to follow WordPress requirements
* AJAX actions renamed to remove "simple" prefix
* Bug fix where adding a vote required a page refresh for it to appear in vote lists
* Added API function to get the total count of votes across all posts
* Tested with WordPress 4.8

= 1.2.4 =
* Added option to display vote counts in admin columns on a per-post type basis. Visit Settings > Votes > Display to enable the columns.
* Added filter option to change cookie expiration (thanks to Github user rlaan)

= 1.2.3 =
* Bug fix - post type parameter in shortcode being overwritten by javascript on load

= 1.2.2 =
* Bug fix - incorrect list being displayed when passing a specific user id to get_user_votes_list();

= 1.2.1 =
* Bug fix where [user_vote_count] shortcode always returning 0 in multisite installations.
* Added filter to customize user list output.
* Tested for WordPress 4.3 compatibility

= 1.2.0 =
* Added functionality to display users who have voted a post. Use the shortcode [post_votes] or one of the two new template functions: get_users_who_voted_post or the_users_who_voted_post. View the plugin website for options and usage examples.
* Added shortcode and template functions to display a "Clear Votes" button. Button clears all user votes when clicked.
* Added developer hooks for before and after a post has been voted.
* Added developer Javascript callback functions for after the page has loaded, and after a vote has been submitted.
* Option added to include vote button in generated list (see "Other Notes" tab or plugins website for template functions and shortcode)
* Option added to customize text that displays in lists when the user has no votes (visit settings > votes > display to customize the text)
* Post type(s) parameter added to get_user_votes_count template function and user_vote_count shortcode.
* User vote count now updated dynamically (may require a cache reset if page cache is enabled)
* Bug fix - Invalid posts removed from user votes (trashed/unpublished posts).
* Other various enhancements and minor bug fixes

= 1.1.4 =
* Fixed bug that allowed multiple button submissions before the previous was processed

= 1.1.3 =
* Option added to hide loading indication on page load.

= 1.1.2 =
* Bug fix in plugin settings when deselecting all automatic post type insertions.

= 1.1.1 =
* Optional filtering added to vote list template functions. The functions now accept an array of arguments for fine-tuned vote lists. Visit the documentation for more information.
* An optional "post_type" parameter has been added to the user_votes shortcode. The parameter allows for filtering of the generated list by post types, and will accept a comma separated list of post types
* Option added to include post vote count in the button (view settings > votes > display to enable)
* Button loading state option added (view settings > votes > display to enable)
* Added template function and shortcode for displaying total number of votes by user (see documentation for more options)

= 1.1.0 =
* Votes is now multisite compatible. See documentation for added template function and shortcode parameters.

= 1.0.5 =
* Autoloader bug fix (Thanks to Stefan Oderbolz)
* User List page cache fix

= 1.0.4 =
* User votes list bug fix

= 1.0.3 =
* Additional bug fixes for logged in users

= 1.0.2 =
* Fixed array error bug for logged in users

= 1.0.1 =
* Fixed bug where logged in user's votes were pulling from session/cookie rather than saved user meta
* Tested for 4.2 compatibility

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.2.0 =
* Important: If page cache is enabled on your site, clear the cache immediately after updating.
* Many enhancements and developer/theme features have been added. If page cache is enabled on your site, it may be beneficial to reset the cache. Some generated HTML elements are now output with data attributes that enable dynamic updates. If the cached HTML does not have these data attributes, errors may occur.

= 1.1.3 =
* Option added to hide loading indication on page load. By default, loading indication is turned off. To turn loading indication on, visit settings > votes > display, and select the checkbox labeld "include loading indicator image on page load"

= 1.1.1 =
* Optional filters added to vote list functionality. Visit the documentation page for more information.

= 1.1.0 =
* Votes is now multisite compatible. See documentation for added template function and shortcode parameters.

= 1.0 =
Initial release

== Usage ==

**Vote Button**

The vote button can be added automatically to the content by enabling specific post types in the plugin settings. It may also be added to template files or through the content editor using the included functions or shortcodes. The post id may be left blank in all cases if inside the loop. The site id parameter is optional, for use in multisite installations (defaults to current site).

* **Get function:** `get_votes_button($post_id, $site_id)`
* **Print function:** `the_votes_button($post_id, $site_id)`
* **Shortcode:** `[vote_button post_id="" site_id=""]`

**Vote Count (by Post)**

Total votes for each post are saved as a simple integer. If a user unvotes a post, this count is updated. Anonymous users' votes count towards the total by default, but may be disabled via the plugin settings. The post id may be left blank in all cases if inside the loop.

* **Get function:** `get_votes_count($post_id)`
* **Print function:** `the_votes_count($post_id)`
* **Shortcode:** `[vote_count post_id=""]`

**Vote Count (by User)**
Displays the total number of votes a user has voted. Template functions accept the same filters parameter as the user votes functions.

* **Get function:** `get_user_votes_count($user_id, $site_id, $filters)`
* **Print function:** `the_user_votes_count($user_id, $site_id, $filters)`
* **Shortcode:** `[user_votes user_id="" site_id="" post_types=""]`

**User Votes**

User votes are stored as an array of post ids. Logged-in users' votes are stored as a custom user meta field, while anonymous users' votes are stored in either the session or browser cookie (configurable in the plugin settings). If the user id parameter is omitted, the votes default to the current user. The site id parameter is optional, for use in multisite installations (defaults to current site).

* **Get function (returns array of IDs):** `get_user_votes($user_id, $site_id)`
* **Get function (returns html list):** `get_user_votes_list($user_id, $site_id, $include_links, $filters, $include_button, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false)`
* **Print function (prints an html list):** `the_user_votes_list($user_id, $site_id, $include_links, $filters, $include_button, $include_thumbnails = false, $thumbnail_size = 'thumbnail', $include_excerpt = false)`
* **Shortcode (prints an html list, with the option of omitting links):** `[user_votes user_id="" include_links="true" site_id="" include_buttons="false" post_types="post" include_thumbnails="false" thumbnail_size="thumbnail" include_excerpt="false"]

**List Users Who Have Voted a Post**

Display a list of users who have voted a specific post. If the user id parameter is omitted, the votes default to the current user. The site id parameter is optional, for use in multisite installations (defaults to current site). The get function returns an array of user objects.

* **Get function (returns array of User Objects):** `get_users_who_voted_post($post_id, $site_id)`
* **Print function (prints an html list):** `the_users_who_voted_post($post_id = null, $site_id = null, $separator = 'list', $include_anonymous = true, $anonymous_label = 'Anonymous Users', $anonymous_label_single = 'Anonymous User')`
* **Shortcode (prints an html list):** `[post_votes post_id="" site_id="" separator="list" include_anonymous="true" anonymous_label="Anonymous Users" anonymous_label_single="Anonymous User"]


**Clear Votes Button**

Displays a button that allows users to clear all of their votes.

* **Get function:** `get_clear_votes_button($site_id, $text)`
* **Print function:** `the_clear_votes_button($site_id, $text)`
* **Shortcode:** `[clear_votes_button site_id="" text="Clear Votes"]

**Vote Count (Across all Posts)**
Displays the total number of votes for a given site.

* **Get function:** `get_total_votes_count($site_id)`
* **Print function:** `the_total_votes_count($site_id)`
