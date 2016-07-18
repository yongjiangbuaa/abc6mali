// JavaScript Document
jQuery(window).ready(function() {

	//MENU Animation
	if (jQuery(window).width() > 768) {
	jQuery('#topmenu ul > li').hoverIntent(function(){
	jQuery(this).find('.sub-menu:first, ul.children:first').slideDown({ duration: 200});
		jQuery(this).find('a').not('.sub-menu a, ul.children a').stop().animate({"color":primarycolor}, 200);
	}, function(){
	jQuery(this).find('.sub-menu:first, ul.children:first').slideUp({ duration: 200});	
		jQuery(this).find('a').not('.sub-menu a, ul.children a').stop().animate({"color":menutext}, 200);
	
	});

	jQuery('#topmenu ul li').not('#topmenu ul li ul li').hover(function(){
	jQuery(this).addClass('menu_hover');
	}, function(){
	jQuery(this).removeClass('menu_hover');	
	});
	jQuery('#topmenu li').has("ul").addClass('zn_parent_menu');
	jQuery('.zn_parent_menu > a').append('<span><i class="fa-angle-down"></i></span>');
	}
	
	//Slider Navigation Animation
	jQuery('#zn_nivo').hover(function(){
	jQuery(this).find('.nivo-directionNav').stop().animate({ "opacity":"1" }, 300);
	}, function(){
	jQuery(this).find('.nivo-directionNav').stop().animate({ "opacity":"0" }, 300);	
	});
	
	//Slider empty content
	jQuery('.acord_text p:empty, .acord_text h3 a:empty, .uninner h3 a:empty, .nivoinner h3 a:empty').css({"display":"none"});

//Widget image opacity animation
jQuery('.widget_wrap a img').hover(function(){
	jQuery(this).stop().animate({ "opacity":"0.7" }, 300);
	}, function(){
	jQuery(this).stop().animate({ "opacity":"1" }, 300);	
});

	
//Layout1 Animation
	if (jQuery(window).width() < 360) {
var divs = jQuery(".lay1 .hentry");
for(var i = 0; i < divs.length; i+=1) {
  divs.slice(i, i+1).wrapAll("<div class='ast_row'></div>");
}		
	}else if (jQuery(window).width() < 480) {
var divs = jQuery(".lay1 .hentry");
for(var i = 0; i < divs.length; i+=2) {
  divs.slice(i, i+2).wrapAll("<div class='ast_row'></div>");
}
	}else{
var divs = jQuery(".lay1 .hentry");
for(var i = 0; i < divs.length; i+=3) {
  divs.slice(i, i+3).wrapAll("<div class='ast_row'></div>");
}
	}

jQuery('.lay1 .postitle a:empty').closest("h2").addClass('no_title');
jQuery('.no_title').css({"padding":"0"});


//Pagination
if ( jQuery('.ast_pagenav').children().length < 7 ) {
jQuery('.ast_pagenav .page-numbers:last-child').css({'marginRight':'0'});
jQuery('.ast_pagenav .page-numbers').wrapAll('<div class="pagi_border" />');
jQuery('.pagi_border').append('<dt />');
var sum=0;
jQuery('.ast_pagenav .page-numbers').each( function(){ sum += jQuery(this).outerWidth( true ); });
jQuery('.ast_pagenav .pagi_border').width( sum );
}

// TO_TOP
jQuery(window).bind("scroll", function() {
    if (jQuery(this).scrollTop() > 800) {
        jQuery(".to_top").fadeIn('slow');
    } else {
        jQuery(".to_top").fadeOut('fast');
    }
});
jQuery(".to_top").click(function() {
  jQuery("html, body").animate({ scrollTop: 0 }, "slow");
  return false;
});

//Sidebar widget padding fix
jQuery('.widget').not(':has(.widgettitle)').addClass('untitled');
jQuery('.rel_eq').equalHeights();

//Hide Homepage Elemnts if empty
jQuery('.home_blocks').each(function () {
	if(jQuery(this).html().length > 3) {
		jQuery(this).addClass('activeblock');
		}
});

jQuery('.lay1, .lay2, .lay3, .lay4, .lay5, .lay6').not(':has(.hentry)').css({"display":"none"});

//WAYPOINT ANIMATIONS
if (jQuery(window).width() > 480) {	


jQuery('.midrow_block').css({"opacity":"0"})
jQuery('.midrow_block').waypoint(function() {
	jQuery(this).addClass('animated fadeInUp'); 
}, { offset: '90%' });

//Posts Animation
jQuery('.home .lay1 .lay1_wrap').css({"opacity":"0"});
jQuery('.home .lay1').waypoint(function() {
  jQuery('.home .homeposts_title, .home .lay1_wrap').addClass('animated fadeInUp');
  }, { offset: '90%' });

jQuery('.home_tabs .center, .home .lay4').css({"opacity":"0", "marginTop":"60px"});
jQuery('.home_tabs .center, .lay4').waypoint(function() {
  jQuery(this).animate({"opacity":"1", "marginTop":"0px"});
}, { offset: '90%' });
}

//Next Previous post button Link
    var link = jQuery('.ast-next > a').attr('href');
    jQuery('.right_arro').attr('href', link);

    var link = jQuery('.ast-prev > a').attr('href');
    jQuery('.left_arro').attr('href', link);



//Mobile Menu
	//jQuery("#topmenu").attr("id","sidr");
	var padmenu = jQuery("#simple-menu").html();
	jQuery('#simple-menu').sidr({
      name: 'sidr-main',
      source: '#topmenu'
    });
	jQuery(".sidr").prepend("<div class='pad_menutitle'>"+padmenu+"<span><i class='fa-times'></i></span></div>");
	
	jQuery(".pad_menutitle span").click(function() {
		jQuery.sidr('close', 'sidr-main')
		preventDefaultEvents: false;
		
});


//NivoSlider Navigation Bug Fix
if (jQuery(window).width() < 480) {
	jQuery(".nivo-control").text('')
}

//FIX HEADER4 MENU DISAPPREAING ISSUE(version 1.8)
var breakpoint = jQuery('#topmenu').position();
if (jQuery(window).width() < 900) {
	if(breakpoint.top >10){
		jQuery('#topmenu').css({"display":"none"});
		jQuery('#simple-menu').css({"display":"block"});
	}
}

//slider porgressbar loader
jQuery(function () {
    var n = 0,
        $imgs = jQuery('.slider-wrapper img'),
        val = 100 / $imgs.length,
        $bar = jQuery('#astbar');
		$progrssn = jQuery('.progrssn');

    $imgs.load(function () {
        n = n + val;
        // for displaying purposes
		$progrssn.width(n + '%');
		var numTruncated = parseFloat(n).toFixed(1);
        $bar.text(numTruncated);
    });
    
});
jQuery('.slider-wrapper').waitForImages(function() {
	jQuery("#zn_nivo, .nivo-controlNav, #slide_acord, .nivoinner").css({"display":"block"});
    jQuery(".pbar_wrap").fadeOut();
});
jQuery(window).load(function(){
jQuery('.nivo-controlNav').css({"display":"block"});
});	


//Remove margin from homeblocks after ast_blocks
jQuery(".ast_blocks").next('.home_blocks').css({"marginTop":"0"});

});