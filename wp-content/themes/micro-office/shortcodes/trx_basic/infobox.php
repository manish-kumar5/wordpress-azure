<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_infobox_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_infobox_theme_setup' );
	function micro_office_sc_infobox_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_infobox_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_infobox_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_infobox')) {	
	function micro_office_sc_infobox($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "regular",
			"closeable" => "no",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) .';' : '');

		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_infobox sc_infobox_style_' . esc_attr($style) 
					. (micro_office_param_is_on($closeable) ? ' sc_infobox_closeable' : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '') 
					. ($icon!='' && !micro_office_param_is_inherit($icon) ? ' sc_infobox_iconed '. esc_attr($icon) : '') 
					. '"'
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>'
				. trim($content)
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_infobox', $atts, $content);
	}
	micro_office_require_shortcode('trx_infobox', 'micro_office_sc_infobox');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_infobox_reg_shortcodes' ) ) {
	
	function micro_office_sc_infobox_reg_shortcodes() {
	
		micro_office_sc_map("trx_infobox", array(
			"title" => esc_html__("Infobox", "micro-office"),
			"desc" => wp_kses_data( __("Insert infobox into your post (page)", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "micro-office"),
					"desc" => wp_kses_data( __("Infobox style", "micro-office") ),
					"value" => "regular",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'regular' => esc_html__('Regular', 'micro-office'),
						'info' => esc_html__('Info', 'micro-office'),
						'success' => esc_html__('Success', 'micro-office'),
						'error' => esc_html__('Error', 'micro-office')
					)
				),
				"closeable" => array(
					"title" => esc_html__("Closeable box", "micro-office"),
					"desc" => wp_kses_data( __("Create closeable box (with close button)", "micro-office") ),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"color" => array(
					"title" => esc_html__("Text color", "micro-office"),
					"desc" => wp_kses_data( __("Any color for text and headers", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "micro-office"),
					"desc" => wp_kses_data( __("Any background color for this infobox", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"_content_" => array(
					"title" => esc_html__("Infobox content", "micro-office"),
					"desc" => wp_kses_data( __("Content for infobox", "micro-office") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"top" => micro_office_get_sc_param('top'),
				"bottom" => micro_office_get_sc_param('bottom'),
				"left" => micro_office_get_sc_param('left'),
				"right" => micro_office_get_sc_param('right'),
				"id" => micro_office_get_sc_param('id'),
				"class" => micro_office_get_sc_param('class'),
				"animation" => micro_office_get_sc_param('animation'),
				"css" => micro_office_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_infobox_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_infobox_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_infobox",
			"name" => esc_html__("Infobox", "micro-office"),
			"description" => wp_kses_data( __("Box with info or error message", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_infobox',
			"class" => "trx_sc_container trx_sc_infobox",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "micro-office"),
					"description" => wp_kses_data( __("Infobox style", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Regular', 'micro-office') => 'regular',
							esc_html__('Info', 'micro-office') => 'info',
							esc_html__('Success', 'micro-office') => 'success',
							esc_html__('Error', 'micro-office') => 'error',
							esc_html__('Result', 'micro-office') => 'result'
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "closeable",
					"heading" => esc_html__("Closeable", "micro-office"),
					"description" => wp_kses_data( __("Create closeable box (with close button)", "micro-office") ),
					"class" => "",
					"value" => array(esc_html__('Close button', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "micro-office"),
					"description" => wp_kses_data( __("Any color for the text and headers", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Any background color for this infobox", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextContainerView'
		) );
		
		class WPBakeryShortCode_Trx_Infobox extends MICRO_OFFICE_VC_ShortCodeContainer {}
	}
}
?>