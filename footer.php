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

</body>
</html>
