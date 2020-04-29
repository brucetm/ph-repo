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
		
