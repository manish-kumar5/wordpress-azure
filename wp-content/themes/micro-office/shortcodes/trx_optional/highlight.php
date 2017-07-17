<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_highlight_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_highlight_theme_setup' );
	function micro_office_sc_highlight_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_highlight_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_highlight_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_highlight')) {	
	function micro_office_sc_highlight($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"color" => "",
			"bg_color" => "",
			"font_size" => "",
			"type" => "1",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$css .= ($color != '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color != '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(micro_office_prepare_css_value($font_size)) . '; line-height: 1em;' : '');
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_highlight'.($type>0 ? ' sc_highlight_style_'.esc_attr($type) : ''). (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. '>' 
				. do_shortcode($content) 
				. '</span>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_highlight', $atts, $content);
	}
	micro_office_require_shortcode('trx_highlight', 'micro_office_sc_highlight');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_highlight_reg_shortcodes' ) ) {
	
	function micro_office_sc_highlight_reg_shortcodes() {
	
		micro_office_sc_map("trx_highlight", array(
			"title" => esc_html__("Highlight text", "micro-office"),
			"desc" => wp_kses_data( __("Highlight text with selected color, background color and other styles", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"type" => array(
					"title" => esc_html__("Type", "micro-office"),
					"desc" => wp_kses_data( __("Highlight type", "micro-office") ),
					"value" => "1",
					"type" => "checklist",
					"options" => array(
						0 => esc_html__('Custom', 'micro-office'),
						1 => esc_html__('Type 1', 'micro-office'),
						2 => esc_html__('Type 2', 'micro-office'),
						3 => esc_html__('Type 3', 'micro-office')
					)
				),
				"color" => array(
					"title" => esc_html__("Color", "micro-office"),
					"desc" => wp_kses_data( __("Color for the highlighted text", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "micro-office"),
					"desc" => wp_kses_data( __("Background color for the highlighted text", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"font_size" => array(
					"title" => esc_html__("Font size", "micro-office"),
					"desc" => wp_kses_data( __("Font size of the highlighted text (default - in pixels, allows any CSS units of measure)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Highlighting content", "micro-office"),
					"desc" => wp_kses_data( __("Content for highlight", "micro-office") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => micro_office_get_sc_param('id'),
				"class" => micro_office_get_sc_param('class'),
				"css" => micro_office_get_sc_param('css')
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_highlight_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_highlight_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_highlight",
			"name" => esc_html__("Highlight text", "micro-office"),
			"description" => wp_kses_data( __("Highlight text with selected color, background color and other styles", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_highlight',
			"class" => "trx_sc_single trx_sc_highlight",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => esc_html__("Type", "micro-office"),
					"description" => wp_kses_data( __("Highlight type", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
							esc_html__('Custom', 'micro-office') => 0,
							esc_html__('Type 1', 'micro-office') => 1,
							esc_html__('Type 2', 'micro-office') => 2,
							esc_html__('Type 3', 'micro-office') => 3
						),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Text color", "micro-office"),
					"description" => wp_kses_data( __("Color for the highlighted text", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Background color for the highlighted text", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "micro-office"),
					"description" => wp_kses_data( __("Font size for the highlighted text (default - in pixels, allows any CSS units of measure)", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "content",
					"heading" => esc_html__("Highlight text", "micro-office"),
					"description" => wp_kses_data( __("Content for highlight", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css')
			),
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Highlight extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>