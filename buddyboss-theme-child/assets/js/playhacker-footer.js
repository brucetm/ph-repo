/**==============Snippet:- Bookmarks: light box filter ==============*/
  

/* write  JavaScript for light Box on course page */
	jQuery(document).ready( function($){ 
		$(document).on("click",'.single-sfwd-courses #header-bookmark-dropdown-elem',function(){
			if($('.bookmark-dropdown').css('display') == 'block'){
			   $('.bookmark-dropdown').css('display','none');
			}else{
				$('.bookmark-dropdown').css('display','block');
				$('.bookmark-dropdown').css('visibility','visible');
				$('.bookmark-dropdown').css('opacity','1');
			}
		});
    
    });

 //light box loading script for all the categories post on click
		jQuery(document).ready(function($){
			setTimeout(function(){
				  $('.filter-list li button.triger_categories').click();
				//$('.filter-list li button.triger_categories').trigger('click');
				}, 1000);
		});	 
		
/*======Snippet:-Flask tweaks======*/
/* Flask plugin is only enabled under certain conditions: */
			jQuery(document).ready(function(){
				
				//console.log("Flask snippet");

				var windowHeight = jQuery(window).height(); 
            	var windowWidth = jQuery(window).width(); 
        		//console.log("windowHeight= "+windowHeight+" windowWidth= "+windowWidth);
				var blockIt = 0;
				var mobileStatus = "big enough";
				var elementorStatus = "not editing";
				var pageStatus = "not blacklisted";

    			/* If screen is too small (check both dims): */
				if ((windowHeight < 768) || (windowWidth < 768)) {
					++blockIt;
					mobileStatus = "too small";
				}	
				
				/* If inside Elementor editor: */ 
				if (jQuery('body').hasClass("elementor-editor-active")) {
					++blockIt;
					elementorStatus = "in editor";
				}						
				
				/* Get current page name and window size: */
				var currPostTitle = jQuery(document).attr('title');
        		//console.log(currPostTitle);

				/* Make an array of no-flask pages: */
				/* NOTE: BuddyBoss hard codes endashes, which are not hyphens, so I copy pasted these from wikipedia: */
				var disallowedPages = jQuery.inArray( currPostTitle, [ "Create an Account – playhacker!", "Activate Your Account – playhacker!", "coming soon – playhacker!" ] );

				/* If an excluded page: */
				if ( disallowedPages > -1 ) {
					++blockIt;
					pageStatus = "disallowed page";
				}

				if (blockIt > 0) {
					jQuery("#flask").css("visibility", "hidden");
				}
				
				//console.log("Flask status for "+currPostTitle+" is "+mobileStatus+", "+elementorStatus+", "+pageStatus+", disallowed="+disallowedPages);

			});
