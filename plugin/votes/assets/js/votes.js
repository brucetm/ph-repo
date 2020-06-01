/**
* Utility Methods
*/
var Votes = Votes || {};

Votes.Utilities = function()
{
	var plugin = this;
	var $ = jQuery;

	/*
	* Check if an item is voted
	* @param int post_id
	* @param object votes for a specific site
	*/
	plugin.isVote = function(post_id, site_votes)
	{
		var status = false;
		$.each(site_votes, function(i, v){
			if ( v.post_id === parseInt(post_id) ) status = true;
			if ( parseInt(v.post_id) === post_id ) status = true;
		});
		return status;
	}

	/**
	* Get the length of an
	*/
	plugin.objectLength = function(object)
	{
		var size = 0, key;
		for (key in object) {
			if (object.hasOwnProperty(key)) size++;
		}
		return size;
	}

	/*
	* Get Site index from All Votes
	*/
	plugin.siteIndex = function(siteid)
	{
		for ( var i = 0; i < Votes.userVotes.length; i++ ){
			if ( Votes.userVotes[i].site_id !== parseInt(siteid) ) continue;
			return i;
		}
	}

	/*
	* Get a specific thumbnail size
	*/
	plugin.getThumbnail = function(vote, size)
	{
		var thumbnails = vote.thumbnails;
		if ( typeof thumbnails === 'undefined' || thumbnails.length == 0 ) return false;
		var thumbnail_url = thumbnails[size];
		if ( typeof thumbnail_url === 'undefined' ) return false;
		if ( !thumbnail_url ) return false;
		return thumbnail_url;
	}
}
/**
* Formatting functionality
*/
var Votes = Votes || {};

Votes.Formatter = function()
{
	var plugin = this;
	var $ = jQuery;

	/*
	*  Add Vote Count to a button
	*/
	plugin.addVoteCount = function(html, count)
	{
		if ( !Votes.jsData.button_options.include_count ) return html;
		if ( count <= 0 ) count = 0;
		html += ' <span class="simplevote-button-count">' + count + '</span>';
		return html;
	}

	/**
	* Decrement all counts by one
	*/
	plugin.decrementAllCounts = function(){
		var buttons = $('.simplevote-button.active.has-count');
		for ( var i = 0; i < buttons.length; i++ ){
			var button = $(buttons)[i];
			var count_display = $(button).find('.simplevote-button-count');
			var new_count = $(count_display).text() - 1;
			$(button).attr('data-votecount', new_count);
		}
	}
}
/**
* Builds the vote button html
*/
var Votes = Votes || {};

Votes.ButtonOptionsFormatter = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.options = Votes.jsData.button_options;
	plugin.formatter = new Votes.Formatter;

	/**
	* Format the button according to plugin options
	*/
	plugin.format = function(button, isVote)
	{
		if ( plugin.options.custom_colors ) plugin.colors(button, isVote);
		plugin.html(button, isVote);
	}

	/**
	* Set the HTML content for the button
	*/
	plugin.html = function(button, isVote)
	{
		var count = $(button).attr('data-votecount');
		var options = plugin.options.button_type;
		var html = '';
		if ( plugin.options.button_type === 'custom' ){
			if ( isVote ) $(button).html(plugin.formatter.addVoteCount(Votes.jsData.voted, count));
			if ( !isVote ) $(button).html(plugin.formatter.addVoteCount(Votes.jsData.vote, count));
			plugin.applyIconColor(button, isVote);
			plugin.applyCountColor(button, isVote);
			return;
		}
		if ( isVote ){
			html += '<i class="' + options.icon_class + '"></i> ';
			html += options.state_active;
			$(button).html(plugin.formatter.addVoteCount(html, count));
			return;
		}
		html += '<i class="' + options.icon_class + '"></i> ';
		html += options.state_default;
		$(button).html(plugin.formatter.addVoteCount(html, count));
		plugin.applyIconColor(button, isVote);
		plugin.applyCountColor(button, isVote);
	}

	/**
	* Apply custom colors to the button if the option is selected
	*/
	plugin.colors = function(button, isVote)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isVote ){
			var options = plugin.options.active;
			if ( options.background_active ) $(button).css('background-color', options.background_active);
			if ( options.border_active ) $(button).css('border-color', options.border_active);
			if ( options.text_active ) $(button).css('color', options.text_active);
			return;
		}
		var options = plugin.options.default;
		if ( options.background_default ) $(button).css('background-color', options.background_default);
		if ( options.border_default ) $(button).css('border-color', options.border_default);
		if ( options.text_default ) $(button).css('color', options.text_default);
		plugin.boxShadow(button);
	}

	/**
	* Remove the box shadow from the button if the option is selected
	*/
	plugin.boxShadow = function(button)
	{
		if ( plugin.options.box_shadow ) return;
		$(button).css('box-shadow', 'none');
		$(button).css('-webkit-box-shadow', 'none');
		$(button).css('-moz-box-shadow', 'none');
	}

	/**
	* Apply custom colors to the icon if the option is selected
	*/
	plugin.applyIconColor = function(button, isVote)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isVote && plugin.options.active.icon_active ) {
			$(button).find('i').css('color', plugin.options.active.icon_active);
		}
		if ( !isVote && plugin.options.default.icon_default ) {
			$(button).find('i').css('color', plugin.options.default.icon_default);
		}
	}

	/**
	* Apply custom colors to the vote count if the option is selected
	*/
	plugin.applyCountColor = function(button, isVote)
	{
		if ( !plugin.options.custom_colors ) return;
		if ( isVote && plugin.options.active.count_active ) {
			$(button).find(Votes.selectors.count).css('color', plugin.options.active.count_active);
			return;
		}
		if ( !isVote && plugin.options.default.count_default ) {
			$(button).find(Votes.selectors.count).css('color', plugin.options.default.count_default);
		}
	}
}
/**
* Gets the user votes
*/
var Votes = Votes || {};

Votes.UserVotes = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.initialLoad = false;

	plugin.bindEvents = function()
	{
		$(window).on('load', function(){
			plugin.initialLoad = true;
			plugin.getVotes();
		});
	}

	/**
	* Get the user votes
	*/
	plugin.getVotes = function()
	{
		$.ajax({
			url: Votes.jsData.ajaxurl,
			type: 'POST',
			datatype: 'json',
			data: {
				action : Votes.formActions.votesarray
			},
			success: function(data){
				if ( Votes.jsData.dev_mode ) {
					console.log('The current user votes were successfully loaded.');
					console.log(data);
				}
				Votes.userVotes = data.votes;
				$(document).trigger('votes-user-votes-loaded', [data.votes, plugin.initialLoad]);
				$(document).trigger('votes-update-all-buttons');

				// Deprecated Callback
				if ( plugin.initialLoad ) votes_after_initial_load(Votes.userVotes);
			},
			error: function(data){
				if ( !Votes.jsData.dev_mode ) return;
				console.log('The was an error loading the user votes.');
				console.log(data);
			}
		});
	}

	return plugin.bindEvents();
}
/**
* Clears all votes for the user
*
* Events:
* votes-cleared: The user's votes have been cleared. Params: clear button
*/
var Votes = Votes || {};

Votes.Clear = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeButton; // The active "clear votes" button
	plugin.utilities = new Votes.Utilities;
	plugin.formatter = new Votes.Formatter;

	plugin.bindEvents = function()
	{
		$(document).on('click', Votes.selectors.clear_button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.clearVotes();
		});
		$(document).on('votes-updated-single', function(){
			plugin.updateClearButtons();
		});
		$(document).on('votes-user-votes-loaded', function(){
			plugin.updateClearButtons();
		});
	}

	/*
	* Submit an AJAX request to clear all of the user's votes
	*/
	plugin.clearVotes = function()
	{
		plugin.loading(true);
		var site_id = $(plugin.activeButton).attr('data-siteid');
		$.ajax({
			url: Votes.jsData.ajaxurl,
			type: 'post',
			datatype: 'json',
			data: {
				action : Votes.formActions.clearall,
				siteid : site_id
			},
			success : function(data){
				if ( Votes.jsData.dev_mode ){
					console.log('Votes list successfully cleared.');
					console.log(data);
				}
				Votes.userVotes = data.votes;
				plugin.formatter.decrementAllCounts();
				plugin.loading(false);
				plugin.clearSiteVotes(site_id);
				$(document).trigger('votes-cleared', [plugin.activeButton, data.old_votes]);
				$(document).trigger('votes-update-all-buttons');
			},
			error : function(data){
				if ( !Votes.jsData.dev_mode ) return;
				console.log('There was an error clearing the votes list.');
				console.log(data);
			}
		});
	}

	/**
	* Toggle the button loading state
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$(plugin.activeButton).addClass(Votes.cssClasses.loading);
			$(plugin.activeButton).attr('disabled', 'disabled');
			return;
		}
		$(plugin.activeButton).removeClass(Votes.cssClasses.loading);
	}

	/*
	* Update disabled status for clear buttons
	*/
	plugin.updateClearButtons = function()
	{
		var button;
		var siteid;
		for ( var i = 0; i < $(Votes.selectors.clear_button).length; i++ ){
			button = $(Votes.selectors.clear_button)[i];
			siteid = $(button).attr('data-siteid');
			for ( var c = 0; c < Votes.userVotes.length; c++ ){
				if ( Votes.userVotes[c].site_id !== parseInt(siteid) ) continue;
				if ( plugin.utilities.objectLength(Votes.userVotes[c].posts) > 0 ) {
					$(button).attr('disabled', false);
					continue;
				}
				$(button).attr('disabled', 'disabled');
			}
		}
	}

	/**
	* Clear out votes for this site id (fix for cookie-enabled sites)
	*/
	plugin.clearSiteVotes = function(site_id)
	{
		$.each(Votes.userVotes, function(i, v){
			if ( this.site_id !== parseInt(site_id) ) return;
			Votes.userVotes[i].posts = {};
		});
	}

	return plugin.bindEvents();
}
/**
* Votes List functionality
*/
var Votes = Votes || {};

Votes.Lists = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Votes.Utilities;
	plugin.buttonFormatter = new Votes.ButtonOptionsFormatter;

	plugin.bindEvents = function()
	{
		$(document).on('votes-update-all-lists', function(){
			plugin.updateAllLists();
		});
		$(document).on('votes-updated-single', function(){
			plugin.updateAllLists();
		});
		$(document).on('votes-cleared', function(){
			plugin.updateAllLists();
		});
		$(document).on('votes-user-votes-loaded', function(){
			plugin.updateAllLists();
		});
	}

	/**
	* Loop through all the votes lists
	*/
	plugin.updateAllLists = function()
	{
		if ( typeof Votes.userVotes === 'undefined' ) return;
		for ( var i = 0; i < Votes.userVotes.length; i++ ){
			var lists = $(Votes.selectors.list + '[data-siteid="' + Votes.userVotes[i].site_id + '"]');
			for ( var c = 0; c < $(lists).length; c++ ){
				var list = $(lists)[c];
				plugin.updateSingleList(list)
			}
		}
	}

	/**
	* Update a specific user list
	*/
	plugin.updateSingleList = function(list)
	{
		var user_id = $(list).attr('data-userid');
		var site_id = $(list).attr('data-siteid');
		var include_links = $(list).attr('data-includelinks');
		var include_buttons = $(list).attr('data-includebuttons');
		var include_thumbnails = $(list).attr('data-includethumbnails');
		var thumbnail_size = $(list).attr('data-thumbnailsize');
		var include_excerpt = $(list).attr('data-includeexcerpts');
		var post_types = $(list).attr('data-posttypes');
		var no_votes = $(list).attr('data-novotestext');

		$.ajax({
			url: Votes.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action : Votes.formActions.votelist,
				userid : user_id,
				siteid : site_id,
				include_links : include_links,
				include_buttons : include_buttons,
				include_thumbnails : include_thumbnails,
				thumbnail_size : thumbnail_size,
				include_excerpt : include_excerpt,
				no_votes : no_votes,
				post_types : post_types
			},
			success : function(data){
				if ( Votes.jsData.dev_mode ){
					console.log('Votes list successfully retrieved.');
					console.log($(list));
					console.log(data);
				}
				var newlist = $(data.list);
				$(list).replaceWith(newlist);
				plugin.removeButtonLoading(newlist);
				$(document).trigger('votes-list-updated', [newlist]);
			},
			error : function(data){
				if ( !Votes.jsData.dev_mode ) return;
				console.log('There was an error updating the list.');
				console.log(list);
				console.log(data);
			}
		});
	}

	/**
	* Remove loading state from buttons in the list
	*/
	plugin.removeButtonLoading = function(list)
	{
		var buttons = $(list).find(Votes.selectors.button);
		$.each(buttons, function(){
			plugin.buttonFormatter.format($(this), false);
			$(this).removeClass(Votes.cssClasses.active);
			$(this).removeClass(Votes.cssClasses.loading);
		});
	}

	/**
	* Remove unvoted items from the list
	*/
	plugin.removeInvalidListItems = function(list, votes)
	{
		var listitems = $(list).find('li[data-postid]');
		$.each(listitems, function(i, v){
			var postid = $(this).attr('data-postid');
			if ( !plugin.utilities.isVote(postid, votes) ) $(this).remove();
		});
	}

	return plugin.bindEvents();
}
/**
* Vote Buttons
* Votes/Unvotes a specific post
*
* Events:
* votes-updated-single: A user's vote has been updated. Params: votes, post_id, site_id, status
*/
var Votes = Votes || {};

Votes.Button = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.activeButton; // The clicked button
	plugin.allButtons; // All vote buttons for the current post
	plugin.authenticated = true;

	plugin.formatter = new Votes.Formatter;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('click', Votes.selectors.button, function(e){
			e.preventDefault();
			plugin.activeButton = $(this);
			plugin.setAllButtons();
			plugin.submitVote();
		});
	}

	/**
	* Set all buttons
	*/
	plugin.setAllButtons = function()
	{
		var post_id = $(plugin.activeButton).attr('data-postid');
		plugin.allButtons = $('button[data-postid="' + post_id + '"]');
	}

	/**
	* Set the Post Data
	*/
	plugin.setData = function()
	{
		plugin.data.post_id = $(plugin.activeButton).attr('data-postid');
		plugin.data.site_id = $(plugin.activeButton).attr('data-siteid');
		plugin.data.status = ( $(plugin.activeButton).hasClass('active') ) ? 'inactive' : 'active';
		var consentProvided = $(plugin.activeButton).attr('data-user-consent-accepted');
		plugin.data.user_consent_accepted = ( typeof consentProvided !== 'undefined' && consentProvided !== '' ) ? true : false;
	}

	/**
	* Submit the button
	*/
	plugin.submitVote = function()
	{
		plugin.loading(true);
		plugin.setData();
		var formData = {
			action : Votes.formActions.vote,
			postid : plugin.data.post_id,
			siteid : plugin.data.site_id,
			status : plugin.data.status,
			user_consent_accepted : plugin.data.user_consent_accepted
		}
		$.ajax({
			url: Votes.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: formData,
			success: function(data){
				if ( Votes.jsData.dev_mode ) {
					console.log('The vote was successfully saved.');
					console.log(data);
				}
				if ( data.status === 'unauthenticated' ){
					Votes.authenticated = false;
					plugin.loading(false);
					plugin.data.status = 'inactive';
					$(document).trigger('votes-update-all-buttons');
					$(document).trigger('votes-require-authentication', [plugin.data]);
					return;
				}
				if ( data.status === 'consent_required' ){
					plugin.loading(false);
					$(document).trigger('votes-require-consent', [data, plugin.data, plugin.activeButton]);
					return;
				}
				Votes.userVotes = data.votes;
				plugin.loading(false);
				plugin.resetButtons();
				$(document).trigger('votes-updated-single', [data.votes, plugin.data.post_id, plugin.data.site_id, plugin.data.status]);
				$(document).trigger('votes-update-all-buttons');

				// Deprecated callback
				votes_after_button_submit(data.votes, plugin.data.post_id, plugin.data.site_id, plugin.data.status);
			},
			error: function(data){
				if ( !Votes.jsData.dev_mode ) return;
				console.log('There was an error saving the vote.');
				console.log(data);
			}
		});
	}

	/*
	* Set the output html
	*/
	plugin.resetButtons = function()
	{
		var vote_count = parseInt($(plugin.activeButton).attr('data-votecount'));

		$.each(plugin.allButtons, function(){
			if ( plugin.data.status === 'inactive' ) {
				if ( vote_count <= 0 ) vote_count = 1;
				$(this).removeClass(Votes.cssClasses.active);
				$(this).attr('data-votecount', vote_count - 1);
				$(this).find(Votes.selectors.count).text(vote_count - 1);
				return;
			}
			$(this).addClass(Votes.cssClasses.active);
			$(this).attr('data-votecount', vote_count + 1);
			$(this).find(Votes.selectors.count).text(vote_count + 1);
		});
	}

	/*
	* Toggle loading on the button
	*/
	plugin.loading = function(loading)
	{
		if ( loading ){
			$.each(plugin.allButtons, function(){
				$(this).attr('disabled', 'disabled');
				$(this).addClass(Votes.cssClasses.loading);
				$(this).html(plugin.addLoadingIndication());
			});
			return;
		}
		$.each(plugin.allButtons, function(){
			$(this).attr('disabled', false);
			$(this).removeClass(Votes.cssClasses.loading);
		});
	}

	/*
	* Add loading indication to button
	*/
	plugin.addLoadingIndication = function(html)
	{
		if ( Votes.jsData.indicate_loading !== '1' ) return html;
		if ( plugin.data.status === 'active' ) return Votes.jsData.loading_text + Votes.jsData.loading_image_active;
		return Votes.jsData.loading_text + Votes.jsData.loading_image;
	}

	return plugin.bindEvents();
}
/**
* Updates Vote Buttons as Needed
*/
var Votes = Votes || {};

Votes.ButtonUpdater = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.utilities = new Votes.Utilities;
	plugin.formatter = new Votes.Formatter;
	plugin.buttonFormatter = new Votes.ButtonOptionsFormatter;

	plugin.activeButton;
	plugin.data = {};

	plugin.bindEvents = function()
	{
		$(document).on('votes-update-all-buttons', function(){
			plugin.updateAllButtons();
		});
		$(document).on('votes-list-updated', function(event, list){
			plugin.updateAllButtons(list);
		});
	}

	/*
	* Update all votes buttons to match the user votes
	* @param list object (optionally updates button in list)
	*/
	plugin.updateAllButtons = function(list)
	{
		if ( typeof Votes.userVotes === 'undefined' ) return;
		var buttons = ( typeof list === undefined && list !== '' )
			? $(list).find(Votes.selectors.button)
			: $(Votes.selectors.button);

		for ( var i = 0; i < $(buttons).length; i++ ){
			plugin.activeButton = $(buttons)[i];
			if ( Votes.authenticated ) plugin.setButtonData();

			if ( Votes.authenticated && plugin.utilities.isVote( plugin.data.postid, plugin.data.site_votes ) ){
				plugin.buttonFormatter.format($(plugin.activeButton), true);
				$(plugin.activeButton).addClass(Votes.cssClasses.active);
				$(plugin.activeButton).removeClass(Votes.cssClasses.loading);
				$(plugin.activeButton).find(Votes.selectors.count).text(plugin.data.vote_count);
				continue;
			}

			plugin.buttonFormatter.format($(plugin.activeButton), false);
			$(plugin.activeButton).removeClass(Votes.cssClasses.active);
			$(plugin.activeButton).removeClass(Votes.cssClasses.loading);
			$(plugin.activeButton).find(Votes.selectors.count).text(plugin.data.vote_count);
		}
	}


	/**
	* Set the button data
	*/
	plugin.setButtonData = function()
	{
		plugin.data.postid = $(plugin.activeButton).attr('data-postid');
		plugin.data.siteid = $(plugin.activeButton).attr('data-siteid');
		plugin.data.vote_count = $(plugin.activeButton).attr('data-votecount');
		plugin.data.site_index = plugin.utilities.siteIndex(plugin.data.siteid);
		plugin.data.site_votes = Votes.userVotes[plugin.data.site_index].posts;
		if ( plugin.data.vote_count <= 0 ) plugin.data.vote_count = 0;
	}

	return plugin.bindEvents();
}
/**
* Total User Votes Count Updates
*/
var Votes = Votes || {};

Votes.TotalCount = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('votes-updated-single', function(){
			plugin.updateTotal();
		});
		$(document).on('votes-cleared', function(){
			plugin.updateTotal();
		});
		$(document).on('votes-user-votes-loaded', function(){
			plugin.updateTotal();
		});
	}

	/*
	* Update Total Number of Votes
	*/
	plugin.updateTotal = function()
	{
		// Loop through all the total vote elements
		for ( var i = 0; i < $(Votes.selectors.total_votes).length; i++ ){
			var item = $(Votes.selectors.total_votes)[i];
			var siteid = parseInt($(item).attr('data-siteid'));
			var posttypes = $(item).attr('data-posttypes');
			var posttypes_array = posttypes.split(','); // Multiple Post Type Support
			var count = 0;

			// Loop through all sites in votes
			for ( var c = 0; c < Votes.userVotes.length; c++ ){
				var site_votes = Votes.userVotes[c];
				if ( site_votes.site_id !== siteid ) continue;
				$.each(site_votes.posts, function(){
					if ( $(item).attr('data-posttypes') === 'all' ){
						count++;
						return;
					}
					if ( $.inArray(this.post_type, posttypes_array) !== -1 ) count++;
				});
			}
			$(item).text(count);
		}
	}

	return plugin.bindEvents();
}
/**
* Updates the count of votes for a post
*/
var Votes = Votes || {};

Votes.PostVoteCount = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('votes-updated-single', function(event, votes, post_id, site_id, status){
			if ( status === 'active' ) return plugin.updateCounts();
			plugin.decrementSingle(post_id, site_id);
		});
		$(document).on('votes-cleared', function(event, button, old_votes){
			plugin.updateCounts(old_votes, true);
		});
	}

	/*
	* Update Total Number of Votes
	*/
	plugin.updateCounts = function(votes, decrement)
	{
		if ( typeof votes === 'undefined' || votes === '' ) votes = Votes.userVotes;
		if ( typeof decrement === 'undefined' || decrement === '' ) decrement = false;

		// Loop through all the total vote elements
		for ( var i = 0; i < $('[' + Votes.selectors.post_vote_count + ']').length; i++ ){

			var item = $('[' + Votes.selectors.post_vote_count + ']')[i];
			var postid = parseInt($(item).attr(Votes.selectors.post_vote_count));
			var siteid = $(item).attr('data-siteid');
			if ( siteid === '' ) siteid = '1';

			// Loop through all sites in votes
			for ( var c = 0; c < votes.length; c++ ){
				var site_votes = votes[c];
				if ( site_votes.site_id !== parseInt(siteid) ) continue;
				$.each(site_votes.posts, function(){

					if ( this.post_id === postid ){
						if ( decrement ){
							var count = parseInt(this.total) - 1;
							$(item).text(count);
							return;
						}
						$(item).text(this.total);
					}
				});
			}
		}
	}

	/**
	* Decrement a single post total
	*/
	plugin.decrementSingle = function(post_id, site_id)
	{
		for ( var i = 0; i < $('[' + Votes.selectors.post_vote_count + ']').length; i++ ){
			var item = $('[' + Votes.selectors.post_vote_count + ']')[i];
			var item_post_id = $(item).attr(Votes.selectors.post_vote_count);
			var item_site_id = $(item).attr('data-siteid');
			if ( item_site_id === '' ) item_site_id = '1';
			if ( item_site_id !== site_id ) continue;
			if ( item_post_id !== post_id ) continue;
			var count = parseInt($(item).text()) - 1;
			$(item).text(count);
		}
	}

	return plugin.bindEvents();
}
/**
* Votes Require Authentication
*/
var Votes = Votes || {};

Votes.RequireAuthentication = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.bindEvents = function()
	{
		$(document).on('votes-require-authentication', function(){
			if ( Votes.jsData.dev_mode ){
				console.log('Unauthenticated user was prevented from voting.');
			}
			if ( Votes.jsData.authentication_redirect ){
				plugin.redirect();
				return;
			}
			plugin.openModal();
		});
		$(document).on('click', '.simplevotes-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[' + Votes.selectors.close_modals + ']', function(e){
			e.preventDefault();
			plugin.closeModal();
		});
	}

	/**
	* Redirect to a page
	*/
	plugin.redirect = function()
	{
		window.location = Votes.jsData.authentication_redirect_url;
	}

	/**
	* Open the Modal
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Votes.selectors.modals + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Votes.selectors.modals + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplevotes-modal-backdrop" ' + Votes.selectors.modals + '></div>';
		html += '<div class="simplevotes-modal-content" ' + Votes.selectors.modals + '>';
		html += '<div class="simplevotes-modal-content-body">';
		html += Votes.jsData.authentication_modal_content;
		html += '</div><!-- .simplevotes-modal-content-body -->';
		html += '</div><!-- .simplevotes-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Moda
	*/
	plugin.closeModal = function()
	{
		$('[' + Votes.selectors.modals + ']').removeClass('active');
		$(document).trigger('votes-modal-closed');
	}

	return plugin.bindEvents();
}
/**
* Votes Require Consent Modal Agreement
*/
var Votes = Votes || {};

Votes.RequireConsent = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.consentData;
	plugin.postData;
	plugin.activeButton;

	plugin.bindEvents = function()
	{
		$(document).on('votes-require-consent', function(event, consent_data, post_data, active_button){
			plugin.consentData = consent_data;
			plugin.postData = post_data;
			plugin.activeButton = active_button;
			plugin.openModal();
		});
		$(document).on('votes-user-consent-approved', function(e, button){
			if ( typeof button !== 'undefined' ){
				$(plugin.activeButton).attr('data-user-consent-accepted', 'true');
				$(plugin.activeButton).click();
				plugin.closeModal();
				return;
			}
			plugin.setConsent(true);
		});
		$(document).on('votes-user-consent-denied', function(){
			plugin.setConsent(false);
		});
		$(document).on('click', '.simplevotes-modal-backdrop', function(e){
			plugin.closeModal();
		});
		$(document).on('click', '[data-votes-consent-deny]', function(e){
			e.preventDefault();
			plugin.closeModal();
			$(document).trigger('votes-user-consent-denied');
		});
		$(document).on('click', '[data-votes-consent-accept]', function(e){
			e.preventDefault();
			$(document).trigger('votes-user-consent-approved', [$(this)]);
		});
	}

	/**
	* Open the Modal
	*/
	plugin.openModal = function()
	{
		plugin.buildModal();
		setTimeout(function(){
			$('[' + Votes.selectors.consentModal + ']').addClass('active');
		}, 10);
	}

	/**
	* Build the Modal
	*/
	plugin.buildModal = function()
	{
		var modal = $('[' + Votes.selectors.consentModal + ']');
		if ( modal.length > 0 ) return;
		var html = '<div class="simplevotes-modal-backdrop" ' + Votes.selectors.consentModal + '></div>';
		html += '<div class="simplevotes-modal-content" ' + Votes.selectors.consentModal + '>';
		html += '<div class="simplevotes-modal-content-body no-padding">';
		html += '<div class="simplevotes-modal-content-interior">';
		html += plugin.consentData.message;
		html += '</div>';
		html += '<div class="simplevotes-modal-content-footer">'
		html += '<button class="simplevotes-button-consent-deny" data-votes-consent-deny>' + plugin.consentData.deny_text + '</button>';
		html += '<button class="simplevotes-button-consent-accept" data-votes-consent-accept>' + plugin.consentData.accept_text + '</button>';
		html += '</div><!-- .simplevotes-modal-footer -->';
		html += '</div><!-- .simplevotes-modal-content-body -->';
		html += '</div><!-- .simplevotes-modal-content -->';
		$('body').prepend(html);
	}

	/**
	* Close the Modal
	*/
	plugin.closeModal = function()
	{
		$('[' + Votes.selectors.consentModal + ']').removeClass('active');
	}

	/**
	* Submit a manual deny/consent
	*/
	plugin.setConsent = function(consent)
	{
		$.ajax({
			url: Votes.jsData.ajaxurl,
			type: 'post',
			dataType: 'json',
			data: {
				action : Votes.formActions.cookieConsent,
				consent : consent
			}
		});
	}

	return plugin.bindEvents();
}
/**
* Primary Votes Initialization
* @package Votes
* @author Kyle Phillips - https://github.com/kylephillips/votes
*
* Events:
* votes-nonce-generated: The nonce has been generated
* votes-updated-single: A user's vote has been updated. Params: votes, post_id, site_id, status
* votes-cleared: The user's votes have been cleared. Params: clear button
* votes-user-votes-loaded: The user's votes have been loaded. Params: intialLoad (bool)
* votes-require-authentication: An unauthenticated user has attempted to vote a post (The Require Login & Show Modal setting is checked)
*/

/**
* Callback Functions for use in themes (deprecated in v2 in favor of events)
*/
function votes_after_button_submit(votes, post_id, site_id, status){}
function votes_after_initial_load(votes){}

jQuery(document).ready(function(){
	new Votes.Factory;
});

var Votes = Votes || {};

/**
* DOM Selectors Used by the Plugin
*/
Votes.selectors = {
	button : '.simplevote-button', // Vote Buttons
	list : '.votes-list', // Vote Lists
	clear_button : '.simplevotes-clear', // Clear Button
	total_votes : '.simplevotes-user-count', // Total Votes (from the_user_votes_count)
	modals : 'data-votes-modal', // Modals
	consentModal : 'data-votes-consent-modal', // Consent Modal
	close_modals : 'data-votes-modal-close', // Link/Button to close the modals
	count : '.simplevote-button-count', // The count inside the votes button
	post_vote_count : 'data-votes-post-count-id' // The total number of times a post has been voted
}

/**
* CSS Classes Used by the Plugin
*/
Votes.cssClasses = {
	loading : 'loading', // Loading State
	active : 'active', // Active State
}

/**
* Localized JS Data Used by the Plugin
*/
Votes.jsData = {
	ajaxurl : votes_data.ajaxurl, // The WP AJAX URL
	vote : votes_data.vote, // Active Button Text/HTML
	voted : votes_data.voted, // Inactive Button Text
	include_count : votes_data.includecount, // Whether to include the count in buttons
	indicate_loading : votes_data.indicate_loading, // Whether to include loading indication in buttons
	loading_text : votes_data.loading_text, // Loading indication text
	loading_image_active : votes_data.loading_image_active, // Loading spinner url in active button
	loading_image : votes_data.loading_image, // Loading spinner url in inactive button
	cache_enabled : votes_data.cache_enabled, // Is cache enabled on the site
	authentication_modal_content : votes_data.authentication_modal_content, // Content to display in authentication gate modal
	authentication_redirect : votes_data.authentication_redirect, // Whether to redirect unauthenticated users to a page
	authentication_redirect_url : votes_data.authentication_redirect_url, // URL to redirect to
	button_options : votes_data.button_options, // Custom button options
	dev_mode : votes_data.dev_mode, // Is Dev mode enabled
	logged_in : votes_data.logged_in, // Is the user logged in
	user_id : votes_data.user_id // The current user ID (0 if logged out)
}

/**
* The user's votes
* @var object
*/
Votes.userVotes = null;

/**
* Is the user authenticated
* @var object
*/
Votes.authenticated = true;

/**
* WP Form Actions Used by the Plugin
*/
Votes.formActions = {
	votesarray : 'votes_array',
	vote : 'votes_vote',
	clearall : 'votes_clear',
	votelist : 'votes_list',
	cookieConsent : 'votes_cookie_consent'
}

/**
* Primary factory class
*/
Votes.Factory = function()
{
	var plugin = this;
	var $ = jQuery;

	plugin.build = function()
	{
		new Votes.UserVotes;
		new Votes.Lists;
		new Votes.Clear;
		new Votes.Button;
		new Votes.ButtonUpdater;
		new Votes.TotalCount;
		new Votes.PostVoteCount;
		new Votes.RequireAuthentication;
		new Votes.RequireConsent;
	}

	return plugin.build();
}