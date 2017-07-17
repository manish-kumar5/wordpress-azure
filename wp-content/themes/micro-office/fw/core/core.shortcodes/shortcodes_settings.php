<?php

// Check if shortcodes settings are now used
if ( !function_exists( 'micro_office_shortcodes_is_used' ) ) {
	function micro_office_shortcodes_is_used() {
		return micro_office_options_is_used() 															// All modes when Theme Options are used
			|| (is_admin() && isset($_POST['action']) 
					&& in_array($_POST['action'], array('vc_edit_form', 'wpb_show_edit_form')))		// AJAX query when save post/page
			|| (is_admin() && !empty($_REQUEST['page']) && $_REQUEST['page']=='vc-roles')			// VC Role Manager
			|| (function_exists('micro_office_vc_is_frontend') && micro_office_vc_is_frontend());			// VC Frontend editor mode
	}
}

// Width and height params
if ( !function_exists( 'micro_office_shortcodes_width' ) ) {
	function micro_office_shortcodes_width($w="") {
		return array(
			"title" => esc_html__("Width", "micro-office"),
			"divider" => true,
			"value" => $w,
			"type" => "text"
		);
	}
}
if ( !function_exists( 'micro_office_shortcodes_height' ) ) {
	function micro_office_shortcodes_height($h='') {
		return array(
			"title" => esc_html__("Height", "micro-office"),
			"desc" => wp_kses_data( __("Width and height of the element", "micro-office") ),
			"value" => $h,
			"type" => "text"
		);
	}
}

// Return sc_param value
if ( !function_exists( 'micro_office_get_sc_param' ) ) {
	function micro_office_get_sc_param($prm) {
		return micro_office_storage_get_array('sc_params', $prm);
	}
}

// Set sc_param value
if ( !function_exists( 'micro_office_set_sc_param' ) ) {
	function micro_office_set_sc_param($prm, $val) {
		micro_office_storage_set_array('sc_params', $prm, $val);
	}
}

// Add sc settings in the sc list
if ( !function_exists( 'micro_office_sc_map' ) ) {
	function micro_office_sc_map($sc_name, $sc_settings) {
		micro_office_storage_set_array('shortcodes', $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list after the key
if ( !function_exists( 'micro_office_sc_map_after' ) ) {
	function micro_office_sc_map_after($after, $sc_name, $sc_settings='') {
		micro_office_storage_set_array_after('shortcodes', $after, $sc_name, $sc_settings);
	}
}

// Add sc settings in the sc list before the key
if ( !function_exists( 'micro_office_sc_map_before' ) ) {
	function micro_office_sc_map_before($before, $sc_name, $sc_settings='') {
		micro_office_storage_set_array_before('shortcodes', $before, $sc_name, $sc_settings);
	}
}

// Compare two shortcodes by title
if ( !function_exists( 'micro_office_compare_sc_title' ) ) {
	function micro_office_compare_sc_title($a, $b) {
		return strcmp($a['title'], $b['title']);
	}
}



/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_shortcodes_settings_theme_setup' ) ) {
//	if ( micro_office_vc_is_frontend() )
	if ( (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true') || (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline') )
		add_action( 'micro_office_action_before_init_theme', 'micro_office_shortcodes_settings_theme_setup', 20 );
	else
		add_action( 'micro_office_action_after_init_theme', 'micro_office_shortcodes_settings_theme_setup' );
	function micro_office_shortcodes_settings_theme_setup() {
		if (micro_office_shortcodes_is_used()) {

			// Sort templates alphabetically
			$tmp = micro_office_storage_get('registered_templates');
			ksort($tmp);
			micro_office_storage_set('registered_templates', $tmp);

			// Prepare arrays 
			micro_office_storage_set('sc_params', array(
			
				// Current element id
				'id' => array(
					"title" => esc_html__("Element ID", "micro-office"),
					"desc" => wp_kses_data( __("ID for current element", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
			
				// Current element class
				'class' => array(
					"title" => esc_html__("Element CSS class", "micro-office"),
					"desc" => wp_kses_data( __("CSS class for current element (optional)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
			
				// Current element style
				'css' => array(
					"title" => esc_html__("CSS styles", "micro-office"),
					"desc" => wp_kses_data( __("Any additional CSS rules (if need)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
			
			
				// Switcher choises
				'list_styles' => array(
					'ul'	=> esc_html__('Unordered', 'micro-office'),
					'ol'	=> esc_html__('Ordered', 'micro-office'),
					'iconed'=> esc_html__('Iconed', 'micro-office')
				),

				'yes_no'	=> micro_office_get_list_yesno(),
				'on_off'	=> micro_office_get_list_onoff(),
				'dir' 		=> micro_office_get_list_directions(),
				'align'		=> micro_office_get_list_alignments(),
				'float'		=> micro_office_get_list_floats(),
				'hpos'		=> micro_office_get_list_hpos(),
				'show_hide'	=> micro_office_get_list_showhide(),
				'sorting' 	=> micro_office_get_list_sortings(),
				'ordering' 	=> micro_office_get_list_orderings(),
				'shapes'	=> micro_office_get_list_shapes(),
				'sizes'		=> micro_office_get_list_sizes(),
				'sliders'	=> micro_office_get_list_sliders(),
				'controls'	=> micro_office_get_list_controls(),
				'categories'=> micro_office_get_list_categories(),
				'columns'	=> micro_office_get_list_columns(),
				'images'	=> array_merge(array('none'=>"none"), micro_office_get_list_images("images/icons", "png")),
				'icons'		=> array_merge(array("inherit", "none"), micro_office_get_list_icons()),
				'locations'	=> micro_office_get_list_dedicated_locations(),
				'filters'	=> micro_office_get_list_portfolio_filters(),
				'formats'	=> micro_office_get_list_post_formats_filters(),
				'hovers'	=> micro_office_get_list_hovers(true),
				'hovers_dir'=> micro_office_get_list_hovers_directions(true),
				'schemes'	=> micro_office_get_list_color_schemes(true),
				'animations'		=> micro_office_get_list_animations_in(),
				'margins' 			=> micro_office_get_list_margins(true),
				'blogger_styles'	=> micro_office_get_list_templates_blogger(),
				'forms'				=> micro_office_get_list_templates_forms(),
				'posts_types'		=> micro_office_get_list_posts_types(),
				'googlemap_styles'	=> micro_office_get_list_googlemap_styles(),
				'field_types'		=> micro_office_get_list_field_types(),
				'label_positions'	=> micro_office_get_list_label_positions()
				)
			);

			// Common params
			micro_office_set_sc_param('animation', array(
				"title" => esc_html__("Animation",  'micro-office'),
				"desc" => wp_kses_data( __('Select animation while object enter in the visible area of page',  'micro-office') ),
				"value" => "none",
				"type" => "select",
				"options" => micro_office_get_sc_param('animations')
				)
			);
			micro_office_set_sc_param('top', array(
				"title" => esc_html__("Top margin",  'micro-office'),
				"divider" => true,
				"value" => "",
				"type" => "text"
				)
			);
			micro_office_set_sc_param('bottom', array(
				"title" => esc_html__("Bottom margin",  'micro-office'),
				"value" => "",
				"type" => "text"
				)
			);
			micro_office_set_sc_param('left', array(
				"title" => esc_html__("Left margin",  'micro-office'),
				"value" => "",
				"type" => "text"
				)
			);
			micro_office_set_sc_param('right', array(
				"title" => esc_html__("Right margin",  'micro-office'),
				"desc" => wp_kses_data( __("Margins around this shortcode", "micro-office") ),
				"value" => "",
				"type" => "text"
				)
			);

			micro_office_storage_set('sc_params', apply_filters('micro_office_filter_shortcodes_params', micro_office_storage_get('sc_params')));

			// Shortcodes list
			//------------------------------------------------------------------
			micro_office_storage_set('shortcodes', array());
			
			// Register shortcodes
			do_action('micro_office_action_shortcodes_list');

			// Sort shortcodes list
			$tmp = micro_office_storage_get('shortcodes');
			uasort($tmp, 'micro_office_compare_sc_title');
			micro_office_storage_set('shortcodes', $tmp);
		}
	}
}
?>