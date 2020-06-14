/*======snippet-Bookmarks button styling=====*/ 
jQuery(document).ready(function($){
				$( "i.bookmark" ).click(function() {
				  $( this ).toggleClass( "highlight" );
				});
});
/*=====Snippet-Signup Form Placeholder & Checkbox======*/
/* write your JavaScript code here */
		jQuery(document).ready( function($){  
          $("#register-page form :input").each(function(index, elem) {
			var eId = $(elem).attr("id");
			var label = null;
			if (eId && (label = $(elem).parents("form").find("label[for="+eId+"]")).length == 1) {
				$(elem).attr("placeholder", $(label).html());
				$(label).remove();
			}
          });
			$(".extended-profile legend").remove();
			$(".extended-profile .field_first-name input").attr("placeholder", "First Name");
			$(".extended-profile .field_last-name input").attr("placeholder", "Last Name");
			$(".extended-profile .field_nickname input").attr("placeholder", "Nickname");
            $(".register-privacy-info").append("<span>Membership includes our newsletter. Adjust your email preferences at any time in your profile.</span>");   
          });
		
		/**************Checkbox**************/
        jQuery(document).ready( function($){ 
			$('#signup_submit').attr('disabled', 'disabled');
			$('.field_accept-policies input[type="checkbox"]').click(function() {
				if ($('#signup_submit').is(':disabled')) {
					$('#signup_submit').removeAttr('disabled');
				} else {
					$('#signup_submit').attr('disabled', 'disabled');
				}
			});
		});
   
   /*==========Snippet:-Course Grids (Learndash, BuddyBoss, Elementor) site styling==========*/
    jQuery(document).ready(function(){
			
			//Add classes to course grid objects based on project state, for easier site styling:
			jQuery(".ld-status.ld-status-progress.ld-primary-background:contains('Start project')").addClass("not-started");
			jQuery(".ld-status.ld-status-progress.ld-primary-background:contains('In Progress')").addClass("in-progress");
	});	

 /*=====Snippets:-Site animations and glow=====*/
    jQuery(document).ready(function(){
			//Remove the tooltip on logo hover (actually, on every image hover):
			jQuery("img").removeAttr("title");
	});	

   //Every time someone changes something on our top-search form, after it finishes updating the page:
		//(See: https://support.searchandfilter.com/forums/topic/select2-orderby-not-displaying) */
		jQuery(document).on("sf:ajaxfinish", ".searchandfilter", function(){

			//Go through and update glow for each dropdown wih an option selected:
			jQuery('.top-search-filter .searchandfilter option.sf-item-0').each(function() {
				if(!jQuery(this).hasClass('sf-option-active')) {
					jQuery(this).parent().addClass('select-highlight');
					console.log('class added');
				}
			});	
			
		});
 