<?php

/* Theme setup section
-------------------------------------------------------------------- */

// ONLY FOR PROGRAMMERS, NOT FOR CUSTOMER
// Framework settings

micro_office_storage_set('settings', array(
	
	'less_compiler'		=> 'less',								// no|lessc|less|external - Compiler for the .less
																// lessc	- fast & low memory required, but .less-map, shadows & gradients not supprted
																// less		- slow, but support all features
																// external	- used if you have external .less compiler (like WinLess or Koala)
																// no		- don't use .less, all styles stored in the theme.styles.php
	'less_nested'		=> false,								// Use nested selectors when compiling less - increase .css size, but allow using nested color schemes
	'less_prefix'		=> '',									// any string - Use prefix before each selector when compile less. For example: 'html '
	'less_split'		=> false,								// If true - load each file into memory, split it (see below) and compile separate.
																// Else - compile each file without loading to memory
	'less_separator'	=> '/*---LESS_SEPARATOR---*/',			// string - separator inside .less file to split it when compiling to reduce memory usage
																// (compilation speed gets a bit slow)
	'less_map'			=> 'no',								// no|internal|external - Generate map for .less files. 
																// Warning! You need more then 128Mb for PHP scripts on your server! Supported only if less_compiler=less (see above)
	
	'customizer_demo'	=> true,								// Show color customizer demo (if many color settings) or not (if only accent colors used)

	'allow_fullscreen'	=> false,								// Allow fullscreen and fullwide body styles

	'socials_type'		=> 'images',							// images|icons - Use this kind of pictograms for all socials: share, social profiles, team members socials, etc.
	'slides_type'		=> 'bg',								// images|bg - Use image as slide's content or as slide's background

	'add_image_size'	=> false,								// Add theme's thumb sizes into WP list sizes. 
																// If false - new image thumb will be generated on demand,
																// otherwise - all thumb sizes will be generated when image is loaded

	'use_list_cache'	=> true,								// Use cache for any lists (increase theme speed, but get 15-20K memory)
	'use_post_cache'	=> true,								// Use cache for post_data (increase theme speed, decrease queries number, but get more memory - up to 300K)

	'admin_dummy_style' => 2									// 1 | 2 - Progress bar style when import dummy data
	)
);



// Default Theme Options
if ( !function_exists( 'micro_office_options_settings_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_options_settings_theme_setup', 2 );	// Priority 1 for add micro_office_filter handlers
	function micro_office_options_settings_theme_setup() {
		
		// Clear all saved Theme Options on first theme run
		add_action('after_switch_theme', 'micro_office_options_reset');

		// Settings 
		$socials_type = micro_office_get_theme_setting('socials_type');
				
		// Prepare arrays 
		micro_office_storage_set('options_params', apply_filters('micro_office_filter_theme_options_params', array(
			'list_fonts'				=> array('$micro_office_get_list_fonts' => ''),
			'list_fonts_styles'			=> array('$micro_office_get_list_fonts_styles' => ''),
			'list_socials' 				=> array('$micro_office_get_list_socials' => ''),
			'list_icons' 				=> array('$micro_office_get_list_icons(true)' => ''),
			'list_posts_types' 			=> array('$micro_office_get_list_posts_types' => ''),
			'list_categories' 			=> array('$micro_office_get_list_categories' => ''),
			'list_menus'				=> array('$micro_office_get_list_menus(true)' => ''),
			'list_sidebars'				=> array('$micro_office_get_list_sidebars' => ''),
			'list_positions' 			=> array('$micro_office_get_list_sidebars_positions' => ''),
			'list_color_schemes'		=> array('$micro_office_get_list_color_schemes' => ''),
			'list_bg_tints'				=> array('$micro_office_get_list_bg_tints' => ''),
			'list_body_styles'			=> array('$micro_office_get_list_body_styles' => ''),
			'list_header_styles'		=> array('$micro_office_get_list_templates_header' => ''),
			'list_blog_styles'			=> array('$micro_office_get_list_templates_blog' => ''),
			'list_single_styles'		=> array('$micro_office_get_list_templates_single' => ''),
			'list_article_styles'		=> array('$micro_office_get_list_article_styles' => ''),
			'list_blog_counters' 		=> array('$micro_office_get_list_blog_counters' => ''),
			'list_menu_hovers' 			=> array('$micro_office_get_list_menu_hovers' => ''),
			'list_button_hovers'		=> array('$micro_office_get_list_button_hovers' => ''),
			'list_input_hovers'			=> array('$micro_office_get_list_input_hovers' => ''),
			'list_search_styles'		=> array('$micro_office_get_list_search_styles' => ''),
			'list_animations_in' 		=> array('$micro_office_get_list_animations_in' => ''),
			'list_animations_out'		=> array('$micro_office_get_list_animations_out' => ''),
			'list_filters'				=> array('$micro_office_get_list_portfolio_filters' => ''),
			'list_hovers'				=> array('$micro_office_get_list_hovers' => ''),
			'list_hovers_dir'			=> array('$micro_office_get_list_hovers_directions' => ''),
			'list_alter_sizes'			=> array('$micro_office_get_list_alter_sizes' => ''),
			'list_sliders' 				=> array('$micro_office_get_list_sliders' => ''),
			'list_bg_image_positions'	=> array('$micro_office_get_list_bg_image_positions' => ''),
			'list_popups' 				=> array('$micro_office_get_list_popup_engines' => ''),
			'list_gmap_styles'		 	=> array('$micro_office_get_list_googlemap_styles' => ''),
			'list_yes_no' 				=> array('$micro_office_get_list_yesno' => ''),
			'list_on_off' 				=> array('$micro_office_get_list_onoff' => ''),
			'list_show_hide' 			=> array('$micro_office_get_list_showhide' => ''),
			'list_sorting' 				=> array('$micro_office_get_list_sortings' => ''),
			'list_ordering' 			=> array('$micro_office_get_list_orderings' => ''),
			'list_locations' 			=> array('$micro_office_get_list_dedicated_locations' => '')
			)
		));


		// Theme options array
		micro_office_storage_set('options', array(

		
		//###############################
		//#### Customization         #### 
		//###############################
		'partition_customization' => array(
					"title" => esc_html__('Customization', 'micro-office'),
					"start" => "partitions",
					"override" => "category,services_group,post,page,custom",
					"icon" => "iconadmin-cog-alt",
					"type" => "partition"
					),
		
		
		// Customization -> Body Style
		//-------------------------------------------------
		
		'customization_body' => array(
					"title" => esc_html__('Body style', 'micro-office'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-picture',
					"start" => "customization_tabs",
					"type" => "tab"
					),
		
		'info_body_1' => array(
					"title" => esc_html__('Body parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Select body style and color scheme for entire site. You can override this parameters on any page, post or category', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'body_type' => array(
					"title" => esc_html__('Body type', 'micro-office'),
					"desc" => wp_kses_data( __('Select body type:', 'micro-office') )
								. ' <br>' 
								. wp_kses_data( __('<b>type 1</b> - light header', 'micro-office') )
								. ',<br>'
								. wp_kses_data( __('<b>type 2</b> - dark header', 'micro-office') ),
					"info" => true,
					"override" => "category,services_group,post,page,custom",
					"std" => "1",
					"options" =>  array(
						'1'	=> esc_html__('Type 1', 'micro-office'),
						'2'	=> esc_html__('Type 2', 'micro-office')
						),
					"dir" => "horizontal",
					"type" => "radio"
		),
		"body_scheme" => array(
					"title" => esc_html__('Color scheme', 'micro-office'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the entire page', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "original",
					"dir" => "horizontal",
					"options" => micro_office_get_options_param('list_color_schemes'),
					"type" => "checklist"),
					
					
		// Customization -> Header
		//-------------------------------------------------
		
		'customization_header' => array(
					"title" => esc_html__("Header", 'micro-office'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		"info_header_1" => array(
					"title" => esc_html__('Top panel', 'micro-office'),
					"desc" => wp_kses_data( __('Top panel settings. It include user menu area (with contact info, cart button, language selector, login/logout menu and user menu) and main menu area (with logo and main menu).', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_page_title" => array(
					"title" => esc_html__('Show Page title', 'micro-office'),
					"desc" => wp_kses_data( __('Show post/page/category title', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_breadcrumbs" => array(
					"title" => esc_html__('Show Breadcrumbs', 'micro-office'),
					"desc" => wp_kses_data( __('Show path to current category (post, page)', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"breadcrumbs_max_level" => array(
					"title" => esc_html__('Breadcrumbs max nesting', 'micro-office'),
					"desc" => wp_kses_data( __("Max number of the nested categories in the breadcrumbs (0 - unlimited)", 'micro-office') ),
					"dependency" => array(
						'show_breadcrumbs' => array('yes')
					),
					"std" => "0",
					"min" => 0,
					"max" => 100,
					"step" => 1,
					"type" => "spinner"),

		
		
		
		"info_header_2" => array( 
					"title" => esc_html__('Main menu style and position', 'micro-office'),
					"desc" => wp_kses_data( __('Select the Main menu style and position', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"menu_main" => array( 
					"title" => esc_html__('Select main menu',  'micro-office'),
					"desc" => wp_kses_data( __('Select main menu for the current page',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"options" => micro_office_get_options_param('list_menus'),
					"type" => "select"),
	
		'info_header_5' => array(
					"title" => esc_html__('Main logo', 'micro-office'),
					"desc" => wp_kses_data( __("Select or upload logos for the site's header and select it position", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),

		'logo' => array(
					"title" => esc_html__('Logo image', 'micro-office'),
					"desc" => wp_kses_data( __('Main logo image', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_retina' => array(
					"title" => esc_html__('Logo image for Retina', 'micro-office'),
					"desc" => wp_kses_data( __('Main logo image used on Retina display', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "",
					"type" => "media"
					),

		'logo_text' => array(
					"title" => esc_html__('Logo text', 'micro-office'),
					"desc" => wp_kses_data( __('Logo text - display it after logo image', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => '',
					"type" => "text"
					),

		'logo_height' => array(
					"title" => esc_html__('Logo height', 'micro-office'),
					"desc" => wp_kses_data( __('Height for the logo in the header area', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"step" => 1,
					"std" => '',
					"min" => 10,
					"max" => 300,
					"mask" => "?999",
					"type" => "spinner"
					),
					
		
		// Customization -> Sidebars
		//-------------------------------------------------
		
		"customization_sidebars" => array( 
					"title" => esc_html__('Sidebars', 'micro-office'),
					"icon" => "iconadmin-indent-right",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_sidebars_1" => array( 
					"title" => esc_html__('Custom sidebars', 'micro-office'),
					"desc" => wp_kses_data( __('In this section you can create unlimited sidebars. You can fill them with widgets in the menu Appearance - Widgets', 'micro-office') ),
					"type" => "info"),
		
		"custom_sidebars" => array(
					"title" => esc_html__('Custom sidebars',  'micro-office'),
					"desc" => wp_kses_data( __('Manage custom sidebars. You can use it with each category (page, post) independently',  'micro-office') ),
					"std" => "",
					"cloneable" => true,
					"type" => "text"),
		
		"info_sidebars_2" => array(
					"title" => esc_html__('Main sidebar', 'micro-office'),
					"desc" => wp_kses_data( __('Show / Hide and select main sidebar', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_main' => array( 
					"title" => esc_html__('Show main sidebar',  'micro-office'),
					"desc" => wp_kses_data( __('Select position for the main sidebar or hide it',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"options" => array(
						'hide'    => esc_html__('Hide',  'micro-office'),
						'show' 	=> esc_html__('Show', 'micro-office')
					),
					"std" => "show",
					"dir" => "horizontal",
					"type" => "checklist"),

		"sidebar_main_scheme" => array(
					"title" => esc_html__("Color scheme", 'micro-office'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the main sidebar', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('show')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => micro_office_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_main" => array( 
					"title" => esc_html__('Select main sidebar',  'micro-office'),
					"desc" => wp_kses_data( __('Select main sidebar content',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_main' => array('show')
					),
					"std" => "sidebar_main",
					"options" => micro_office_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"info_sidebars_3" => array(
					"title" => esc_html__('Outer sidebar', 'micro-office'),
					"desc" => wp_kses_data( __('Show / Hide and select outer sidebar (sidemenu, logo, etc.', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		'show_sidebar_outer' => array( 
					"title" => esc_html__('Show outer sidebar',  'micro-office'),
					"desc" => wp_kses_data( __('Select position for the outer sidebar or hide it',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"options" => array(
						'hide'    => esc_html__('Hide',  'micro-office'),
						'show' => esc_html__('Show', 'micro-office')
					),
					"std" => "show",
					"dir" => "horizontal",
					"type" => "checklist"),

	
		
		
		
		// Customization -> Footer
		//-------------------------------------------------
		
		'customization_footer' => array(
					"title" => esc_html__("Footer", 'micro-office'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-window',
					"type" => "tab"),
		
		
		"info_footer_1" => array(
					"title" => esc_html__("Footer components", 'micro-office'),
					"desc" => wp_kses_data( __("Select components of the footer, set style and put the content for the user's footer area", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"show_sidebar_footer" => array(
					"title" => esc_html__('Show footer sidebar', 'micro-office'),
					"desc" => wp_kses_data( __('Select style for the footer sidebar or hide it', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"sidebar_footer_scheme" => array(
					"title" => esc_html__("Color scheme", 'micro-office'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the footer', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => micro_office_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"sidebar_footer" => array( 
					"title" => esc_html__('Select footer sidebar',  'micro-office'),
					"desc" => wp_kses_data( __('Select footer sidebar for the blog page',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => "sidebar_footer",
					"options" => micro_office_get_options_param('list_sidebars'),
					"type" => "select"),
		
		"sidebar_footer_columns" => array( 
					"title" => esc_html__('Footer sidebar columns',  'micro-office'),
					"desc" => wp_kses_data( __('Select columns number for the footer sidebar',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_sidebar_footer' => array('yes')
					),
					"std" => 3,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),
		
		"info_footer_6" => array(
					"title" => esc_html__("Copyright and footer menu", 'micro-office'),
					"desc" => wp_kses_data( __("Show/Hide copyright area in the footer", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),

		"show_copyright_in_footer" => array(
					"title" => esc_html__('Show Copyright area in footer', 'micro-office'),
					"desc" => wp_kses_data( __('Show area with copyright information, footer menu and small social icons in footer', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "plain",
					"options" => array(
						'none' => esc_html__('Hide', 'micro-office'),
						'text' => esc_html__('Text', 'micro-office'),
						'menu' => esc_html__('Text and menu', 'micro-office'),
						'socials' => esc_html__('Text and Social icons', 'micro-office')
					),
					"type" => "checklist"),

		"copyright_scheme" => array(
					"title" => esc_html__("Color scheme", 'micro-office'),
					"desc" => wp_kses_data( __('Select predefined color scheme for the copyright area', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"std" => "original",
					"dir" => "horizontal",
					"options" => micro_office_get_options_param('list_color_schemes'),
					"type" => "checklist"),
		
		"menu_footer" => array( 
					"title" => esc_html__('Select footer menu',  'micro-office'),
					"desc" => wp_kses_data( __('Select footer menu for the current page',  'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "default",
					"dependency" => array(
						'show_copyright_in_footer' => array('menu')
					),
					"options" => micro_office_get_options_param('list_menus'),
					"type" => "select"),

		"footer_copyright" => array(
					"title" => esc_html__('Footer copyright text',  'micro-office'),
					"desc" => wp_kses_data( __("Copyright text to show in footer area (bottom of site)", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'show_copyright_in_footer' => array('text', 'menu', 'socials')
					),
					"allow_html" => true,
					"std" => "Micro Office &copy; 2016 All Rights Reserved ",
					"rows" => "10",
					"type" => "editor"),




		// Customization -> Other
		//-------------------------------------------------
		
		'customization_other' => array(
					"title" => esc_html__('Other', 'micro-office'),
					"override" => "category,services_group,post,page,custom",
					"icon" => 'iconadmin-cog',
					"type" => "tab"
					),

		'info_other_1' => array(
					"title" => esc_html__('Theme customization other parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Animation parameters and responsive layouts for the small screens', 'micro-office') ),
					"type" => "info"
					),
					
		'login_descr' => array(
					"title" => esc_html__('Login text', 'micro-office'),
					"desc" => wp_kses_data( __('Type some text to display on login page', 'micro-office') ),
					"std" => "",
					"type" => "textarea"),
					
		'css_animation' => array(
					"title" => esc_html__('Extended CSS animations', 'micro-office'),
					"desc" => wp_kses_data( __('Do you want use extended animations effects on your site?', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		'animation_on_mobile' => array(
					"title" => esc_html__('Allow CSS animations on mobile', 'micro-office'),
					"desc" => wp_kses_data( __('Do you allow extended animations effects on mobile devices?', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"
					),


		'remember_visitors_settings' => array(
					"title" => esc_html__("Remember visitor's settings", 'micro-office'),
					"desc" => wp_kses_data( __('To remember the settings that were made by the visitor, when navigating to other pages or to limit their effect only within the current page', 'micro-office') ),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"
					),
					
		'responsive_layouts' => array(
					"title" => esc_html__('Responsive Layouts', 'micro-office'),
					"desc" => wp_kses_data( __('Do you want use responsive layouts on small screen or still use main layout?', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"
					),

		"page_preloader" => array( 
					"title" => esc_html__("Show page preloader", 'micro-office'),
					"desc" => wp_kses_data( __("Select one of predefined styles for the page preloader or upload preloader image", 'micro-office') ),
					"std" => "none",
					"type" => "select",
					"options" => array(
						'none'   => esc_html__('Hide preloader', 'micro-office'),
						'circle' => esc_html__('Circle', 'micro-office'),
						'square' => esc_html__('Square', 'micro-office'),
						'custom' => esc_html__('Custom', 'micro-office'),
					)),
		
		'page_preloader_image' => array(
					"title" => esc_html__('Upload preloader image',  'micro-office'),
					"desc" => wp_kses_data( __('Upload animated GIF to use it as page preloader',  'micro-office') ),
					"dependency" => array(
						'page_preloader' => array('custom')
					),
					"std" => "",
					"type" => "media"
					),


		'info_other_2' => array(
					"title" => esc_html__('Google fonts parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Specify additional parameters, used to load Google fonts', 'micro-office') ),
					"type" => "info"
					),
		
		"fonts_subset" => array(
					"title" => esc_html__('Characters subset', 'micro-office'),
					"desc" => wp_kses_data( __('Select subset, included into used Google fonts', 'micro-office') ),
					"std" => "latin,latin-ext",
					"options" => array(
						'latin' => esc_html__('Latin', 'micro-office'),
						'latin-ext' => esc_html__('Latin Extended', 'micro-office'),
						'greek' => esc_html__('Greek', 'micro-office'),
						'greek-ext' => esc_html__('Greek Extended', 'micro-office'),
						'cyrillic' => esc_html__('Cyrillic', 'micro-office'),
						'cyrillic-ext' => esc_html__('Cyrillic Extended', 'micro-office'),
						'vietnamese' => esc_html__('Vietnamese', 'micro-office')
					),
					"size" => "medium",
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),


		'info_other_3' => array(
					"title" => esc_html__('Additional CSS and HTML/JS code', 'micro-office'),
					"desc" => wp_kses_data( __('Put here your custom CSS and JS code', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"
					),
					
		'custom_css_html' => array(
					"title" => esc_html__('Use custom JS', 'micro-office'),
					"desc" => wp_kses_data( __('Do you want use custom JS code in your site? For Google Analitics code, etc.', 'micro-office') ),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"
					),
		
		"gtm_code" => array(
					"title" => esc_html__('Google tags manager or Google analitics code',  'micro-office'),
					"desc" => wp_kses_data( __('Put here Google Tags Manager (GTM) code from your account: Google analitics, remarketing, etc. This code will be placed after open body tag.',  'micro-office') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		"gtm_code2" => array(
					"title" => esc_html__('Google remarketing code',  'micro-office'),
					"desc" => wp_kses_data( __('Put here Google Remarketing code from your account. This code will be placed before close body tag.',  'micro-office') ),
					"dependency" => array(
						'custom_css_html' => array('yes')
					),
					"divider" => false,
					"cols" => 80,
					"rows" => 20,
					"std" => "",
					"allow_html" => true,
					"allow_js" => true,
					"type" => "textarea"),
		
		
		
		
		
		//###############################
		//#### Blog and Single pages #### 
		//###############################
		"partition_blog" => array(
					"title" => esc_html__('Blog &amp; Single', 'micro-office'),
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		
		
		// Blog -> Stream page
		//-------------------------------------------------
		
		'blog_tab_stream' => array(
					"title" => esc_html__('Stream page', 'micro-office'),
					"start" => 'blog_tabs',
					"icon" => "iconadmin-docs",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		"info_blog_1" => array(
					"title" => esc_html__('Blog streampage parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Select desired blog streampage parameters (you can override it in each category)', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
		
		"blog_style" => array(
					"title" => esc_html__('Blog style', 'micro-office'),
					"desc" => wp_kses_data( __('Select desired blog style', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "excerpt",
					"options" => micro_office_get_options_param('list_blog_styles'),
					"type" => "select"),
		
		"hover_style" => array(
					"title" => esc_html__('Hover style', 'micro-office'),
					"desc" => wp_kses_data( __('Select desired hover style (only for Blog style = Portfolio)', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "square effect_shift",
					"options" => micro_office_get_options_param('list_hovers'),
					"type" => "select"),
		
		"hover_dir" => array(
					"title" => esc_html__('Hover dir', 'micro-office'),
					"desc" => wp_kses_data( __('Select hover direction (only for Blog style = Portfolio and Hover style = Circle or Square)', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored'),
						'hover_style' => array('square','circle')
					),
					"std" => "left_to_right",
					"options" => micro_office_get_options_param('list_hovers_dir'),
					"type" => "select"),
		
		"article_style" => array(
					"title" => esc_html__('Article style', 'micro-office'),
					"desc" => wp_kses_data( __('Select article display method: boxed or stretch', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "stretch",
					"options" => micro_office_get_options_param('list_article_styles'),
					"size" => "medium",
					"type" => "switch"),
		
		"dedicated_location" => array(
					"title" => esc_html__('Dedicated location', 'micro-office'),
					"desc" => wp_kses_data( __('Select location for the dedicated content or featured image in the "excerpt" blog style', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt')
					),
					"std" => "default",
					"options" => micro_office_get_options_param('list_locations'),
					"type" => "select"),
		
		"show_filters" => array(
					"title" => esc_html__('Show filters', 'micro-office'),
					"desc" => wp_kses_data( __('What taxonomy use for filter buttons', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('portfolio','grid','square','colored')
					),
					"std" => "hide",
					"options" => micro_office_get_options_param('list_filters'),
					"type" => "checklist"),
		
		"blog_sort" => array(
					"title" => esc_html__('Blog posts sorted by', 'micro-office'),
					"desc" => wp_kses_data( __('Select the desired sorting method for posts', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "date",
					"options" => micro_office_get_options_param('list_sorting'),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_order" => array(
					"title" => esc_html__('Blog posts order', 'micro-office'),
					"desc" => wp_kses_data( __('Select the desired ordering method for posts', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "desc",
					"options" => micro_office_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		"posts_per_page" => array(
					"title" => esc_html__('Blog posts per page',  'micro-office'),
					"desc" => wp_kses_data( __('How many posts display on blog pages for selected style. If empty or 0 - inherit system wordpress settings',  'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "12",
					"mask" => "?99",
					"type" => "text"),
		
		"post_excerpt_maxlength" => array(
					"title" => esc_html__('Excerpt maxlength for streampage',  'micro-office'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Excerpt). 0 - do not trim excerpt.',  'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('excerpt', 'portfolio', 'grid', 'square', 'related')
					),
					"std" => "250",
					"mask" => "?9999",
					"type" => "text"),
		
		"post_excerpt_maxlength_masonry" => array(
					"title" => esc_html__('Excerpt maxlength for classic and masonry',  'micro-office'),
					"desc" => wp_kses_data( __('How many characters from post excerpt are display in blog streampage (only for Blog style = Classic or Masonry). 0 - do not trim excerpt.',  'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'blog_style' => array('masonry', 'classic')
					),
					"std" => "150",
					"mask" => "?9999",
					"type" => "text"),
		
		
		
		
		// Blog -> Single page
		//-------------------------------------------------
		
		'blog_tab_single' => array(
					"title" => esc_html__('Single page', 'micro-office'),
					"icon" => "iconadmin-doc",
					"override" => "category,services_group,post,page,custom",
					"type" => "tab"),
		
		
		"info_single_1" => array(
					"title" => esc_html__('Single (detail) pages parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Select desired parameters for single (detail) pages (you can override it in each category and single post (page))', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"type" => "info"),
					
		"single_style" => array(
					"title" => esc_html__('Single page style', 'micro-office'),
					"desc" => wp_kses_data( __('Select desired style for single page', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "single-standard",
					"options" => micro_office_get_options_param('list_single_styles'),
					"dir" => "horizontal",
					"type" => "radio"),			
		
		"show_featured_image" => array(
					"title" => esc_html__('Show featured image before post',  'micro-office'),
					"desc" => wp_kses_data( __("Show featured image (if selected) before post content on single pages", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title" => array(
					"title" => esc_html__('Show post title', 'micro-office'),
					"desc" => wp_kses_data( __('Show area with post title on single pages', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_title_on_quotes" => array(
					"title" => esc_html__('Show post title on links, chat, quote, status', 'micro-office'),
					"desc" => wp_kses_data( __('Show area with post title on single and blog pages in specific post formats: links, chat, quote, status', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_info" => array(
					"title" => esc_html__('Show post info', 'micro-office'),
					"desc" => wp_kses_data( __('Show area with post info on single pages', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_text_before_readmore" => array(
					"title" => esc_html__('Show text before "Read more" tag', 'micro-office'),
					"desc" => wp_kses_data( __('Show text before "Read more" tag on single pages', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
					
		"show_post_author" => array(
					"title" => esc_html__('Show post author details',  'micro-office'),
					"desc" => wp_kses_data( __("Show post author information block on single post page", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_tags" => array(
					"title" => esc_html__('Show post tags',  'micro-office'),
					"desc" => wp_kses_data( __("Show tags block on single post page", 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"show_post_related" => array(
					"title" => esc_html__('Show related posts',  'micro-office'),
					"desc" => wp_kses_data( __("Show related posts block on single post page", 'micro-office') ),
					"override" => "category,services_group,post,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"post_related_count" => array(
					"title" => esc_html__('Related posts number',  'micro-office'),
					"desc" => wp_kses_data( __("How many related posts showed on single post page", 'micro-office') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"override" => "category,services_group,post,custom",
					"std" => "2",
					"step" => 1,
					"min" => 2,
					"max" => 8,
					"type" => "spinner"),

		"post_related_columns" => array(
					"title" => esc_html__('Related posts columns',  'micro-office'),
					"desc" => wp_kses_data( __("How many columns used to show related posts on single post page. 1 - use scrolling to show all related posts", 'micro-office') ),
					"override" => "category,services_group,post,custom",
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "2",
					"step" => 1,
					"min" => 1,
					"max" => 4,
					"type" => "spinner"),
		
		"post_related_sort" => array(
					"title" => esc_html__('Related posts sorted by', 'micro-office'),
					"desc" => wp_kses_data( __('Select the desired sorting method for related posts', 'micro-office') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "date",
					"options" => micro_office_get_options_param('list_sorting'),
					"type" => "select"),
		
		"post_related_order" => array(
					"title" => esc_html__('Related posts order', 'micro-office'),
					"desc" => wp_kses_data( __('Select the desired ordering method for related posts', 'micro-office') ),
					"dependency" => array(
						'show_post_related' => array('yes')
					),
					"std" => "desc",
					"options" => micro_office_get_options_param('list_ordering'),
					"size" => "big",
					"type" => "switch"),
		
		
		
		// Blog -> Other parameters
		//-------------------------------------------------
		
		'blog_tab_other' => array(
					"title" => esc_html__('Other parameters', 'micro-office'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,page,custom",
					"type" => "tab"),
		
		"info_blog_other_1" => array(
					"title" => esc_html__('Other Blog parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Select excluded categories, substitute parameters, etc.', 'micro-office') ),
					"type" => "info"),
					
		"cat_color" => array(
					"title" => esc_html__("Category color", "micro-office"),
					"desc" => wp_kses_data( __("Category color", "micro-office") ),
					"override" => "category,courses_group",
					"std" => "#1ebeb4",
					"type" => "color"
				),
				
		"exclude_cats" => array(
					"title" => esc_html__('Exclude categories', 'micro-office'),
					"desc" => wp_kses_data( __('Select categories, which posts are exclude from blog page', 'micro-office') ),
					"std" => "",
					"options" => micro_office_get_options_param('list_categories'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"blog_pagination" => array(
					"title" => esc_html__('Blog pagination', 'micro-office'),
					"desc" => wp_kses_data( __('Select type of the pagination on blog streampages', 'micro-office') ),
					"std" => "pages",
					"override" => "category,services_group,page,custom",
					"options" => array(
						'pages'    => esc_html__('Standard page numbers', 'micro-office'),
						'slider'   => esc_html__('Slider with page numbers', 'micro-office'),
						'viewmore' => esc_html__('"View more" button', 'micro-office'),
						'infinite' => esc_html__('Infinite scroll', 'micro-office')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"blog_counters" => array(
					"title" => esc_html__('Blog counters', 'micro-office'),
					"desc" => wp_kses_data( __('Select counters, displayed near the post title', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "",
					"options" => micro_office_get_options_param('list_blog_counters'),
					"dir" => "vertical",
					"multiple" => true,
					"type" => "checklist"),
		
		"close_category" => array(
					"title" => esc_html__("Post's category announce", 'micro-office'),
					"desc" => wp_kses_data( __('What category display in announce block (over posts thumb) - original or nearest parental', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "parental",
					"options" => array(
						'parental' => esc_html__('Nearest parental category', 'micro-office'),
						'original' => esc_html__("Original post's category", 'micro-office')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"show_date_after" => array(
					"title" => esc_html__('Show post date after', 'micro-office'),
					"desc" => wp_kses_data( __('Show post date after N days (before - show post age)', 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "30",
					"mask" => "?99",
					"type" => "text"),
		
		
		
		
		
		//###############################
		//#### Reviews               #### 
		//###############################
		"partition_reviews" => array(
					"title" => esc_html__('Reviews', 'micro-office'),
					"icon" => "iconadmin-newspaper",
					"override" => "category,services_group,services_group",
					"type" => "partition"),
		
		"info_reviews_1" => array(
					"title" => esc_html__('Reviews criterias', 'micro-office'),
					"desc" => wp_kses_data( __('Set up list of reviews criterias. You can override it in any category.', 'micro-office') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
		
		"show_reviews" => array(
					"title" => esc_html__('Show reviews block',  'micro-office'),
					"desc" => wp_kses_data( __("Show reviews block on single post page and average reviews rating after post's title in stream pages", 'micro-office') ),
					"override" => "category,services_group,services_group",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"reviews_max_level" => array(
					"title" => esc_html__('Max reviews level',  'micro-office'),
					"desc" => wp_kses_data( __("Maximum level for reviews marks", 'micro-office') ),
					"std" => "5",
					"options" => array(
						'5'=>esc_html__('5 stars', 'micro-office'), 
						'10'=>esc_html__('10 stars', 'micro-office'), 
						'100'=>esc_html__('100%', 'micro-office')
					),
					"type" => "radio",
					),
		
		"reviews_style" => array(
					"title" => esc_html__('Show rating as',  'micro-office'),
					"desc" => wp_kses_data( __("Show rating marks as text or as stars/progress bars.", 'micro-office') ),
					"std" => "stars",
					"options" => array(
						'text' => esc_html__('As text (for example: 7.5 / 10)', 'micro-office'), 
						'stars' => esc_html__('As stars or bars', 'micro-office')
					),
					"dir" => "vertical",
					"type" => "radio"),
		
		"reviews_criterias_levels" => array(
					"title" => esc_html__('Reviews Criterias Levels', 'micro-office'),
					"desc" => wp_kses_data( __('Words to mark criterials levels. Just write the word and press "Enter". Also you can arrange words.', 'micro-office') ),
					"std" => esc_html__("bad,poor,normal,good,great", 'micro-office'),
					"type" => "tags"),
		
		"reviews_first" => array(
					"title" => esc_html__('Show first reviews',  'micro-office'),
					"desc" => wp_kses_data( __("What reviews will be displayed first: by author or by visitors. Also this type of reviews will display under post's title.", 'micro-office') ),
					"std" => "author",
					"options" => array(
						'author' => esc_html__('By author', 'micro-office'),
						'users' => esc_html__('By visitors', 'micro-office')
						),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_second" => array(
					"title" => esc_html__('Hide second reviews',  'micro-office'),
					"desc" => wp_kses_data( __("Do you want hide second reviews tab in widgets and single posts?", 'micro-office') ),
					"std" => "show",
					"options" => micro_office_get_options_param('list_show_hide'),
					"size" => "medium",
					"type" => "switch"),
		
		"reviews_can_vote" => array(
					"title" => esc_html__('What visitors can vote',  'micro-office'),
					"desc" => wp_kses_data( __("What visitors can vote: all or only registered", 'micro-office') ),
					"std" => "all",
					"options" => array(
						'all'=>esc_html__('All visitors', 'micro-office'), 
						'registered'=>esc_html__('Only registered', 'micro-office')
					),
					"dir" => "horizontal",
					"type" => "radio"),
		
		"reviews_criterias" => array(
					"title" => esc_html__('Reviews criterias',  'micro-office'),
					"desc" => wp_kses_data( __('Add default reviews criterias.',  'micro-office') ),
					"override" => "category,services_group,services_group",
					"std" => "",
					"cloneable" => true,
					"type" => "text"),

		// Don't remove this parameter - it used in admin for store marks
		"reviews_marks" => array(
					"std" => "",
					"type" => "hidden"),
		





		//###############################
		//#### Media                #### 
		//###############################
		"partition_media" => array(
					"title" => esc_html__('Media', 'micro-office'),
					"icon" => "iconadmin-picture",
					"override" => "category,services_group,post,page,custom",
					"type" => "partition"),
		
		"info_media_1" => array(
					"title" => esc_html__('Media settings', 'micro-office'),
					"desc" => wp_kses_data( __('Set up parameters to show images, galleries, audio and video posts', 'micro-office') ),
					"override" => "category,services_group,services_group",
					"type" => "info"),
					
		"retina_ready" => array(
					"title" => esc_html__('Image dimensions', 'micro-office'),
					"desc" => wp_kses_data( __('What dimensions use for uploaded image: Original or "Retina ready" (twice enlarged)', 'micro-office') ),
					"std" => "1",
					"size" => "medium",
					"options" => array(
						"1" => esc_html__("Original", 'micro-office'), 
						"2" => esc_html__("Retina", 'micro-office')
					),
					"type" => "switch"),
		
		"images_quality" => array(
					"title" => esc_html__('Quality for cropped images', 'micro-office'),
					"desc" => wp_kses_data( __('Quality (1-100) to save cropped images', 'micro-office') ),
					"std" => "70",
					"min" => 1,
					"max" => 100,
					"type" => "spinner"),
		
		"substitute_gallery" => array(
					"title" => esc_html__('Substitute standard Wordpress gallery', 'micro-office'),
					"desc" => wp_kses_data( __('Substitute standard Wordpress gallery with our slider on the single pages', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_instead_image" => array(
					"title" => esc_html__('Show gallery instead featured image', 'micro-office'),
					"desc" => wp_kses_data( __('Show slider with gallery instead featured image on blog streampage and in the related posts section for the gallery posts', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"gallery_max_slides" => array(
					"title" => esc_html__('Max images number in the slider', 'micro-office'),
					"desc" => wp_kses_data( __('Maximum images number from gallery into slider', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"dependency" => array(
						'gallery_instead_image' => array('yes')
					),
					"std" => "5",
					"min" => 2,
					"max" => 10,
					"type" => "spinner"),
		
		"popup_engine" => array(
					"title" => esc_html__('Popup engine to zoom images', 'micro-office'),
					"desc" => wp_kses_data( __('Select engine to show popup windows with images and galleries', 'micro-office') ),
					"std" => "magnific",
					"options" => micro_office_get_options_param('list_popups'),
					"type" => "select"),
		
		"substitute_audio" => array(
					"title" => esc_html__('Substitute audio tags', 'micro-office'),
					"desc" => wp_kses_data( __('Substitute audio tag with source from soundcloud to embed player', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"substitute_video" => array(
					"title" => esc_html__('Substitute video tags', 'micro-office'),
					"desc" => wp_kses_data( __('Substitute video tags with embed players or leave video tags unchanged (if you use third party plugins for the video tags)', 'micro-office') ),
					"override" => "category,services_group,post,page,custom",
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"use_mediaelement" => array(
					"title" => esc_html__('Use Media Element script for audio and video tags', 'micro-office'),
					"desc" => wp_kses_data( __('Do you want use the Media Element script for all audio and video tags on your site or leave standard HTML5 behaviour?', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		//###############################
		//#### Socials               #### 
		//###############################
		"partition_socials" => array(
					"title" => esc_html__('Socials', 'micro-office'),
					"icon" => "iconadmin-users",
					"override" => "category,services_group,page,custom",
					"type" => "partition"),
		
		"info_socials_1" => array(
					"title" => esc_html__('Social networks', 'micro-office'),
					"desc" => wp_kses_data( __("Social networks list for site footer and Social widget", 'micro-office') ),
					"type" => "info"),
		
		"social_icons" => array(
					"title" => esc_html__('Social networks',  'micro-office'),
					"desc" => wp_kses_data( __('Select icon and write URL to your profile in desired social networks.',  'micro-office') ),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? micro_office_get_options_param('list_socials') : micro_office_get_options_param('list_icons'),
					"type" => "socials"),
		
		"info_socials_2" => array(
					"title" => esc_html__('Share buttons', 'micro-office'),
					"desc" => wp_kses_data( __("Add button's code for each social share network.<br>
					In share url you can use next macro:<br>
					<b>{url}</b> - share post (page) URL,<br>
					<b>{title}</b> - post title,<br>
					<b>{image}</b> - post image,<br>
					<b>{descr}</b> - post description (if supported)<br>
					For example:<br>
					<b>Facebook</b> share string: <em>http://www.facebook.com/sharer.php?u={link}&amp;t={title}</em><br>
					<b>Delicious</b> share string: <em>http://delicious.com/save?url={link}&amp;title={title}&amp;note={descr}</em>", 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"type" => "info"),
		
		"show_share" => array(
					"title" => esc_html__('Show social share buttons',  'micro-office'),
					"desc" => wp_kses_data( __("Show social share buttons block", 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"std" => "horizontal",
					"options" => array(
						'hide'		=> esc_html__('Hide', 'micro-office'),
						'vertical'	=> esc_html__('Vertical', 'micro-office'),
						'horizontal'=> esc_html__('Horizontal', 'micro-office')
					),
					"type" => "checklist"),

		"show_share_counters" => array(
					"title" => esc_html__('Show share counters',  'micro-office'),
					"desc" => wp_kses_data( __("Show share counters after social buttons", 'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"share_caption" => array(
					"title" => esc_html__('Share block caption',  'micro-office'),
					"desc" => wp_kses_data( __('Caption for the block with social share buttons',  'micro-office') ),
					"override" => "category,services_group,page,custom",
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => esc_html__('Share:', 'micro-office'),
					"type" => "text"),
		
		"share_buttons" => array(
					"title" => esc_html__('Share buttons',  'micro-office'),
					"desc" => wp_kses_data( __('Select icon and write share URL for desired social networks.<br><b>Important!</b> If you leave text field empty - internal theme link will be used (if present).',  'micro-office') ),
					"dependency" => array(
						'show_share' => array('vertical', 'horizontal')
					),
					"std" => array(array('url'=>'', 'icon'=>'')),
					"cloneable" => true,
					"size" => "small",
					"style" => $socials_type,
					"options" => $socials_type=='images' ? micro_office_get_options_param('list_socials') : micro_office_get_options_param('list_icons'),
					"type" => "socials"),
		
		
		"info_socials_3" => array(
					"title" => esc_html__('Twitter API keys', 'micro-office'),
					"desc" => wp_kses_data( __("Put to this section Twitter API 1.1 keys.<br>You can take them after registration your application in <strong>https://apps.twitter.com/</strong>", 'micro-office') ),
					"type" => "info"),
		
		"twitter_username" => array(
					"title" => esc_html__('Twitter username',  'micro-office'),
					"desc" => wp_kses_data( __('Your login (username) in Twitter',  'micro-office') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_key" => array(
					"title" => esc_html__('Consumer Key',  'micro-office'),
					"desc" => wp_kses_data( __('Twitter API Consumer key',  'micro-office') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_consumer_secret" => array(
					"title" => esc_html__('Consumer Secret',  'micro-office'),
					"desc" => wp_kses_data( __('Twitter API Consumer secret',  'micro-office') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_key" => array(
					"title" => esc_html__('Token Key',  'micro-office'),
					"desc" => wp_kses_data( __('Twitter API Token key',  'micro-office') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"twitter_token_secret" => array(
					"title" => esc_html__('Token Secret',  'micro-office'),
					"desc" => wp_kses_data( __('Twitter API Token secret',  'micro-office') ),
					"divider" => false,
					"std" => "",
					"type" => "text"),
		
		"info_socials_4" => array(
					"title" => esc_html__('Google API Keys', 'micro-office'),
					"desc" => wp_kses_data( __('API Keys for some Web services', 'micro-office') ),
					"type" => "info"),
		'api_google' => array(
					"title" => esc_html__('Google API Key for browsers', 'micro-office'),
					"desc" => wp_kses_data( __("Insert Google API Key for browsers into the field above to generate Google Maps", 'micro-office') ),
					"std" => "",
					"type" => "text"),
		
		"info_socials_5" => array(
					"title" => esc_html__('Login via Socials', 'micro-office'),
					"desc" => wp_kses_data( __('Settings for the Login via Social networks', 'micro-office') ),
					"type" => "info"),
		
		"social_login" => array(
					"title" => esc_html__('Shortcode or any HTML/JS code',  'micro-office'),
					"desc" => wp_kses_data( __('Specify shortcode from your Social Login Plugin or any HTML/JS code to make Social Login section',  'micro-office') ),
					"std" => "",
					"type" => "textarea"),
		
		
		
		
		//###############################
		//#### Contact info          #### 
		//###############################
		"partition_contacts" => array(
					"title" => esc_html__('Contact info', 'micro-office'),
					"icon" => "iconadmin-mail",
					"type" => "partition"),
		
		
		"info_contact_2" => array(
					"title" => esc_html__('Contact and Comments form', 'micro-office'),
					"desc" => wp_kses_data( __('Maximum length of the messages in the contact form shortcode and in the comments form', 'micro-office') ),
					"type" => "info"),
		
		"message_maxlength_contacts" => array(
					"title" => esc_html__('Contact form message', 'micro-office'),
					"desc" => wp_kses_data( __("Message's maxlength in the contact form shortcode", 'micro-office') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"message_maxlength_comments" => array(
					"title" => esc_html__('Comments form message', 'micro-office'),
					"desc" => wp_kses_data( __("Message's maxlength in the comments form", 'micro-office') ),
					"std" => "1000",
					"min" => 0,
					"max" => 10000,
					"step" => 100,
					"type" => "spinner"),
		
		"info_contact_3" => array(
					"title" => esc_html__('Default mail function', 'micro-office'),
					"desc" => wp_kses_data( __('What function use to send mail: the built-in Wordpress wp_mail() or standard PHP mail() function? Attention! Some plugins may not work with one of them and you always have the ability to switch to alternative.', 'micro-office') ),
					"type" => "info"),
		
		"mail_function" => array(
					"title" => esc_html__("Mail function", 'micro-office'),
					"desc" => wp_kses_data( __("What function use to send mail? Attention! Only wp_mail support attachment in the mail!", 'micro-office') ),
					"std" => "wp_mail",
					"size" => "medium",
					"options" => array(
						'wp_mail' => esc_html__('WP mail', 'micro-office'),
						'mail' => esc_html__('PHP mail', 'micro-office')
					),
					"type" => "switch"),
		
		
		
		
		
		
		
		//###############################
		//#### Search parameters     #### 
		//###############################
		"partition_search" => array(
					"title" => esc_html__('Search', 'micro-office'),
					"icon" => "iconadmin-search",
					"type" => "partition"),
		
		"info_search_1" => array(
					"title" => esc_html__('Search parameters', 'micro-office'),
					"desc" => wp_kses_data( __('Enable/disable AJAX search and output settings for it', 'micro-office') ),
					"type" => "info"),
		
		"show_search" => array(
					"title" => esc_html__('Show search field', 'micro-office'),
					"desc" => wp_kses_data( __('Show search field in the top area and side menus', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"search_style" => array( 
					"title" => esc_html__('Select search style', 'micro-office'),
					"desc" => wp_kses_data( __('Select style for the search field', 'micro-office') ),
					"std" => "default",
					"type" => "select",
					"options" => micro_office_get_options_param('list_search_styles')),
		
		"use_ajax_search" => array(
					"title" => esc_html__('Enable AJAX search', 'micro-office'),
					"desc" => wp_kses_data( __('Use incremental AJAX search for the search field in top of page', 'micro-office') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand')
					),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_min_length" => array(
					"title" => esc_html__('Min search string length',  'micro-office'),
					"desc" => wp_kses_data( __('The minimum length of the search string',  'micro-office') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 4,
					"min" => 3,
					"type" => "spinner"),
		
		"ajax_search_delay" => array(
					"title" => esc_html__('Delay before search (in ms)',  'micro-office'),
					"desc" => wp_kses_data( __('How much time (in milliseconds, 1000 ms = 1 second) must pass after the last character before the start search',  'micro-office') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => 500,
					"min" => 300,
					"max" => 1000,
					"step" => 100,
					"type" => "spinner"),
		
		"ajax_search_types" => array(
					"title" => esc_html__('Search area', 'micro-office'),
					"desc" => wp_kses_data( __('Select post types, what will be include in search results. If not selected - use all types.', 'micro-office') ),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"std" => "",
					"options" => micro_office_get_options_param('list_posts_types'),
					"multiple" => true,
					"style" => "list",
					"type" => "select"),
		
		"ajax_search_posts_count" => array(
					"title" => esc_html__('Posts number in output',  'micro-office'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __('Number of the posts to show in search results',  'micro-office') ),
					"std" => 5,
					"min" => 1,
					"max" => 10,
					"type" => "spinner"),
		
		"ajax_search_posts_image" => array(
					"title" => esc_html__("Show post's image", 'micro-office'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's thumbnail in the search results", 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_date" => array(
					"title" => esc_html__("Show post's date", 'micro-office'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's publish date in the search results", 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_author" => array(
					"title" => esc_html__("Show post's author", 'micro-office'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's author in the search results", 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"ajax_search_posts_counters" => array(
					"title" => esc_html__("Show post's counters", 'micro-office'),
					"dependency" => array(
						'show_search' => array('yes'),
						'search_style' => array('default', 'slide', 'expand'),
						'use_ajax_search' => array('yes')
					),
					"desc" => wp_kses_data( __("Show post's counters (views, comments, likes) in the search results", 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		
		
		
		
		//###############################
		//#### Service               #### 
		//###############################
		
		"partition_service" => array(
					"title" => esc_html__('Service', 'micro-office'),
					"icon" => "iconadmin-wrench",
					"type" => "partition"),
		
		"info_service_1" => array(
					"title" => esc_html__('Theme functionality', 'micro-office'),
					"desc" => wp_kses_data( __('Basic theme functionality settings', 'micro-office') ),
					"type" => "info"),
	
		"use_ajax_views_counter" => array(
					"title" => esc_html__('Use AJAX post views counter', 'micro-office'),
					"desc" => wp_kses_data( __('Use javascript for post views count (if site work under the caching plugin) or increment views count in single page template', 'micro-office') ),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_add_filters" => array(
					"title" => esc_html__('Additional filters in the admin panel', 'micro-office'),
					"desc" => wp_kses_data( __('Show additional filters (on post formats, tags and categories) in admin panel page "Posts". <br>Attention! If you have more than 2.000-3.000 posts, enabling this option may cause slow load of the "Posts" page! If you encounter such slow down, simply open Appearance - Theme Options - Service and set "No" for this option.', 'micro-office') ),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_taxonomies" => array(
					"title" => esc_html__('Show overriden options for taxonomies', 'micro-office'),
					"desc" => wp_kses_data( __('Show extra column in categories list, where changed (overriden) theme options are displayed.', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"show_overriden_posts" => array(
					"title" => esc_html__('Show overriden options for posts and pages', 'micro-office'),
					"desc" => wp_kses_data( __('Show extra column in posts and pages list, where changed (overriden) theme options are displayed.', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
		"admin_dummy_data" => array(
					"title" => esc_html__('Enable Dummy Data Installer', 'micro-office'),
					"desc" => wp_kses_data( __('Show "Install Dummy Data" in the menu "Appearance". <b>Attention!</b> When you install dummy data all content of your site will be replaced!', 'micro-office') ),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

		"admin_dummy_timeout" => array(
					"title" => esc_html__('Dummy Data Installer Timeout',  'micro-office'),
					"desc" => wp_kses_data( __('Web-servers set the time limit for the execution of php-scripts. By default, this is 30 sec. Therefore, the import process will be split into parts. Upon completion of each part - the import will resume automatically! The import process will try to increase this limit to the time, specified in this field.',  'micro-office') ),
					"std" => 120,
					"min" => 30,
					"max" => 1800,
					"type" => "spinner"),
		
		"debug_mode" => array(
					"title" => esc_html__('Debug mode', 'micro-office'),
					"desc" => wp_kses_data( __('In debug mode we are using unpacked scripts and styles, else - using minified scripts and styles (if present). <b>Attention!</b> If you have modified the source code in the js or css files, regardless of this option will be used latest (modified) version stylesheets and scripts. You can re-create minified versions of files using on-line services or utilities', 'micro-office') ),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch")
		));

	}
}


// Update all temporary vars (start with $micro_office_) in the Theme Options with actual lists
if ( !function_exists( 'micro_office_options_settings_theme_setup2' ) ) {
	add_action( 'micro_office_action_after_init_theme', 'micro_office_options_settings_theme_setup2', 1 );
	function micro_office_options_settings_theme_setup2() {
		if (micro_office_options_is_used()) {
			// Replace arrays with actual parameters
			$lists = array();
			$tmp = micro_office_storage_get('options');
			if (is_array($tmp) && count($tmp) > 0) {
				$prefix = '$micro_office_';
				$prefix_len = micro_office_strlen($prefix);
				foreach ($tmp as $k=>$v) {
					if (isset($v['options']) && is_array($v['options']) && count($v['options']) > 0) {
						foreach ($v['options'] as $k1=>$v1) {
							if (micro_office_substr($k1, 0, $prefix_len) == $prefix || micro_office_substr($v1, 0, $prefix_len) == $prefix) {
								$list_func = micro_office_substr(micro_office_substr($k1, 0, $prefix_len) == $prefix ? $k1 : $v1, 1);
								$inherit = strpos($list_func, '(true)')!==false;
								$list_func = str_replace('(true)', '', $list_func);
								unset($tmp[$k]['options'][$k1]);
								if (isset($lists[$list_func]))
									$tmp[$k]['options'] = micro_office_array_merge($tmp[$k]['options'], $lists[$list_func]);
								else {
									if (function_exists($list_func)) {
										$tmp[$k]['options'] = $lists[$list_func] = micro_office_array_merge($tmp[$k]['options'], $list_func($inherit));
								   	} else
								   		dfl(sprintf(esc_html__('Wrong function name %s in the theme options array', 'micro-office'), $list_func));
								}
							}
						}
					}
				}
				micro_office_storage_set('options', $tmp);
			}
		}
	}
}



// Reset old Theme Options while theme first run
if ( !function_exists( 'micro_office_options_reset' ) ) {
	
	function micro_office_options_reset($clear=true) {
		$theme_slug = str_replace(' ', '_', trim(micro_office_strtolower(get_stylesheet())));
		$option_name = micro_office_storage_get('options_prefix') . '_' . trim($theme_slug) . '_options_reset';
		if ( get_option($option_name, false) === false ) {	
			if ($clear) {
				// Remove Theme Options from WP Options
				global $wpdb;
				$wpdb->query( $wpdb->prepare(
										"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
										micro_office_storage_get('options_prefix').'_%'
										)
							);
				// Add Templates Options
				 $txt = micro_office_fgc(micro_office_storage_get('demo_data_url') . 'default/templates_options.txt');
				if (!empty($txt)) {
					$data = micro_office_unserialize($txt);
					// Replace upload url in options
					if (is_array($data) && count($data) > 0) {
						foreach ($data as $k=>$v) {
							if (is_array($v) && count($v) > 0) {
								foreach ($v as $k1=>$v1) {
									$v[$k1] = micro_office_replace_uploads_url(micro_office_replace_uploads_url($v1, 'uploads'), 'imports');
								}
							}
							add_option( $k, $v, '', 'yes' );
						}
					}
				}
			}
			add_option($option_name, 1, '', 'yes');
		}
	}
}
?>