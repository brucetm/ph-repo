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
