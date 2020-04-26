/**=============Snippet:- Bookmark: add button to product page=============*/
//Add favorite button on single product page:
add_action( 'woocommerce_after_add_to_cart_button', 'ph_favorite_before_add_to_cart_btn' );
 
function ph_favorite_before_add_to_cart_btn(){
	echo '<p class="fav_btn">';
	echo do_shortcode('[favorite_button]');
	echo '</p>';
}