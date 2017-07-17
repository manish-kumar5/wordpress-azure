<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_chart_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_chart_theme_setup' );
	function micro_office_sc_chart_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_chart_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_chart_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_chart')) {	
	function micro_office_sc_chart($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "",
			"columns" => "",
			"color" => "",
			"dark_color" => "",
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
		micro_office_storage_set('sc_chart_data', array(
			'id' => $id,
			'columns' => $columns,
            'max_value' => $max_value,
            'color' => $color,
            'dark_color' => $dark_color
			)
		);
		wp_enqueue_script( 'diagram-chart', micro_office_get_file_url('fw/js/diagram/chart.min.js'), array('jquery'), null, true );
		wp_enqueue_script( 'diagram-raphael', micro_office_get_file_url('fw/js/diagram/diagram.raphael.js'), array('jquery'), null, true );
		
		$css = ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width);

		$content = do_shortcode($content);
		$output = '<div '.(!empty($id) ? 'id="'.esc_attr($id).'"' : '').' class="sc_chart_diagram'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '"'.(!empty($id) ? ' style="'.esc_attr($css).';"' : '').'>'
					. '<div class="columns_wrap">'
						.$content
					. '</div>'
				.'</div>';
				
		return apply_filters('micro_office_shortcode_output', $output, 'trx_chart', $atts, $content);
	}
	micro_office_require_shortcode('trx_chart', 'micro_office_sc_chart');
}


if (!function_exists('micro_office_sc_chart_item')) {	
	function micro_office_sc_chart_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"value" => "75",
			"title" => "",
			"color" => "",
			"dark_color" => ""
		), $atts)));
		
		if (empty($color)) $color = micro_office_storage_get_array('sc_chart_data', 'color');
		if (empty($color)) $color = micro_office_get_scheme_color('text_hover', $color);
		if (empty($dark_color)) $dark_color = micro_office_storage_get_array('sc_chart_data', 'dark_color');
		if (empty($dark_color)) $dark_color = micro_office_get_scheme_color('text_dark', $dark_color);
		
		$id = micro_office_storage_get_array('sc_chart_data', 'id').'_ch_'.mt_rand(0,1000);
		$max_value = micro_office_storage_get_array('sc_chart_data', 'max_value');
		if (empty($max_value)) $max_value = 100;
		
		$x = $value * 100 / $max_value;
		if((int) $x > 100) $x = '100';

		$ed = micro_office_substr($x, -1)=='%' ? '%' : '';
		$x = (int) str_replace('%', '', $x);
		$percent = round($x / 100 * 100);
		
		
		$content = do_shortcode($content);
		$output = 	'<div class="column-1_'. esc_attr(micro_office_storage_get_array('sc_chart_data', 'columns')) .'">'
						.'<div id="'. esc_attr($id) .'" class="sc_chart_item">'
							.'<div class="sc_chart_item_canvas">'
								.'<canvas id="canvas_'. esc_attr($id) .'"  data-percent="'. esc_attr($percent) .'" data-color="'. esc_attr($color) .'" data-darkcolor="'. esc_attr($dark_color) .'"></canvas>'
								.'<div class="sc_chart_item_value" style="color: '.esc_attr($dark_color).';">'. ($value) .'</div>'
							.'</div>'
							.' <div class="sc_chart_title">'.($title).'</div>' 
						.'</div>'
					.'</div>';
								
		return apply_filters('micro_office_shortcode_output', $output, 'trx_chart_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_chart_item', 'micro_office_sc_chart_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_chart_reg_shortcodes' ) ) {
	
	function micro_office_sc_chart_reg_shortcodes() {
	
		micro_office_sc_map("trx_chart", array(
			"title" => esc_html__("Chart", "micro-office"),
			"desc" => wp_kses_data( __("Insert chart diagramm in your page (post)", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", "micro-office"),
					"desc" => wp_kses_data( __("Max value for chart items", "micro-office") ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"color" => array(
					"title" => esc_html__("Chart items color", "micro-office"),
					"desc" => wp_kses_data( __("Color for all chart items", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"dark_color" => array(
					"title" => esc_html__("Dark color", "micro-office"),
					"desc" => wp_kses_data( __("Dark color for all chart items", "micro-office") ),
					"value" => "",
					"type" => "color"
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
			),
			"children" => array(
				"name" => "trx_chart_item",
				"title" => esc_html__("Chart item", "micro-office"),
				"desc" => wp_kses_data( __("Chart item", "micro-office") ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", "micro-office"),
						"desc" => wp_kses_data( __("Current chart item title", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", "micro-office"),
						"desc" => wp_kses_data( __("Current chart value", "micro-office") ),
						"value" => 75,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", "micro-office"),
						"desc" => wp_kses_data( __("Current chart item color", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"dark_color" => array(
						"title" => esc_html__("Dark color", "micro-office"),
						"desc" => wp_kses_data( __("Current chart item dark color", "micro-office") ),
						"value" => "",
						"type" => "color"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_chart_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_chart_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_chart",
			"name" => esc_html__("Chart", "micro-office"),
			"description" => wp_kses_data( __("Insert chart diagramm", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_chart',
			"class" => "trx_sc_collection trx_sc_chart",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_chart_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", "micro-office"),
					"description" => wp_kses_data( __("Max value for chart items", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", "micro-office"),
					"description" => wp_kses_data( __("Chart columns count (required)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("Color for all current item", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "dark_color",
					"heading" => esc_html__("Dark color", "micro-office"),
					"description" => wp_kses_data( __("Dark color for all current item", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
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
		
		
		vc_map( array(
			"base" => "trx_chart_item",
			"name" => esc_html__("Chart item", "micro-office"),
			"description" => wp_kses_data( __("Chart item", "micro-office") ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_chart_item',
			"class" => "trx_sc_single trx_sc_chart_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_chart'),
			"as_parent" => array('except' => 'trx_chart'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for the current chart item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", "micro-office"),
					"description" => wp_kses_data( __("Value for the current chart item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("Color for current chart item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "dark_color",
					"heading" => esc_html__("Dark color", "micro-office"),
					"description" => wp_kses_data( __("Dark color for current chart item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Chart extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Chart_Item extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>