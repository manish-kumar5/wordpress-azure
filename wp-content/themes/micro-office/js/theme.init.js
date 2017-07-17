/* global jQuery:false */
/* global MICRO_OFFICE_STORAGE:false */


// Theme-specific first load actions
//==============================================

function micro_office_theme_ready_actions() {
	"use strict";
	// Put here your init code with theme-specific actions
	// It will be called before core actions
	
	if(jQuery('.top_panel_user').length > 0){
		jQuery('.top_panel_user > a').on('click', function(){
			if(jQuery('.sidebar_outer').hasClass('sidebar_show'))
			{
				jQuery('.sidebar_outer').addClass('sidebar_hide').removeClass('sidebar_show');
			}
			else{
				jQuery('.sidebar_outer').removeClass('sidebar_hide').addClass('sidebar_show');
				if (jQuery(window).width() <= 1023){
					setTimeout(function(){
						"use strict";
						var height = jQuery('.page_content_wrap .content_wrap').outerHeight(); 
						jQuery('.sidebar_outer, .menu_main_wrap').height(height);
					}, 2500);
				}
			}
		});
	}
	
	if(jQuery('#user_menu').length > 0){
		jQuery('#user_menu .menu-parent > a').on('click', function(e){
			
			jQuery('#user_menu .menu-parent.active .sub-menu').slideUp();
			jQuery('#user_menu .menu-parent.active').removeClass('active');
			
			jQuery(this).parent().addClass('active');
			jQuery(this).parent().find('.sub-menu').slideDown();
			
			e.preventDefault();
		});
	}

	initResponsive();
	
	jQuery('.comment-reply-link, #cancel-comment-reply-link').on('click', function(){
		jQuery('.comments_form_wrap').addClass('reply');
	});
}

function initResponsive(){
	"use strict"; 
	if(jQuery(window).width() >= 1180){
		jQuery('body').removeClass('tablet').removeClass('mobile');
	}
	else if(jQuery(window).width() > 768 && jQuery(window).width() < 1180){
		jQuery('body').addClass('tablet').removeClass('mobile');
	}
	else if(jQuery(window).width() <= 768){
		jQuery('body').removeClass('tablet').addClass('mobile');
	}
	initMenu();
	
	var width = jQuery('.page_content_wrap .sidebar_wrap .sidebar_inner').outerWidth();
	jQuery('.page_content_wrap .sidebar_wrap').width(width);
}

function initMenu(){
	"use strict";
	if(jQuery(window).width() <= 1023)  {
		jQuery('body').removeClass('sidebar_hide').addClass('sidebar_show');		
	}
	else {
		var myCookie = jQuery.cookie('sidebar_pushy');
		if(myCookie != null) 
			jQuery('body').removeClass('sidebar_hide').removeClass('sidebar_show').addClass(myCookie);
	}
	
	if(jQuery('body').hasClass('tablet') || jQuery('body').hasClass('mobile')){
		jQuery('.menu_pushy span, .menu_mask').on('click', function(){
			"use strict";
			
			setTimeout(function(){
				"use strict";
				var height = jQuery('.page_content_wrap .content_wrap').outerHeight(); 
				jQuery('.sidebar_outer, .menu_main_wrap').height(height);
			}, 1500);
			
	
			if(jQuery('.page_content_wrap').hasClass('show_menu')) {
				jQuery('.page_content_wrap').removeClass('show_menu');
				jQuery('body, html').removeClass('overflow');				
			}
			else {
				jQuery('.page_content_wrap').addClass('show_menu');
				jQuery('body, html').addClass('overflow');		
			}
		});	
	}
}

// Theme-specific scroll actions
//==============================================
function micro_office_theme_scroll_actions() {
	"use strict";
	// Put here your theme-specific code with scroll actions
	// It will be called when page is scrolled (before core actions)
	// Fix/unfix sidebar
	micro_office_fix_sidebar();
	micro_office_fix_outersidebar();
	micro_office_fix_menu();
}

// Fix/unfix sidebar
function micro_office_fix_sidebar() {
	"use strict";
	var page_content_wrap = jQuery('.page_content_wrap');
	var sb = jQuery('.sidebar_inner');
	if (sb.length > 0 && jQuery(window).width() > 1023){

		// Unfix when sidebar is under content
		if (jQuery('.page_content_wrap .content_wrap .content').css('float') == 'none') {
			if (sb.css('position')=='fixed') {
				sb.css({
					'float': sb.hasClass('right') ? 'right' : 'left',
					'position': 'static'
				});
			}

		} else {

			var sb_height = sb.outerHeight(); 		
			
			var content_height = page_content_wrap.find('.content_wrap').outerHeight(); 	
			var scroll_offset = jQuery(window).scrollTop();
			var top_panel_height = jQuery('.top_panel_wrap').length > 0 ? jQuery('.top_panel_wrap').outerHeight() : 0; 	
			var top_panel_fixed_height = 0;
			if(jQuery('body').hasClass('admin-bar')) {
				top_panel_fixed_height = 32;
			}
			
			if (sb_height < content_height && 
				(sb_height >= jQuery(window).height() && scroll_offset + jQuery(window).height() > sb_height+top_panel_height
				||
				sb_height < jQuery(window).height() && scroll_offset > top_panel_height )
				) {
				
				// Fix when sidebar bottom appear
				if (sb.css('position')!=='fixed') {
					var sb_top = sb_height >= jQuery(window).height()
										? Math.min(0, jQuery(window).height() - sb_height)
										: top_panel_fixed_height;
					sb.css({
						'position': 'fixed',
						'top': sb_top + 'px'
					});
				}
				
				
				// Shift to top when footer appear
				var footer_top = 0;
				var footer_pos = jQuery('.footer_wrap').position();
				if(footer_pos == 0)
					footer_top = jQuery('.copyright_wrap').position(); 
				
				if (footer_pos)
					footer_top = footer_pos.top;  
				if (footer_top > 0 && scroll_offset + jQuery(window).height() > footer_top)
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height - (scroll_offset + jQuery(window).height() - footer_top + 50)) + 'px'
					});
				else
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height) + 'px'
					});
			} else {
				// Unfix when page scrolling to top
				if (sb.css('position')=='fixed') {
					sb.css({
						'position': 'static',
						'top': 'auto'
					});
				}
			}
		}
	}
	else if (sb.length > 0 && jQuery(window).width() <= 1023){
		if (sb.css('position')=='fixed') {
			sb.css({
				'position': 'static',
				'top': 'auto'
			});
		}
	}
}

// Fix/unfix sidebar
function micro_office_fix_outersidebar() {
	"use strict";
	var page_content_wrap = jQuery('.page_content_wrap');
	var sb = jQuery('.sidebar_outer.sidebar_show .sidebar_outer_inner');
	if (sb.length > 0 && jQuery(window).width() > 1023){

		// Unfix when sidebar is under content
		if (jQuery('.page_content_wrap .content_wrap .content').css('float') == 'none') {
			if (sb.css('position')=='fixed') {
				sb.css({
					'float': sb.hasClass('right') ? 'right' : 'left',
					'position': 'static'
				});
			}

		} else {

			var sb_height = sb.outerHeight(); 		
			
			var content_height = page_content_wrap.find('.content_wrap').outerHeight(); 	
			var scroll_offset = jQuery(window).scrollTop();
			var top_panel_height = jQuery('.top_panel_wrap').length > 0 ? jQuery('.top_panel_wrap').outerHeight() : 0; 	
			var top_panel_fixed_height = 0;
			if(jQuery('body').hasClass('admin-bar')) {
				top_panel_fixed_height = 32;
			}
			
			if (sb_height < content_height && 
				(sb_height >= jQuery(window).height() && scroll_offset + jQuery(window).height() > sb_height+top_panel_height
				||
				sb_height < jQuery(window).height() && scroll_offset > top_panel_height )
				) {
				
				// Fix when sidebar bottom appear
				if (sb.css('position')!=='fixed') {
					var sb_top = sb_height >= jQuery(window).height()
										? Math.min(0, jQuery(window).height() - sb_height)
										: top_panel_fixed_height;
					sb.css({
						'position': 'fixed',
						'top': sb_top + 'px'
					});
				}
				
				
				// Shift to top when footer appear
				var footer_top = 0;
				var footer_pos = jQuery('.footer_wrap').position();
				if(footer_pos == 0)
					footer_top = jQuery('.copyright_wrap').position(); 
				
				if (footer_pos)
					footer_top = footer_pos.top;  
				if (footer_top > 0 && scroll_offset + jQuery(window).height() > footer_top)
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height - (scroll_offset + jQuery(window).height() - footer_top + 50)) + 'px'
					});
				else
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height) + 'px'
					});
				

			} else {

				// Unfix when page scrolling to top
				if (sb.css('position')=='fixed') {
					sb.css({
						'position': 'static',
						'top': 'auto'
					});
				}

			}
		}
	}
	else if (sb.length > 0 && jQuery(window).width() <= 1023){
		if (sb.css('position')=='fixed') {
			sb.css({
				'position': 'static',
				'top': 'auto'
			});
		}
	}
}

// Fix/unfix sidebar
function micro_office_fix_menu() {
	"use strict";
	var page_content_wrap = jQuery('.page_content_wrap');
	var sb = jQuery('.menu_main_nav_area');
	if (sb.length > 0 && jQuery(window).width() > 1023){
		
		// Unfix when sidebar is under content
		if (jQuery('.page_content_wrap .content_wrap .content').css('float') == 'none') {
			if (sb.css('position')=='fixed') {
				sb.css({
					'float': sb.hasClass('right') ? 'right' : 'left',
					'position': 'static'
				});
			}

		} else {

			var sb_height = sb.outerHeight() + 10; 		
			
			var content_height = page_content_wrap.find('.content_wrap').outerHeight(); 	
			var scroll_offset = jQuery(window).scrollTop();
			var top_panel_height = jQuery('.top_panel_wrap').length > 0 ? jQuery('.top_panel_wrap').outerHeight() : 0; 	
			var top_panel_fixed_height = 0;
			if(jQuery('body').hasClass('admin-bar')) {
				top_panel_fixed_height = 32;
			}
			
			if (sb_height < content_height && 
				(sb_height >= jQuery(window).height() && scroll_offset + jQuery(window).height() > sb_height+top_panel_height
				||
				sb_height < jQuery(window).height() && scroll_offset > top_panel_height )
				) {
				
				// Fix when sidebar bottom appear
				if (sb.css('position')!=='fixed') {
					var sb_top = sb_height >= jQuery(window).height()
										? Math.min(0, jQuery(window).height() - sb_height)
										: top_panel_fixed_height;
					sb.css({
						'position': 'fixed',
						'top': sb_top + 'px'
					});
				}
				
				
				// Shift to top when footer appear
				var footer_top = 0;
				var footer_pos = jQuery('.footer_wrap').position();
				if(footer_pos == 0)
					footer_top = jQuery('.copyright_wrap').position(); 
				
				if (footer_pos)
					footer_top = footer_pos.top;  
				if (footer_top > 0 && scroll_offset + jQuery(window).height() > footer_top)
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height - (scroll_offset + jQuery(window).height() - footer_top + 33)) + 'px'
					});
				else
					sb.css({
						'top': Math.min(top_panel_fixed_height, jQuery(window).height() - sb_height) + 'px'
					});

			} else {

				// Unfix when page scrolling to top
				if (sb.css('position')=='fixed') {
					sb.css({
						'position': 'static',
						'top': 'auto'
					});
				}

			}
		}
	}
	else if (sb.length > 0 && jQuery(window).width() <= 1023){
		if (sb.css('position')=='fixed') {
			sb.css({
				'position': 'static',
				'top': 'auto'
			});
		}
	}
}

// Theme-specific resize actions
//==============================================
function micro_office_theme_resize_actions() {
	"use strict";
	// Put here your theme-specific code with resize actions
	// It will be called when window is resized (before core actions)
	initResponsive();
	initMenu();	
	
	if (jQuery(window).width() <= 1023){
		setTimeout(function(){
			"use strict";
			var height = jQuery('.page_content_wrap .content_wrap').outerHeight(); 
			jQuery('.sidebar_outer, .menu_main_wrap').height(height);
		}, 1500);
	}
}


// Theme-specific shortcodes init
//=====================================================
function micro_office_theme_sc_init(cont) {
	"use strict";
	// Put here your theme-specific code to init shortcodes
	// It will be called before core init shortcodes
	// @param cont - jQuery-container with shortcodes (init only inside this container)
}


// Theme-specific post-formats init
//=====================================================
function micro_office_theme_init_post_formats() {
	"use strict";
	// Put here your theme-specific code to init post-formats
	// It will be called before core init post_formats when page is loaded or after 'Load more' or 'Infinite scroll' actions

	// Tribe Events buttons decoration (add 'sc_button' class)
	jQuery('a.tribe-events-read-more,.tribe-events-button,.tribe-events-nav-previous a,.tribe-events-nav-next a,.tribe-events-widget-link a,.tribe-events-viewmore a')
		.addClass('sc_button sc_button_style_filled');

	// All other buttons decoration (add 'hover' class)
	if (MICRO_OFFICE_STORAGE['button_hover'] && MICRO_OFFICE_STORAGE['button_hover']!='default') {
		jQuery('button:not(.search_submit):not([class*="sc_button_hover_"]),\
				.sc_button:not(.sc_button_style_border):not([class*="sc_button_hover_"]),\
				.woocommerce .button:not([class*="sc_button_hover_"]),.woocommerce-page .button:not([class*="sc_button_hover_"]),\
				#buddypress a.button:not([class*="sc_button_hover_"])'
				).addClass('sc_button_hover_'+MICRO_OFFICE_STORAGE['button_hover']);
		if (MICRO_OFFICE_STORAGE['button_hover']!='arrow')
			jQuery('input[type="submit"]:not([class*="sc_button_hover_"]),\
					input[type="button"]:not([class*="sc_button_hover_"]),\
					.isotope_filters_button,\
					.scroll_to_top:not([class*="sc_button_hover_"]),\
					.sc_slider_prev:not([class*="sc_button_hover_"]),.sc_slider_next:not([class*="sc_button_hover_"]),\
					.tagcloud > a:not([class*="sc_button_hover_"])'
					).addClass('sc_button_hover_'+MICRO_OFFICE_STORAGE['button_hover']);
	}

	// All text fields decoration (except Calculated Fields Forms)
	//if (MICRO_OFFICE_STORAGE['input_hover'])
	//	jQuery('form:not([class*="cp_cff_"]):not([class*="sc_input_hover_"])').addClass('sc_input_hover_'+MICRO_OFFICE_STORAGE['input_hover']);

	// Mark field as 'filled' on keypress
	jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea').each(function() {
		"use strict";
		if (jQuery(this).val()!='')
			jQuery(this).addClass('filled');
		else
			jQuery(this).removeClass('filled');
	});
	jQuery('[class*="sc_input_hover_"] input, [class*="sc_input_hover_"] textarea').on('blur', function() {
		"use strict";
		if (jQuery(this).val()!='')
			jQuery(this).addClass('filled');
		else
			jQuery(this).removeClass('filled');
	});
}


// Theme-specific GoogleMap styles
//=====================================================
function micro_office_theme_googlemap_styles($styles) {
	"use strict";
	// Put here your theme-specific code to add GoogleMap styles
	// It will be called before GoogleMap init when page is loaded
	$styles['greyscale'] = [
    	{ "stylers": [
        	{ "saturation": -100 }
            ]
        }
	];
	$styles['inverse'] = [
		{ "stylers": [
			{ "invert_lightness": true },
			{ "visibility": "on" }
			]
		}
	];
	$styles['simple'] = [
    	{ stylers: [
        	{ hue: "#00ffe6" },
            { saturation: -20 }
			]
		},
		{ featureType: "road",
          elementType: "geometry",
          stylers: [
			{ lightness: 100 },
           	{ visibility: "simplified" }
            ]
		},
		{ featureType: "road",
          elementType: "labels",
          stylers: [
          	{ visibility: "off" }
            ]
		}
	];
	$styles['apple'] = [
		{ "featureType": "landscape.man_made",
		  "elementType": "geometry",
		  "stylers": [
			{"color":"#f7f1df"}
			]
		},
		{ "featureType": "landscape.natural",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#d0e3b4"}
			]
		},
		{ "featureType": "landscape.natural.terrain",
		  "elementType": "geometry",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi",
		  "elementType": "labels",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi.business",
		  "elementType": "all",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "poi.medical",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#fbd3da"}
			]
		},
		{ "featureType": "poi.park",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#bde6ab"}
			]
		},
		{ "featureType": "road",
		  "elementType": "geometry.stroke",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "road",
		  "elementType": "labels",
		  "stylers": [
		  	{"visibility":"off"}
			]
		},
		{ "featureType": "road.highway",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#ffe15f"}
			]
		},
		{ "featureType": "road.highway",
		  "elementType":"geometry.stroke",
		  "stylers": [
		  	{"color":"#efd151"}
		  	]
		},
		{ "featureType": "road.arterial",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#ffffff"}
			]
		},
		{ "featureType": "road.local",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"black"}
			]
		},
		{ "featureType": "transit.station.airport",
		  "elementType": "geometry.fill",
		  "stylers": [
		  	{"color":"#cfb2db"}
			]
		},
		{ "featureType": "water",
		  "elementType": "geometry",
		  "stylers": [
		  	{"color":"#a2daf2"}
			]
		}
	];
	return $styles;
}
