/**==============Snippet:- Bookmarks: light box filter ==============*/
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