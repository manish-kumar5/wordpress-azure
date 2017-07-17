<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_islands_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_islands_theme_setup' );
	function micro_office_sc_islands_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_islands_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_islands_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_islands')) {	
	function micro_office_sc_islands($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		
		$css .= micro_office_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);

		$output = '<div class="sc_islands '.($class != '' ? $class : '').'"'
					. ($id ? ' id="'.esc_attr($id).'"' : '')
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '>'
						. '<ul>'
							. do_shortcode($content)
						. '</ul>'
					. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_islands', $atts, $content);
	}
	micro_office_require_shortcode('trx_islands', 'micro_office_sc_islands');
}


if (!function_exists('micro_office_sc_islands_item')) {	
	function micro_office_sc_islands_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"type" => "1",
			"color" => micro_office_get_scheme_color('text_hover'),
			"value" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		
		$css = ($color !== '' ? 'color:'.$color.';' : '')
		  .($left !== '' ? 'left:'.$left.';' : '')
		  .($right !== '' ? 'right:'.$right.';' : '');
		  
		$output = '<li class="sc_islands_item sc_item_type_'.$type.'" style="'.$css.'">'
				  .($value !== '' ? '<div class="sc_item_value"><div class="value">'.$value.'</div><div class="circle"></div></div>' : '')
				  .'</li>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_islands_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_islands_item', 'micro_office_sc_islands_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_islands_reg_shortcodes' ) ) {
	
	function micro_office_sc_islands_reg_shortcodes() {
	
		micro_office_sc_map("trx_islands", array(
			"title" => esc_html__("Islands", "micro-office"),
			"desc" => wp_kses_data( __("Insert islands diagramm in your page (post)", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
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
			),
			"children" => array(
				"name" => "trx_islands_item",
				"title" => esc_html__("Island", "micro-office"),
				"desc" => wp_kses_data( __("Islands item", "micro-office") ),
				"container" => false,
				"params" => array(
					"type" => array(
						"title" => __("Type", "micro-office"),
						"desc" => __("Choose type of island (min 1 max 4)", "micro-office"),
						"value" => "1",
						"type" => "spinner",
						"min" => 1,
						"max" => 4
					),
					"value" => array(
						"title" => __("Value", "micro-office"),
						"desc" => __("Item value", "micro-office"),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => __("Color", "micro-office"),
						"desc" => __("Item color", "micro-office"),
						"value" => micro_office_get_scheme_color('text_hover'),
						"type" => "color"
					),
					"left" => array(
						"title" => __("Left", "micro-office"),
						"desc" => __("Position from left in px or percent", "micro-office"),
						"value" => "20%",
						"type" => "text"
					),
					"right" => array(
						"title" => __("Right", "micro-office"),
						"desc" => __("Position from right in px or percent", "micro-office"),
						"value" => "",
						"type" => "text"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_islands_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_islands_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_islands",
			"name" => esc_html__("Islands", "micro-office"),
			"description" => wp_kses_data( __("Insert islands diagramm", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_islands',
			"class" => "trx_sc_collection trx_sc_islands",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_islands_item'),
			"params" => array(
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
			)
		) );
		
		
		vc_map( array(
			"base" => "trx_islands_item",
			"name" => esc_html__("Island", "micro-office"),
			"description" => wp_kses_data( __("Islands item", "micro-office") ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_islands_item',
			"class" => "trx_sc_single trx_sc_islands_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_islands'),
			"as_parent" => array('except' => 'trx_islands'),
			"params" => array(
				array(
					"param_name" => "type",
					"heading" => __("Type", "micro-office"),
					"description" => __("Choose type of island (min 1 max 4)", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "1",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => __("Value", "micro-office"),
					"description" => __("Item value", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => __("Color", "micro-office"),
					"description" => __("Item color", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => micro_office_get_scheme_color('text_hover'),
					"type" => "colorpicker"
				),
				array(
					"param_name" => "left",
					"heading" => __("Left", "micro-office"),
					"description" => __("Position from left in px or percent", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "20%",
					"type" => "textfield"
				),
				array(
					"param_name" => "right",
					"heading" => __("Right", "micro-office"),
					"description" => __("Position from right in px or percent", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Islands extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Islands_Item extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>