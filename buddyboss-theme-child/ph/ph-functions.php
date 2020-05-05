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

/*========Snippets:- Bookmarks: light box filter=========*/

add_action("wp_ajax_my_favorite_list", "my_favorite_list");
add_action("wp_ajax_nopriv_my_favorite_list", "my_favorite_list");

function my_favorite_list() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_user_fav_nonce")) {
     return false;
   } 
	$filterdata=$_POST['filterdata'];
	if($filterdata=="all-categories"){
		echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail" include_excerpts="true" ]');
	} elseif($filterdata=="think"){
		$post_type='post';
	} elseif($filterdata=="build"){
		$post_type='sfwd-courses';
	} elseif($filterdata=="buy"){
		$post_type='product';
	}  else{
		echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail" include_excerpts="true" ]');
	}
	
	   $filters_data = array(
	  'post_type' => array(
		$post_type
	  ),
	  'status' => array(
		'publish'
	  )
	);
	the_user_favorites_list($user_id = null, $site_id = null, $include_links = true, $filters =  $filters_data, $include_button = true, $include_thumbnails = true, $thumbnail_size = 'thumbnail', $include_excerpt = true);
	  
   die();

}

/*=============Snippet:- Bookmarks: Page filters=============*/
add_action("wp_ajax_bookmark_page_favorite_list", "bookmark_page_favorite_list");
add_action("wp_ajax_nopriv_bookmark_page_favorite_list", "bookmark_page_favorite_list");

function bookmark_page_favorite_list() {

   if ( !wp_verify_nonce( $_REQUEST['nonce'], "my_user_fav_nonce")) {
     return false;
   } 
	$filterdata=$_POST['filterdata'];
	if($filterdata=="all-categories"){
		echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail" include_excerpts="true" ]');
	} elseif($filterdata=="think"){
		$post_type='post';
	} elseif($filterdata=="build"){
		$post_type='sfwd-courses';
	} elseif($filterdata=="buy"){
		$post_type='product';
	} else{
		echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail" include_excerpts="true" ]');
	} 
	
	   $filters_data = array(
	  'post_type' => array(
		$post_type
	  ),
	  'status' => array(
		'publish'
	  )
	);
	the_user_favorites_list($user_id = null, $site_id = null, $include_links = true, $filters =  $filters_data, $include_button = true, $include_thumbnails = true, $thumbnail_size = 'thumbnail', $include_excerpt = true);
	  
   die();

}

/*===========Snippets:-Menu main: PHP to add category to every type of post page for menu and section styling=========*/
function pn_body_class_add_categories( $classes ) {
 
	// Enable this if we only want to place category classes on a single post page, not archives:
	//if ( !is_single() )
	//	return $classes;
 
	// Get the categories that are assigned to this post
	$post_categories = get_the_category();
 
	// Loop over each category in the $categories array
	foreach( $post_categories as $current_category ) {
 
		// Add the current category's slug to the $body_classes array
		$classes[] = 'category-' . $current_category->slug;
 
	}
 
	// Finally, return the $body_classes array
	return $classes;
}
add_filter( 'body_class', 'pn_body_class_add_categories' );

/*===========Snippets: Quickview customizations==================*/
/* Add link to full product page, on Iconic quickview lightbox: */  
function iconic_qv_shop_link(  $product_id, $post, $product ) {
	if( ! empty( $_POST['variation_id'] ) ) {
		$product = wc_get_product( $_POST['variation_id'] );
	}
	printf( '<a href="%s" class="iconic-shop-link">%s</a>', $product->get_permalink(), __( 'See more in shop...', 'iconic' ) );
}
add_action( 'jck_qv_summary', 'iconic_qv_shop_link', 100, 3 );

/* Add link to full product page, on Barn2 quickview lightbox and product page: */
/* (we are hooking into the woo product because for now, because we don't know how to hook into the barn2 quickview proper) */
function barn2_qv_shop_link() { 
	global $product;
    $id = $product->get_id();
	printf( '<a href="%s" class="barn2-shop-link">%s</a>', $product->get_permalink(), __( 'See more in shop...', 'barn2' ) );
}; 
add_action( 'woocommerce_share', 'barn2_qv_shop_link', 10, 0 );

/*==========Snippets:- Newsletter Subscription Function===========*/
// Set up Cutsom BP navigation
add_action( 'bp_member_options_nav', 'bp_email_preferences_links' );
 
function bp_email_preferences_links() {
	global $bp;
    ?>
<li><a href="<?php echo esc_url( bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications' ); ?>">Email Preferences</a></li>
<?php 
}
//Ajax call 'Newsletter Active-Campaign Ajax '

function phnews_subscribe_function() {  
   require_once(ABSPATH . "/ActiveCampaign/includes/ActiveCampaign.class.php");
   $ac = new ActiveCampaign(ACTIVECAMPAIGN_URL, ACTIVECAMPAIGN_API_KEY);
   
	$profileUserID = bp_displayed_user_id();
    $subscribe = $_POST['subscribe_status'];
   //echo $subscribe; 
	$subscribe_key_value = get_user_meta($profileUserID, 'ac_newsletter_subscribe', true);
	if($subscribe_key_value){
	 update_user_meta( $profileUserID, 'ac_newsletter_subscribe', $subscribe );
	}else{
	 add_user_meta( $profileUserID, 'ac_newsletter_subscribe', $subscribe);
	}
	//Get User Data
	  $user_info = get_userdata($profileUserID);
	  $user_email = $user_info->user_email;
      $first_name = $user_info->first_name;
      $last_name = $user_info->last_name;
	//account view
    $account = $ac->api("account/view");
	
	$list_id=1;//Active-campaign list Id
	if($subscribe=='subscribed'){
	
		$contact = array(
			"email"              => $user_email,
			"first_name"         => $first_name,
			"last_name"          => $last_name,
			"p[{$list_id}]"      => $list_id,
			"status[{$list_id}]" => 1, // "Active" status
		);

		$contact_sync = $ac->api("contact/sync", $contact);

		if (!(int)$contact_sync->success) {
			// request failed
			echo "<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>";
			exit();
		}

			// successful request
			$contact_id = (int)$contact_sync->subscriber_id;
			//echo "<p>Contact subscribed successfully (ID {$contact_id})!</p>";
		    echo 'Thank you for newsletter subscription';
		 
		
    } else{
		$contact = array(
		"email"              => $user_email,
		"p[{$list_id}]"      => $list_id,
		"status[{$list_id}]" => 2, // "Active" status
	);
     
	$contact_sync = $ac->api("contact/sync", $contact);

	if (!(int)$contact_sync->success) {
		// request failed
		echo "<p>Syncing contact failed. Error returned: " . $contact_sync->error . "</p>";
		exit();
	}
        
        // successful request
        $contact_id = (int)$contact_sync->subscriber_id;
        //echo "<p>Contact unsubscribed successfully (ID {$contact_id})!</p>";
		echo 'You have unsubscribed successfully';
	}
	
    die();
}

add_action('wp_ajax_phnews_subscribe_function', 'phnews_subscribe_function'); 
add_action('wp_ajax_nopriv_phnews_subscribe_function', 'phnews_subscribe_function');

/*======Snippet:-Section Search styling - archive horiz - select2 - DO NOT EDIT======*/
//Register the Select2 library components for use on our site:
	function enqueue_select2() { 
		wp_enqueue_script( 'select2-library', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js' ); 
		wp_enqueue_style( 'select2-stylesheet', 'https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css' ); 
	}
	add_action( 'wp_enqueue_scripts', 'enqueue_select2' );

/*======Snippet:-Enrolled user after lesson completion======*/
add_filter( 'learndash_completion_redirect', function( $link, $post_id ){
	$user_id = get_current_user_id();
	$course_id = learndash_get_course_id( $post_id );
if ( get_post_type( $post_id ) == 'sfwd-lessons' ) {
   ld_update_course_access($user_id, $course_id, $remove = false);
}	
return $link;
},5,2);

/*===========Snippet:Termly Cookie Consent Banner v1============*/
 if ( !function_exists( 'bp_is_register_page' ) && !is_user_logged_in() && !bp_is_register_page()) { 
add_action( 'wp_head', function () { ?>
	<script>
	  (function () {
		var s = document.createElement('script');
		s.type = 'text/javascript';
		s.async = true;
		s.src = 'https://app.termly.io/embed.min.js';
		s.id = '5621db51-7ef0-425d-80ac-71deb1020ab8';
		s.setAttribute("data-name", "termly-embed-banner");
		var x = document.getElementsByTagName('script')[0];
		x.parentNode.insertBefore(s, x);
	  })();
	</script>
<?php } );
}

/*========Learn dash helper fuction copy for riboon status change ===========*/
function ph_landing_learndash_status_bubble( $status = 'incomplete', $context = null, $echo = true ) {

	$bubble = '';
    
	switch ( $status ) {
		case 'In Progress':
		case 'progress':
		case 'incomplete':
			$bubble = '<div class="ld-status ld-status-progress ph-ld-primary-background">' . esc_html_x( 'Building!', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'complete':
		case 'completed':
		case 'Completed':
			$bubble = '<div class="ld-status ld-status-complete ph-ld-secondary-background">' . esc_html_x( 'you finished it!', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'graded':
			$bubble = '<div class="ld-status ld-status-complete ph-ld-secondary-background">' . esc_html_x( 'Graded', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'not_graded':
			$bubble = '<div class="ld-status ld-status-progress ph-ld-primary-background">' . esc_html_x( 'Not Graded', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case '':
		default:
			break;
	}

	$bubble = apply_filters( 'learndash_status_bubble', $bubble, $status );

	if ( $echo ) {
		echo wp_kses_post( $bubble );
	} else {
		return $bubble;
	}

}

/*========Learn dash helper fuction copy for riboon status change ===========*/
function ph_learndash_status_bubble( $status = 'incomplete', $context = null, $echo = true ) {

	$bubble = '';
    
	switch ( $status ) {
		case 'In Progress':
		case 'progress':
		case 'incomplete':
			$bubble = '<div class="ld-status ld-status-progress ph-ld-primary-background">' . esc_html_x( 'In Progress!', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'complete':
		case 'completed':
		case 'Completed':
			$bubble = '<div class="ld-status ld-status-complete ph-ld-secondary-background">' . esc_html_x( 'Completed', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'graded':
			$bubble = '<div class="ld-status ld-status-complete ph-ld-secondary-background">' . esc_html_x( 'Graded', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case 'not_graded':
			$bubble = '<div class="ld-status ld-status-progress ph-ld-primary-background">' . esc_html_x( 'Not Graded', 'In Progress item status', 'learndash' ) . '</div>';
			break;

		case '':
		default:
			break;
	}

	$bubble = apply_filters( 'learndash_status_bubble', $bubble, $status );

	if ( $echo ) {
		echo wp_kses_post( $bubble );
	} else {
		return $bubble;
	}

}

?>
