<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_icons_skills_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_icons_skills_theme_setup' );
	function micro_office_sc_icons_skills_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_icons_skills_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_icons_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_icons_skills')) {	
	function micro_office_sc_icons_skills($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"icon" => "icon-heart",
			"color" => "",
			"size" => "2.5em",
			"count" => "10",
			"value" => "5",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width);
		if (empty($color)) $color = micro_office_get_scheme_color('text_hover', $color);
		
		$output = '<div' . ($id ? ' id="' . $id . '"' : '') 
					. ' class="sc_icons_skills'
					. (!empty($class) ? ' '.esc_attr($class) : '') .'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '') .'>'
						. '<div class="sc_icons_content" style="'.($size !== '' ? 'font-size:'.$size.';' : '').'">';
		for($i = 0; $i < $count; $i++)
		{
			if($i <= $value) 
				$output .= '<div class="sc_icons_item active '.esc_attr($icon).'" data-color="'.esc_attr($color).'"></div>';
			else   
				$output .= '<div class="sc_icons_item '.esc_attr($icon).'"></div>';
		}
		$output .= '</div></div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_icons_skills', $atts, $content);
	}
	micro_office_require_shortcode('trx_icons_skills', 'micro_office_sc_icons_skills');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_icons_skills_reg_shortcodes' ) ) {
	
	function micro_office_sc_icons_skills_reg_shortcodes() {
	
		micro_office_sc_map("trx_icons_skills", array(
			"title" => esc_html__("Icons Skills", "micro-office"),
			"desc" => wp_kses_data( __("Insert icons_skills diagramm in your page (post)", "micro-office") ),
			"decorate" => true,
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
					"title" => __("Color", "micro-office"),
					"desc" => __("Icon's color", "micro-office"),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "",
					"type" => "color"
				),
				"size" => array(
					"title" => __("Font size", "micro-office"),
					"desc" => __("Icon's font size", "micro-office"),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "2.5em",
					"type" => "text"
				),
				"count" => array(
					"title" => __("Count", "micro-office"),
					"desc" => __("Number of icons", "micro-office"),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "10",
					"type" => "spinner",
					"min" => 0,
					"max" => 10000
				),
				"value" => array(
					"title" => __("Value", "micro-office"),
					"desc" => __("Number of colored icons", "micro-office"),
					"dependency" => array(
						'icon' => array('not_empty')
					),
					"value" => "5",
					"type" => "spinner",
					"min" => 0,
					"max" => 10000
				),
				"width" => micro_office_shortcodes_width(),
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
if ( !function_exists( 'micro_office_sc_icons_skills_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_icons_skills_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_icons_skills",
			"name" => esc_html__("Icons Skills", "micro-office"),
			"description" => wp_kses_data( __("Insert skills diagramm", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_icons_skills',
			"class" => "trx_sc_collection trx_sc_icons_skills",
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
					"heading" => __("Color", "micro-office"),
					"description" => __("Icon's color", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "size",
					"heading" => __("Font size", "micro-office"),
					"description" => __("Icon's font size", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "2.5em",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => __("Count", "micro-office"),
					"description" => __("Number of icons", "micro-office"),
					"class" => "",
					"value" => "10",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => __("Value", "micro-office"),
					"description" => __("Number of colored icons", "micro-office"),
					"class" => "",
					"value" => "5",
					"type" => "textfield"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_vc_width(),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Icons_Skills extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>