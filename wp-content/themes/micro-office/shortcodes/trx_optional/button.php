<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_button_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_button_theme_setup' );
	function micro_office_sc_button_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_button_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_button_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_button')) {	
	function micro_office_sc_button($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "square",
			"style" => "filled",
			"size" => "small",
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"link" => "",
			"target" => "",
			"align" => "",
			"rel" => "",
			"popup" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		
		$css .= micro_office_get_css_dimensions_from_values($width, $height)
			. ($color !== '' ? 'color:' . esc_attr($color) .';' : '')
			. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '');
		if (micro_office_param_is_on($popup)) micro_office_enqueue_popup('magnific');
		$output = '<a href="' . (empty($link) ? '#' : $link) . '"'
			. (!empty($target) ? ' target="'.esc_attr($target).'"' : '')
			. (!empty($rel) ? ' rel="'.esc_attr($rel).'"' : '')
			. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
			. ' class="sc_button sc_button_' . esc_attr($type) 
					. ' sc_button_style_' . esc_attr($style) 
					. ' sc_button_size_' . esc_attr($size)
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($icon!='' ? '  sc_button_iconed '. esc_attr($icon) : '') 
					. ($width!='' ? '  sc_button_boxed' : '') 
					. (micro_office_param_is_on($popup) ? ' sc_popup_link' : '') 
					. '"'
			. ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
			. '>'
			. do_shortcode($content)
			. '</a>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_button', $atts, $content);
	}
	micro_office_require_shortcode('trx_button', 'micro_office_sc_button');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_button_reg_shortcodes' ) ) {
	
	function micro_office_sc_button_reg_shortcodes() {
	
		micro_office_sc_map("trx_button", array(
			"title" => esc_html__("Button", "micro-office"),
			"desc" => wp_kses_data( __("Button with link", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Caption", "micro-office"),
					"desc" => wp_kses_data( __("Button caption", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"color" => array(
					"title" => esc_html__("Button's text color", "micro-office"),
					"desc" => wp_kses_data( __("Any color for button's caption", "micro-office") ),
					"std" => "",
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Button's backcolor", "micro-office"),
					"desc" => wp_kses_data( __("Any color for button's background", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Button's alignment", "micro-office"),
					"desc" => wp_kses_data( __("Align button to left, center or right", "micro-office") ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", "micro-office"),
					"desc" => wp_kses_data( __("URL for link on button click", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"target" => array(
					"title" => esc_html__("Link target", "micro-office"),
					"desc" => wp_kses_data( __("Target for link on button click", "micro-office") ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"popup" => array(
					"title" => esc_html__("Open link in popup", "micro-office"),
					"desc" => wp_kses_data( __("Open link target in popup window", "micro-office") ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				), 
				"rel" => array(
					"title" => esc_html__("Rel attribute", "micro-office"),
					"desc" => wp_kses_data( __("Rel attribute for button's link (if need)", "micro-office") ),
					"dependency" => array(
						'link' => array('not_empty')
					),
					"value" => "",
					"type" => "text"
				),
				"width" => micro_office_shortcodes_width(),
				"height" => micro_office_shortcodes_height(),
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
if ( !function_exists( 'micro_office_sc_button_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_button_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_button",
			"name" => esc_html__("Button", "micro-office"),
			"description" => wp_kses_data( __("Button with link", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_button',
			"class" => "trx_sc_single trx_sc_button",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Caption", "micro-office"),
					"description" => wp_kses_data( __("Button caption", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Button's text color", "micro-office"),
					"description" => wp_kses_data( __("Any color for button's caption", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Button's backcolor", "micro-office"),
					"description" => wp_kses_data( __("Any color for button's background", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Button's alignment", "micro-office"),
					"description" => wp_kses_data( __("Align button to left, center or right", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "micro-office"),
					"description" => wp_kses_data( __("URL for the link on button click", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Link', 'micro-office'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "micro-office"),
					"description" => wp_kses_data( __("Target for the link on button click", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Link', 'micro-office'),
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "popup",
					"heading" => esc_html__("Open link in popup", "micro-office"),
					"description" => wp_kses_data( __("Open link target in popup window", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Link', 'micro-office'),
					"value" => array(esc_html__('Open in popup', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "rel",
					"heading" => esc_html__("Rel attribute", "micro-office"),
					"description" => wp_kses_data( __("Rel attribute for the button's link (if need", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Link', 'micro-office'),
					"value" => "",
					"type" => "textfield"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_vc_width(),
				micro_office_vc_height(),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Button extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>