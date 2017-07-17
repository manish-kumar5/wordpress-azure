<?php
if (is_admin() 
		|| (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true' )
		|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline')
	) {
	require_once MICRO_OFFICE_FW_PATH . 'core/core.shortcodes/shortcodes_vc_classes.php';
}

// Width and height params
if ( !function_exists( 'micro_office_vc_width' ) ) {
	function micro_office_vc_width($w='') {
		return array(
			"param_name" => "width",
			"heading" => esc_html__("Width", "micro-office"),
			"description" => wp_kses_data( __("Width of the element", "micro-office") ),
			"group" => esc_html__('Size &amp; Margins', 'micro-office'),
			"value" => $w,
			"type" => "textfield"
		);
	}
}
if ( !function_exists( 'micro_office_vc_height' ) ) {
	function micro_office_vc_height($h='') {
		return array(
			"param_name" => "height",
			"heading" => esc_html__("Height", "micro-office"),
			"description" => wp_kses_data( __("Height of the element", "micro-office") ),
			"group" => esc_html__('Size &amp; Margins', 'micro-office'),
			"value" => $h,
			"type" => "textfield"
		);
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'micro_office_shortcodes_vc_scripts_admin' ) ) {
	
	function micro_office_shortcodes_vc_scripts_admin() {
		// Include CSS 
		wp_enqueue_style ( 'shortcodes_vc_admin-style', micro_office_get_file_url('shortcodes/theme.shortcodes_vc_admin.css'), array(), null );
		// Include JS
		wp_enqueue_script( 'shortcodes_vc_admin-script', micro_office_get_file_url('core/core.shortcodes/shortcodes_vc_admin.js'), array('jquery'), null, true );
	}
}

// Load scripts and styles for VC support
if ( !function_exists( 'micro_office_shortcodes_vc_scripts_front' ) ) {
	
	function micro_office_shortcodes_vc_scripts_front() {
		if (micro_office_vc_is_frontend()) {
			// Include CSS 
			wp_enqueue_style ( 'shortcodes_vc_front-style', micro_office_get_file_url('shortcodes/theme.shortcodes_vc_front.css'), array(), null );
			// Include JS
			wp_enqueue_script( 'shortcodes_vc_front-script', micro_office_get_file_url('core/core.shortcodes/shortcodes_vc_front.js'), array('jquery'), null, true );
			wp_enqueue_script( 'shortcodes_vc_theme-script', micro_office_get_file_url('shortcodes/theme.shortcodes_vc_front.js'), array('jquery'), null, true );
		}
	}
}

// Add init script into shortcodes output in VC frontend editor
if ( !function_exists( 'micro_office_shortcodes_vc_add_init_script' ) ) {
	
	function micro_office_shortcodes_vc_add_init_script($output, $tag='', $atts=array(), $content='') {
		if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') && (isset($_POST['action']) && $_POST['action']=='vc_load_shortcode')
				&& ( isset($_POST['shortcodes'][0]['tag']) && $_POST['shortcodes'][0]['tag']==$tag )
		) {
			if (micro_office_strpos($output, 'micro_office_vc_init_shortcodes')===false) {
				$id = "micro_office_vc_init_shortcodes_".str_replace('.', '', mt_rand());
				// Attention! This code will be appended in the shortcode's output
				// to init shortcode after it inserted in the page in the VC Frontend editor
				$holder = 'script';
				$output .= '<'.trim($holder).' id="'.esc_attr($id).'">
						try {
							micro_office_init_post_formats();
							micro_office_init_shortcodes(jQuery("body").eq(0));
							micro_office_scroll_actions();
						} catch (e) { };
					</'.trim($holder).'>';
			}
		}
		return $output;
	}
}

// Return vc_param value
if ( !function_exists( 'micro_office_get_vc_param' ) ) {
	function micro_office_get_vc_param($prm) {
		return micro_office_storage_get_array('vc_params', $prm);
	}
}

// Set vc_param value
if ( !function_exists( 'micro_office_set_vc_param' ) ) {
	function micro_office_set_vc_param($prm, $val) {
		micro_office_storage_set_array('vc_params', $prm, $val);
	}
}


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_shortcodes_vc_theme_setup' ) ) {
	//if ( micro_office_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'micro_office_action_before_init_theme', 'micro_office_shortcodes_vc_theme_setup', 20 );
	else
		add_action( 'micro_office_action_after_init_theme', 'micro_office_shortcodes_vc_theme_setup' );
	function micro_office_shortcodes_vc_theme_setup() {


		// Set dir with theme specific VC shortcodes
		if ( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {
			vc_set_shortcodes_templates_dir( micro_office_get_folder_dir('shortcodes/vc' ) );
		}
		
		// Add/Remove params in the standard VC shortcodes
		vc_add_param("vc_row", array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "micro-office"),
					"description" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"group" => esc_html__('Color scheme', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_get_list_color_schemes(true)),
					"type" => "dropdown"
		));
		vc_add_param("vc_row", array(
					"param_name" => "inverse",
					"heading" => esc_html__("Inverse colors", "micro-office"),
					"description" => wp_kses_data( __("Inverse all colors of this block", "micro-office") ),
					"group" => esc_html__('Color scheme', 'micro-office'),
					"class" => "",
					"std" => "no",
					"value" => array(esc_html__('Inverse colors', 'micro-office') => 'yes'),
					"type" => "checkbox"
		));

		if (micro_office_shortcodes_is_used() && class_exists('MICRO_OFFICE_VC_ShortCodeSingle')) {

			// Set VC as main editor for the theme
			vc_set_as_theme( true );
			
			// Enable VC on follow post types
			vc_set_default_editor_post_types( array('page', 'team') );
			
			// Load scripts and styles for VC support
			add_action( 'wp_enqueue_scripts',		'micro_office_shortcodes_vc_scripts_front');
			add_action( 'admin_enqueue_scripts',	'micro_office_shortcodes_vc_scripts_admin' );

			// Add init script into shortcodes output in VC frontend editor
			add_filter('micro_office_shortcode_output', 'micro_office_shortcodes_vc_add_init_script', 10, 4);

			micro_office_storage_set('vc_params', array(
				
				// Common arrays and strings
				'category' => esc_html__("Micro Office shortcodes", "micro-office"),
			
				// Current element id
				'id' => array(
					"param_name" => "id",
					"heading" => esc_html__("Element ID", "micro-office"),
					"description" => wp_kses_data( __("ID for the element", "micro-office") ),
					"group" => esc_html__('ID &amp; Class', 'micro-office'),
					"value" => "",
					"type" => "textfield"
				),
			
				// Current element class
				'class' => array(
					"param_name" => "class",
					"heading" => esc_html__("Element CSS class", "micro-office"),
					"description" => wp_kses_data( __("CSS class for the element", "micro-office") ),
					"group" => esc_html__('ID &amp; Class', 'micro-office'),
					"value" => "",
					"type" => "textfield"
				),

				// Current element animation
				'animation' => array(
					"param_name" => "animation",
					"heading" => esc_html__("Animation", "micro-office"),
					"description" => wp_kses_data( __("Select animation while object enter in the visible area of page", "micro-office") ),
					"group" => esc_html__('ID &amp; Class', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('animations')),
					"type" => "dropdown"
				),
			
				// Current element style
				'css' => array(
					"param_name" => "css",
					"heading" => esc_html__("CSS styles", "micro-office"),
					"description" => wp_kses_data( __("Any additional CSS rules (if need)", "micro-office") ),
					"group" => esc_html__('ID &amp; Class', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				// Margins params
				'margin_top' => array(
					"param_name" => "top",
					"heading" => esc_html__("Top margin", "micro-office"),
					"description" => wp_kses_data( __("Margin above this shortcode", "micro-office") ),
					"group" => esc_html__('Size &amp; Margins', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_bottom' => array(
					"param_name" => "bottom",
					"heading" => esc_html__("Bottom margin", "micro-office"),
					"description" => wp_kses_data( __("Margin below this shortcode", "micro-office") ),
					"group" => esc_html__('Size &amp; Margins', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
			
				'margin_left' => array(
					"param_name" => "left",
					"heading" => esc_html__("Left margin", "micro-office"),
					"description" => wp_kses_data( __("Margin on the left side of this shortcode", "micro-office") ),
					"group" => esc_html__('Size &amp; Margins', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				
				'margin_right' => array(
					"param_name" => "right",
					"heading" => esc_html__("Right margin", "micro-office"),
					"description" => wp_kses_data( __("Margin on the right side of this shortcode", "micro-office") ),
					"group" => esc_html__('Size &amp; Margins', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				)
			) );
			
			// Add theme-specific shortcodes
			do_action('micro_office_action_shortcodes_list_vc');

		}
	}
}
?>