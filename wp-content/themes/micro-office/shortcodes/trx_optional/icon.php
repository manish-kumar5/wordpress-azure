<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_icon_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_icon_theme_setup' );
	function micro_office_sc_icon_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_icon_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_icon_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_icon')) {	
	function micro_office_sc_icon($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "",
			"color" => "",
			"bg_color" => "",
			"bg_shape" => "",
			"font_size" => "",
			"font_weight" => "",
			"align" => "",
			"link" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css2 = ($font_weight != '' && !micro_office_is_inherit_option($font_weight) ? 'font-weight:'. esc_attr($font_weight).';' : '')
			. ($font_size != '' ? 'font-size:' . esc_attr(micro_office_prepare_css_value($font_size)) . '; line-height: ' . (!$bg_shape || micro_office_param_is_inherit($bg_shape) ? '1' : '1.2') . 'em;' : '')
			. ($color != '' ? 'color:'.esc_attr($color).';' : '')
			. ($bg_color != '' ? 'background-color:'.esc_attr($bg_color).';border-color:'.esc_attr($bg_color).';' : '')
		;
		$output = $icon!='' 
			? ($link ? '<a href="'.esc_url($link).'"' : '<span') . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_icon '.esc_attr($icon)
					. ($bg_shape && !micro_office_param_is_inherit($bg_shape) ? ' sc_icon_shape_'.esc_attr($bg_shape) : '')
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ($bg_color != '' ? ' bg' : '')
				.'"'
				.($css || $css2 ? ' style="'.($class ? 'display:block;' : '') . ($css) . ($css2) . '"' : '')
				.'>'
				.($link ? '</a>' : '</span>')
			: '';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_icon', $atts, $content);
	}
	micro_office_require_shortcode('trx_icon', 'micro_office_sc_icon');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_icon_reg_shortcodes' ) ) {
	
	function micro_office_sc_icon_reg_shortcodes() {
	
		micro_office_sc_map("trx_icon", array(
			"title" => esc_html__("Icon", "micro-office"),
			"desc" => wp_kses_data( __("Insert icon", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"icon" => array(
					"title" => esc_html__('Icon',  'micro-office'),
					"desc" => wp_kses_data( __('Select font icon from the Fontello icons set',  'micro-office') ),
					"value" => "",
					"type" => "icons",
					"options" => micro_office_get_sc_param('icons')
				),
				"color" => array(
					"title" => esc_html__("Icon's color", "micro-office"),
					"desc" => wp_kses_data( __("Icon's color", "micro-office") ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Icon's background color", "micro-office"),
					"desc" => wp_kses_data( __("Icon's background color", "micro-office") ),
					"dependency" => array(
						'icon' => array('not_empty'),
						'background' => array('round','square')
					),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", "micro-office"),
					"desc" => wp_kses_data( __("Icon's font size", "micro-office") ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "spinner",
					"min" => 8,
					"max" => 240
				),
				"align" => array(
					"title" => esc_html__("Alignment", "micro-office"),
					"desc" => wp_kses_data( __("Icon text alignment", "micro-office") ),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
				), 
				"link" => array(
					"title" => esc_html__("Link URL", "micro-office"),
					"desc" => wp_kses_data( __("Link URL from this icon (if not empty)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"top" => micro_office_get_sc_param('top'),
				"bottom" => micro_office_get_sc_param('bottom'),
				"left" => micro_office_get_sc_param('left'),
				"right" => micro_office_get_sc_param('right'),
				"id" => micro_office_get_sc_param('id'),
				"class" => micro_office_get_sc_param('class'),
				"css" => micro_office_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_icon_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_icon_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icon",
			"name" => esc_html__("Icon", "micro-office"),
			"description" => wp_kses_data( __("Insert the icon", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_icon',
			"class" => "trx_sc_single trx_sc_icon",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Icon", "micro-office"),
					"description" => wp_kses_data( __("Select icon class from Fontello icons set", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => micro_office_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "micro-office"),
					"description" => wp_kses_data( __("Icon's color", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Background color for the icon", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "micro-office"),
					"description" => wp_kses_data( __("Icon's font size", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Icon's alignment", "micro-office"),
					"description" => wp_kses_data( __("Align icon to left, center or right", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "micro-office"),
					"description" => wp_kses_data( __("Link URL from this icon (if not empty)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Icon extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>