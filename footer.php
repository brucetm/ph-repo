<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BuddyBoss_Theme
 */

?>

<?php do_action( THEME_HOOK_PREFIX . 'end_content' ); ?>

</div><!-- .bb-grid -->
</div><!-- .container -->
</div><!-- #content -->

<?php do_action( THEME_HOOK_PREFIX . 'after_content' ); ?>

<?php do_action( THEME_HOOK_PREFIX . 'before_footer' ); ?>
<?php do_action( THEME_HOOK_PREFIX . 'footer' ); ?>
<?php do_action( THEME_HOOK_PREFIX . 'after_footer' ); ?>

</div><!-- #page -->

<?php do_action( THEME_HOOK_PREFIX . 'after_page' ); ?>

<?php wp_footer(); ?>
<script>
/*========Snippets:- Bookmarks: light box filter=========*/
jQuery(document).ready( function($){ 
		   var count = 0;
		   $(".filter-list li button").click(function(){
			   if(count>0){
			   if($('.single-sfwd-courses .bookmark-dropdown').css('display') == 'block'){
			     $('.single-sfwd-courses .bookmark-dropdown').css('display','none');
			   }else{
				 $('.single-sfwd-courses .bookmark-dropdown').css('display','block');
			   }
		      }
			   count++;
			  var filter_val= $(this).val();
			   $(".filter-list li button").removeClass('active');
			   $(this).addClass('active');
			  jQuery.ajax({
				 type : "post",
				 url : "<?php echo site_url().'/wp-admin/admin-ajax.php' ?>",
				 data : {action: "my_favorite_list", filterdata : filter_val, nonce: "<?php echo wp_create_nonce("my_user_fav_nonce"); ?>"},
				 success: function(response) {
					$('.fav-light-box').html(response);
				 }
			  })   
		   });
	   });
</script> 

<script>
	/**==================Snippet:- Bookmarks: Page filters ====================**/
	   jQuery(document).ready( function($){ 
		   $(".bookmark-page-filter-list li button").click(function(){
			  var filter_val= $(this).val();
			   $(".bookmark-page-filter-list li button").removeClass('active');
			   $(this).addClass('active');
			  jQuery.ajax({
				 type : "post",
				 url : "<?php echo site_url().'/wp-admin/admin-ajax.php' ?>",
				 data : {action: "bookmark_page_favorite_list", filterdata : filter_val, nonce: "<?php echo wp_create_nonce("my_user_fav_nonce"); ?>"},
				 success: function(response) {
					$('.bkmark_template').html(response);
				 }
			  })   
		   });
	   });   
   </script>	
   <script>
    /*==============Snippet: Account Settings==============*/
        jQuery(document).ready(function($){
			 setTimeout(function () {
				   $(".bp-settings-container #subnav ul").append('<li id="shop-setting-li" class="bp-personal-sub-tab"><a href="<?php echo bp_loggedin_user_domain().'shop'; ?>" id="shop-setting"><i class="bb-icon-shopping-cart"></i> Manage Shop</a></li>');
			  }, 0); 
			$('#wp-admin-bar-my-account-settings>a').text("Membership");
			$('.main-navs.single-screen-navs #settings-personal-li>a').text("Membership");
			$('.settings-header .settings-title').text("Membership Settings");
		 });
   </script>

   <script>
   	/*==========Snippet:- Newsletter Active-Campaign Ajax ==========*/
   	/* write your JavaScript code here */
		jQuery(document).ready( function($){  
        jQuery(document).on('change', '#phnws', function(e) {
        e.preventDefault();  
		//Check subciption checbox value	
			 var nws_val;
			 if($(this).is(":checked")){
               nws_val='subscribed';
            }
            else if($(this).is(":not(:checked)")){
               nws_val='unsubscribed';
            }
		//Ajax Call	
        jQuery.ajax({           
            url : "<?php echo site_url().'/wp-admin/admin-ajax.php' ?>",
            type : 'post',
            data : {
                action : 'phsubscribe_function',
                subscribe_status : nws_val
            },
            success : function( response ) {
                 console.log(response);              
                jQuery('#subs-msg').html(response);

            }
        }); 
    });
});

		
/* Call Ajax on button submit when user opt-in or opt-out Newsletter on Email preference tab*/
		jQuery(document).ready( function($){  
        //jQuery(".submit input[type='submit']").click(function(){
		jQuery("#settings-form").submit(function(e) {
            var radioValue = $("input[name='newsletter-subscribe']:checked").val();
			var nwsletter_val;
            if(radioValue){
                nwsletter_val= radioValue;
            }
			//alert(nwsletter_val);
       
		
		//Ajax Call	
        jQuery.ajax({           
            url : "<?php echo site_url().'/wp-admin/admin-ajax.php' ?>",
            type : 'post',
            data : {
                action : 'phnews_subscribe_function',
                subscribe_status : nwsletter_val
            },
            success : function( response ) {
                 //alert(response); 
            }
        }); 
    });
});
   </script>
  <script>
		/*=====Snippets:-Section Search styling - archive horiz - select2 - DO NOT EDIT=====*/
		jQuery(document).ready( function() {
			
			/* Hide BB's search elements on course archive page: */
			jQuery('.post-type-archive-sfwd-courses nav.courses-type-navs.main-navs.bp-navs.dir-navs.bp-subnavs').hide();

			/* Fix the title, and add a subtitle:
				Note: this is a BAD soluton.  
				We need to do this in PHP instead.
			*/
			jQuery('.post-type-archive-sfwd-courses .top-serch-filter div.bb-courses-header').replaceWith('<div class="projects-header"><h4 class="bb-title projects-title">build something!</h4><p class="projects-subtitle">DIY projects, skills tutorials and kits for your crafting pleasure</p></div>');
						
			//Init the Select2 dropdown styler for each of our filters:
			initializeSelect2();
			
		}); //Document ready

		
		/* Re-initialize the Select2 instances each time ajax content is loaded: 
		See: https://support.searchandfilter.com/forums/topic/select2-orderby-not-displaying/ */
		jQuery(document).on("sf:ajaxfinish", ".searchandfilter", function() {
		
			//Init the Select2 dropdown styler for each of our search filters:
			initializeSelect2();

		}); //Document on
		
		
		//Create a unique placeholder for each of our site search selects:
		function initializeSelect2() {
			
			jQuery('#search-filter-form-29986 .sf-field-taxonomy-ld_course_category .sf-input-select').select2({
  				allowClear: true,
				placeholder: 'project type',
				minimumResultsForSearch: Infinity
			});
			jQuery('#search-filter-form-29986 .sf-field-post-meta-complexity_level .sf-input-select').select2({
  				allowClear: true,
				placeholder: 'complexity',
				minimumResultsForSearch: Infinity
			});
			jQuery('#search-filter-form-29986 .sf-field-tag .sf-input-select').select2({
  				allowClear: true,
				placeholder: 'topic'
			});	
		}
		
	</script>
	<script>
		/*======Snippet:-Projects: lesson pages======*/
        jQuery(document).ready(function($){
			
			//Set up some prompts for visitors to join:
			
			//If user is not logged in:
			if(!jQuery('body').hasClass('logged-in')) {
				//If we are on a lesson page:
				if(jQuery('body').hasClass('sfwd-lessons-template-default')) {

					//First, remove the old text:
					$('.bb-ld-status div.ld-status.ld-status-progress.ld-primary-background').empty(); 
				
					//Construct the sign-up URL for whatever server we're on right now:  
					var currentUrl = location.protocol + '//' + location.host + '/community/join';
					
					//Construct the anchor:
					var progressMessage = '<a class="join-link" href="' + currentUrl + '">Join to see project progress here!</a>';
					//console.log(anchorComplete);
					
					//Add a sign-up invite message as a link:
					$('.bb-ld-status div.ld-status.ld-status-progress.ld-primary-background').wrapInner(progressMessage);
					
					//Construct the button:
					var completeButton = '<a class="join-button" href="' + currentUrl + '">Join to mark complete!</a>';
					//console.log(anchorComplete);

					//Add a promo at the bottom of each lesson page too:
					$(completeButton).insertAfter(".ld-tab-content");
					
				}																								
			}
			else {
				
				//console.log('logged-in detect: logged in');
			}
				
		});	
	</script>
</body>
</html>
