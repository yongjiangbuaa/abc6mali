// Gallery plugin written by Towfiq I.
jQuery(window).ready(function() {
	
//Check if image links to attachment
jQuery('.gallery-item a[href*="attachment_id"]').each(function() {
    jQuery(this).addClass('attachedlink');
});
jQuery('.gallery').each(function() {
	jQuery(this).has('.attachedlink').addClass('defgallery');
});

//remove any <br> inside the gallery markup
jQuery(".gallery br").remove();
//Empty Caption FIX
jQuery('.gallery-item:not(:has(.gallery-caption))').prepend('<dd class="wp-caption-text gallery-caption"></dd>');
//wrap all .gallery-item with .gall_dash /  For making the thumbnail navigation area
jQuery(".gallery").each(function (){jQuery(this).find(".gallery-item").wrapAll('<div class="gall_dash" style="display:none" />');});

jQuery('.gall_dash .hasimg').removeClass('hasimg');
	
//Prepend the big image area. and load the src image of the first thumbnail. The.ast_full is for fancybox integration.
jQuery(".single_post .gallery").prepend("<div class='ast_gall'><div class='sk-spinner sk-spinner-wave'><div class='sk-rect1'></div><div class='sk-rect2'></div><div class='sk-rect3'></div><div class='sk-rect4'></div><div class='sk-rect5'></div></div><a href='' class='ast_full' style='display: block;'></a><div class='ast_gall_wrap'></div></div>");

//==============REMAP AND APPEND THE MAIN IMAGES================
jQuery(".gallery").each(function (){
		var gallid = jQuery(this).attr('id');
		var tn_array = jQuery(this).find(".gallery-item a").map(function() {
		  return jQuery(this).attr("href");
		});
		var tn_array_cap = jQuery(this).find(".gallery-caption").map(function() {
				return jQuery(this).text();	
		});
		var tn_array_src = jQuery(this).find(".gallery-item img").map(function() {
		  return jQuery(this).attr("src");
		});
		var pageLimit= jQuery(this).find(".gall_dash img").size() - 1;
		for (var i = 0; i <= pageLimit; i++) {
			var article = jQuery(this).find(".gallery-item a");
				jQuery(article[i]).addClass("" + i + "");
				jQuery(article[i]).attr('id' , "vis" + i + "");
				jQuery(this).find('.ast_gall_wrap').append("<img id='mainImage" + i + "' src='"+tn_array[i]+"' title='#"+gallid + i + "' data-thumb='"+tn_array_src[i]+"' data-caption='"+tn_array_cap[i]+"' />");
				jQuery(this).find('.ast_gall_wrap').after('<div id="'+gallid + i + '" class="nivo-html-caption"><div class="cap_inner">'+tn_array_cap[i]+'</div></div>')

			}
			});
});


jQuery(window).ready(function() {
	jQuery(".ast_gall_wrap").each(function (){	
	jQuery(this).waitForImages(function() {
		jQuery(this).css({"minHeight":"initial"});
		jQuery(this).parent().find('.sk-spinner-wave').hide();
		jQuery(this).fadeIn();
		
		jQuery(this).nivoSlider({
				effect: 'fade',
				animSpeed:300,
				pauseTime:3000,
				startSlide:0,
				slices:5,
				directionNav:true,
				directionNavHide:true,
				controlNav:true,
				controlNavThumbs:true,
				keyboardNav:true,
				manualAdvance: true,
				afterChange: function(){  
					jQuery(this).parent().find('.ast_full').attr('href', jQuery(this).find('.nivo-main-image').attr('src')); 
					jQuery(this).find('.nivo-caption').css({"width":jQuery(this).find('.nivo-main-image').width()});
				}
		});
		jQuery(this).parent().find('.ast_full').attr('href', jQuery(this).find('.nivo-main-image').attr('src'));
		
		jQuery(".ast_full").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	400,
			'speedOut'		:	200,
			'overlayShow'	:	true,
			'hideOnContentClick' : true,
			'titleShow':false
		}); 
	});
});
});