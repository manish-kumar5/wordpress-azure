<?php
/**
 * Theme custom styles
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if (!function_exists('micro_office_action_theme_styles_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_action_theme_styles_theme_setup', 1 );
	function micro_office_action_theme_styles_theme_setup() {
	
		// Add theme fonts in the used fonts list
		add_filter('micro_office_filter_used_fonts',			'micro_office_filter_theme_styles_used_fonts');
		// Add theme fonts (from Google fonts) in the main fonts list (if not present).
		add_filter('micro_office_filter_list_fonts',			'micro_office_filter_theme_styles_list_fonts');

		// Add theme stylesheets
		add_action('micro_office_action_add_styles',			'micro_office_action_theme_styles_add_styles');
		add_action('micro_office_action_add_styles',			'micro_office_eventon_frontend_scripts');
		add_action('micro_office_action_add_styles',			'micro_office_wisechat_frontend_scripts');
		add_action('micro_office_action_add_styles',			'micro_office_timeline_frontend_scripts');
		// Add theme inline styles
		add_filter('micro_office_filter_add_styles_inline',		'micro_office_filter_theme_styles_add_styles_inline');

		// Add theme scripts
		add_action('micro_office_action_add_scripts',			'micro_office_action_theme_styles_add_scripts');
		// Add theme scripts inline
		add_filter('micro_office_filter_localize_script',		'micro_office_filter_theme_styles_localize_script');

		// Add theme less files into list for compilation
		add_filter('micro_office_filter_compile_less',			'micro_office_filter_theme_styles_compile_less');


		/* Color schemes
		
		// Block's border and background
		bd_color		- border for the entire block
		bg_color		- background color for the entire block
		// Next settings are deprecated
		//bg_image, bg_image_position, bg_image_repeat, bg_image_attachment  - first background image for the entire block
		//bg_image2,bg_image2_position,bg_image2_repeat,bg_image2_attachment - second background image for the entire block
		
		// Additional accented colors (if need)
		accent2			- theme accented color 2
		accent2_hover	- theme accented color 2 (hover state)		
		accent3			- theme accented color 3
		accent3_hover	- theme accented color 3 (hover state)		
		
		// Headers, text and links
		text			- main content
		text_light		- post info
		text_dark		- headers
		text_link		- links
		text_hover		- hover links
		
		// Inverse blocks
		inverse_text	- text on accented background
		inverse_light	- post info on accented background
		inverse_dark	- headers on accented background
		inverse_link	- links on accented background
		inverse_hover	- hovered links on accented background
		
		// Input colors - form fields
		input_text		- inactive text
		input_light		- placeholder text
		input_dark		- focused text
		input_bd_color	- inactive border
		input_bd_hover	- focused borde
		input_bg_color	- inactive background
		input_bg_hover	- focused background
		
		// Alternative colors - highlight blocks, form fields, etc.
		alter_text		- text on alternative background
		alter_light		- post info on alternative background
		alter_dark		- headers on alternative background
		alter_link		- links on alternative background
		alter_hover		- hovered links on alternative background
		alter_bd_color	- alternative border
		alter_bd_hover	- alternative border for hovered state or active field
		alter_bg_color	- alternative background
		alter_bg_hover	- alternative background for hovered state or active field 
		// Next settings are deprecated
		//alter_bg_image, alter_bg_image_position, alter_bg_image_repeat, alter_bg_image_attachment - background image for the alternative block
		
		*/

		// Add color schemes
		micro_office_add_color_scheme('original', array(

			'title'					=> esc_html__('Original', 'micro-office'),
			
			// Whole block border and background
			'bd_color'				=> '#F1F1F1',
			'bg_color'				=> '#E9E9E9',
			
			// Headers, text and links colors
			'text'					=> '#8a8d90',
			'text_light'			=> '#a9aaad',
			'text_dark'				=> '#2A3342',
			'text_link'				=> '#2A3342',
			'text_hover'			=> '#1EBEB4',

			// Inverse colors
			'inverse_text'			=> '#ffffff',
			'inverse_light'			=> '#ffffff',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#ffffff',
		
			// Input fields
			'input_text'			=> '#8a8d90',
			'input_light'			=> '#8a8d90',
			'input_dark'			=> '#8a8d90',
			'input_bd_color'		=> '#DFDFDF',
			'input_bd_hover'		=> '#1EBEB4',
			'input_bg_color'		=> '#F8F8F8',
			'input_bg_hover'		=> '#F8F8F8',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#8A8A8A',
			'alter_light'			=> '#ACB4B6',
			'alter_dark'			=> '#2A3342',
			'alter_link'			=> '#383A3F',
			'alter_hover'			=> '#EE5744',
			'alter_bd_color'		=> '#E9E9E9',
			'alter_bd_hover'		=> '#E3E3E3',
			'alter_bg_color'		=> '#F7F7F7',
			'alter_bg_hover'		=> '#F0F0F0',
			)
		);

		// Add color schemes
		micro_office_add_color_scheme('dark', array(

			'title'					=> esc_html__('Dark', 'micro-office'),
			
			// Whole block border and background
			'bd_color'				=> '#7D7D7D',
			'bg_color'				=> '#333333',

			// Headers, text and links colors
			'text'					=> '#909090',
			'text_light'			=> '#a0a0a0',
			'text_dark'				=> '#e0e0e0',
			'text_link'				=> '#20c7ca',
			'text_hover'			=> '#189799',

			// Inverse colors
			'inverse_text'			=> '#f0f0f0',
			'inverse_light'			=> '#e0e0e0',
			'inverse_dark'			=> '#ffffff',
			'inverse_link'			=> '#ffffff',
			'inverse_hover'			=> '#e5e5e5',
		
			// Input fields
			'input_text'			=> '#999999',
			'input_light'			=> '#aaaaaa',
			'input_dark'			=> '#d0d0d0',
			'input_bd_color'		=> '#909090',
			'input_bd_hover'		=> '#888888',
			'input_bg_color'		=> '#666666',
			'input_bg_hover'		=> '#505050',
		
			// Alternative blocks (submenu items, etc.)
			'alter_text'			=> '#999999',
			'alter_light'			=> '#aaaaaa',
			'alter_dark'			=> '#d0d0d0',
			'alter_link'			=> '#20c7ca',
			'alter_hover'			=> '#29fbff',
			'alter_bd_color'		=> '#909090',
			'alter_bd_hover'		=> '#888888',
			'alter_bg_color'		=> '#666666',
			'alter_bg_hover'		=> '#505050',
			)
		);


		/* Font slugs:
		h1 ... h6	- headers
		p			- plain text
		link		- links
		info		- info blocks (Posted 15 May, 2015 by John Doe)
		menu		- main menu
		submenu		- dropdown menus
		logo		- logo text
		button		- button's caption
		input		- input fields
		*/

		// Add Custom fonts
		micro_office_add_custom_font('h1', array(
			'title'			=> esc_html__('Heading 1', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.857em',
			'font-weight'	=> '100',
			'font-style'	=> '',
			'line-height'	=> '1.225em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.59em'
			)
		);
		micro_office_add_custom_font('h2', array(
			'title'			=> esc_html__('Heading 2', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.285em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.85em'
			)
		);
		micro_office_add_custom_font('h3', array(
			'title'			=> esc_html__('Heading 3', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.714em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.375em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.85em'
			)
		);
		micro_office_add_custom_font('h4', array(
			'title'			=> esc_html__('Heading 4', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.428em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.5em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.85em'
			)
		);
		micro_office_add_custom_font('h5', array(
			'title'			=> esc_html__('Heading 5', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1.2857em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '1.5em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.85em'
			)
		);
		micro_office_add_custom_font('h6', array(
			'title'			=> esc_html__('Heading 6', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '1em',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.5em',
			'margin-top'	=> '0em',
			'margin-bottom'	=> '0.85em'
			)
		);
		micro_office_add_custom_font('p', array(
			'title'			=> esc_html__('Text', 'micro-office'),
			'description'	=> '',
			'font-family'	=> 'Open Sans',
			'font-size' 	=> '14px',
			'font-weight'	=> '400',
			'font-style'	=> '',
			'line-height'	=> '1.715em',
			'margin-top'	=> '',
			'margin-bottom'	=> '1em'
			)
		);
		micro_office_add_custom_font('link', array(
			'title'			=> esc_html__('Links', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> ''
			)
		);
		micro_office_add_custom_font('info', array(
			'title'			=> esc_html__('Post info', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '12px',
			'font-weight'	=> '500',
			'font-style'	=> '',
			'line-height'	=> '1.2857em',
			'margin-top'	=> '',
			'margin-bottom'	=> '2.4em'
			)
		);
		micro_office_add_custom_font('logo', array(
			'title'			=> esc_html__('Logo', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '2.8571em',
			'font-weight'	=> '700',
			'font-style'	=> '',
			'line-height'	=> '0.75em',
			'margin-top'	=> '2.5em',
			'margin-bottom'	=> '2em'
			)
		);
		micro_office_add_custom_font('button', array(
			'title'			=> esc_html__('Buttons', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);
		micro_office_add_custom_font('input', array(
			'title'			=> esc_html__('Input fields', 'micro-office'),
			'description'	=> '',
			'font-family'	=> '',
			'font-size' 	=> '',
			'font-weight'	=> '',
			'font-style'	=> '',
			'line-height'	=> '1.2857em'
			)
		);

	}
}





//------------------------------------------------------------------------------
// Theme fonts
//------------------------------------------------------------------------------

// Add theme fonts in the used fonts list
if (!function_exists('micro_office_filter_theme_styles_used_fonts')) {
	
	function micro_office_filter_theme_styles_used_fonts($theme_fonts) {
		$theme_fonts['Lato'] = 1;
		return $theme_fonts;
	}
}

// Add theme fonts (from Google fonts) in the main fonts list (if not present).
// To use custom font-face you not need add it into list in this function
// How to install custom @font-face fonts into the theme?
// All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!
// Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.
// Create your @font-face kit by using Fontsquirrel @font-face Generator (http://www.fontsquirrel.com/fontface/generator)
// and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install
if (!function_exists('micro_office_filter_theme_styles_list_fonts')) {
	
	function micro_office_filter_theme_styles_list_fonts($list) {
		if (!isset($list['Lato']))	$list['Lato'] = array('family'=>'sans-serif');
		return $list;
	}
}



//------------------------------------------------------------------------------
// Theme stylesheets
//------------------------------------------------------------------------------

// Add theme.less into list files for compilation
if (!function_exists('micro_office_filter_theme_styles_compile_less')) {
	
	function micro_office_filter_theme_styles_compile_less($files) {
		if (file_exists(micro_office_get_file_dir('css/theme.less'))) {
		 	$files[] = micro_office_get_file_dir('css/theme.less');
		}
		return $files;	
	}
}

// Add theme stylesheets
if (!function_exists('micro_office_action_theme_styles_add_styles')) {
	
	function micro_office_action_theme_styles_add_styles() {
		// Add stylesheet files only if LESS supported
		if ( micro_office_get_theme_setting('less_compiler') != 'no' ) {
			wp_enqueue_style( 'micro_office-theme-style', micro_office_get_file_url('css/theme.css'), array(), null );
			wp_add_inline_style( 'micro_office-theme-style', micro_office_get_inline_css() );
		}
	}
}

// Enqueue EventOn custom styles
if ( !function_exists( 'micro_office_eventon_frontend_scripts' ) ) {
	
	function micro_office_eventon_frontend_scripts() {
		wp_enqueue_style( 'micro_office-plugin.eventon-style',  micro_office_get_file_url('css/plugin.eventon.css'), array(), null );
	}
}

// Enqueue WiseChat custom styles
if ( !function_exists( 'micro_office_wisechat_frontend_scripts' ) ) {
	
	function micro_office_wisechat_frontend_scripts() {
		wp_enqueue_style( 'micro_office-plugin.wisechat-style',  micro_office_get_file_url('css/plugin.wisechat.css'), array(), null );
	}
}

// Enqueue Timeline custom styles
if ( !function_exists( 'micro_office_timeline_frontend_scripts' ) ) {
	
	function micro_office_timeline_frontend_scripts() {
		wp_enqueue_style( 'micro_office-plugin.timeline-style',  micro_office_get_file_url('css/plugin.timeline.css'), array(), null );
	}
}

// Add theme inline styles
if (!function_exists('micro_office_filter_theme_styles_add_styles_inline')) {
	
	function micro_office_filter_theme_styles_add_styles_inline($custom_style) {
		// Submenu width
		$menu_width = micro_office_get_theme_option('menu_width');
		if (!empty($menu_width)) {
			$custom_style .= "
				/* Submenu width */
				.menu_side_nav > li ul,
				.menu_main_nav > li ul {
					width: ".intval($menu_width)."px;
				}
				.menu_side_nav > li > ul ul,
				.menu_main_nav > li > ul ul {
					left:".intval($menu_width+4)."px;
				}
				.menu_side_nav > li > ul ul.submenu_left,
				.menu_main_nav > li > ul ul.submenu_left {
					left:-".intval($menu_width+1)."px;
				}
			";
		}
	
		// Logo height
		$logo_height = micro_office_get_custom_option('logo_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo header height */
				.sidebar_outer_logo .logo_main,
				.top_panel_wrap .logo_main,
				.top_panel_wrap .logo_fixed {
					height:".intval($logo_height)."px;
				}
			";
		}
	
		// Logo top offset
		$logo_offset = micro_office_get_custom_option('logo_offset');
		if (!empty($logo_offset)) {
			$custom_style .= "
				/* Logo header top offset */
				.top_panel_wrap .logo {
					margin-top:".intval($logo_offset)."px;
				}
			";
		}

		// Logo footer height
		$logo_height = micro_office_get_theme_option('logo_footer_height');
		if (!empty($logo_height)) {
			$custom_style .= "
				/* Logo footer height */
				.contacts_wrap .logo img {
					height:".intval($logo_height)."px;
				}
			";
		}

		return $custom_style;	
	}
}


//------------------------------------------------------------------------------
// Theme scripts
//------------------------------------------------------------------------------

// Add theme scripts
if (!function_exists('micro_office_action_theme_styles_add_scripts')) {
	
	function micro_office_action_theme_styles_add_scripts() {
		if (micro_office_get_theme_option('show_theme_customizer') == 'yes' && file_exists(micro_office_get_file_dir('js/theme.customizer.js')))
			wp_enqueue_script( 'micro_office-theme_styles-customizer-script', micro_office_get_file_url('js/theme.customizer.js'), array(), null );
	}
}

// Add theme scripts inline
if (!function_exists('micro_office_filter_theme_styles_localize_script')) {
	
	function micro_office_filter_theme_styles_localize_script($vars) {
		if (empty($vars['theme_font']))
			$vars['theme_font'] = micro_office_get_custom_font_settings('p', 'font-family');
		$vars['theme_color'] = micro_office_get_scheme_color('text_dark');
		$vars['theme_bg_color'] = micro_office_get_scheme_color('bg_color');
		return $vars;
	}
}
?>