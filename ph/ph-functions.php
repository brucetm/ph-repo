<?php
/**=============Snippet:- Bookmark: add button to product page=============*/
//Add favorite button on single product page:
add_action( 'woocommerce_after_add_to_cart_button', 'ph_favorite_before_add_to_cart_btn' );
 
function ph_favorite_before_add_to_cart_btn(){
	echo '<p class="fav_btn">';
	echo do_shortcode('[favorite_button]');
	echo '</p>';
}

/**================Snippet:- Bookmarks: add bookmarks page link to member profile===========*/
// Set up Custom BP navigation:
/*add_action( 'bp_member_options_nav', 'bp_add_ph_favorite_links' );
 
function bp_add_ph_favorite_links() {
    ?>
		<li><a href="<?php echo site_url('bookmarks');?>">Bookmarks</a></li>
	<?php 
}
*/
function phbookmark_profile_tab() {
  global $bp;

  bp_core_new_nav_item( array( 
        'name' => __( 'Bookmarks', 'phbookmark' ), 
        'slug' => 'bookmarks', 
        'position' => 110,
        'screen_function' => 'phbookmark_profile_list',
        'show_for_displayed_user' => true,
        'item_css_id' => 'phbookmark_profile_list'
  ) );
  
}

add_action( 'bp_setup_nav', 'phbookmark_profile_tab', 1000 );

// Newsletter tab page title and content hook
function phbookmark_profile_list () {
    add_action( 'bp_template_title', 'ph_profile_bookmark_title' );
    add_action( 'bp_template_content', 'ph_profile_bookmark_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function ph_profile_bookmark_title() {
	 //_e( 'bookmarks', 'buddypress' );
}

function ph_profile_bookmark_content() {
  
  $profileUserID = bp_displayed_user_id();
 

  // Newsletter subscription Checkbox
  if( $profileUserID > 0 ) {?>
 <div class="bp-profile-wrapper">
	 <div class="bp-profile-content">
	    <header class="entry-header profile-loop-header profile-header flex align-items-center">
            <h2 class="entry-title bb-profile-title">bookmarks</h2>	
		 </header>	 
  <div class="bookmark-page-filter">
            <ul class="bookmark-page-filter-list">
                <li><button value="all-categories" class="active">all types</button></li>
                <li><button value="think">think</button></li>
                <li><button value="build">build</button></li>
                <li><button value="buy">buy</button></li>
            </ul>   
        </div>
     <div class="bkmark_template"> 
		 <?php echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail"  include_excerpts="true"] ');
	  ?>
	  </div>
	 </div>
</div>
<?php	   
  }
	
} 
?>