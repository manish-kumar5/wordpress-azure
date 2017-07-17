<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_graph_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_graph_theme_setup' );
	function micro_office_sc_graph_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_graph_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_graph_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_graph')) {	
	function micro_office_sc_graph($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"labels" => "Label1, Label2, Label3, Label4",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "100%",
			"height" => "220"
		), $atts)));
		wp_enqueue_script( 'diagram-chart', micro_office_get_file_url('/js/diagram/chart.min.js'), array('jquery'), null, true );
		wp_enqueue_script( 'diagram-raphael', micro_office_get_file_url('/js/diagram/diagram.raphael.js'), array('jquery'), null, true );
		wp_enqueue_script( 'graph', micro_office_get_file_url('/js/diagram/Graph.js'), array('jquery'), null, true );
	
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width,$height);
		$content = do_shortcode($content);   
		$output = '<div ' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="sc_graph tw-chart-graph tw-animate tw-redraw with-list-desc'
					. (!empty($class) ? ' '.esc_attr($class) : '') .'"'
					.' data-zero="false" data-labels="'.$labels.'"'
					.' data-type="Curve" data-item-height="'.$height.'" data-animation-delay="0" data-animation-offset="90%"'
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') .'>'
					.'<ul class="data" style="display: none;">'
					. $content
					.'</ul>'
					.'<canvas></canvas>'
					.'</div>';	
		return apply_filters('micro_office_shortcode_output', $output, 'trx_graph', $atts, $content);
	}
	micro_office_require_shortcode('trx_graph', 'micro_office_sc_graph');
}


if (!function_exists('micro_office_sc_graph_item')) {	
	function micro_office_sc_graph_item($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"datas" => "30,50,40,70",
			"color" => "#76D3E1"
		), $atts)));
		
		if($content == '') $content = "Attribute";
		$output =  '<li data-datas="'.$datas.'" data-fill-color="'.$color.'" data-fill-text="'.$content.'"></li>';    
	
		return apply_filters('micro_office_shortcode_output', $output, 'trx_ggraph_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_graph_item', 'micro_office_sc_graph_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_graph_reg_shortcodes' ) ) {
	
	function micro_office_sc_graph_reg_shortcodes() {
	
		micro_office_sc_map("trx_graph", array(
			"title" => esc_html__("Graph", "micro-office"),
			"desc" => wp_kses_data( __("Insert a graph into post (page). ", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"labels" => array(
					"title" => esc_html__("Labels", "micro-office"),
					"desc" => wp_kses_data( __("Insert labels separate with comma", "micro-office") ),
					"value" => "Label1, Label2, Label3, Label4",
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
			),
			"children" => array(
				"name" => "trx_graph_item",
				"title" => esc_html__("Item", "micro-office"),
				"desc" => wp_kses_data( __("Graph item", "micro-office") ),
				"container" => false,
				"params" => array(
					"datas" => array(
						"title" => __("Datas", "micro-office"),
						"desc" => __("Insert datas separate with comma", "micro-office"),
						"value" => "30,50,40,70",
						"type" => "text"
					),
					"color" => array(
						"title" => __("Color", "micro-office"),
						"desc" => __("Item color", "micro-office"),
						"value" => "#76D3E1",
						"type" => "color"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_graph_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_graph_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_graph",
			"name" => esc_html__("Graph", "micro-office"),
			"description" => wp_kses_data( __("Insert a graph", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_graph',
			"class" => "trx_sc_container trx_sc_graph",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_graph_item'),
			"params" => array(
				array(
					"param_name" => "labels",
					"heading" => esc_html__("Labels", "micro-office"),
					"description" => wp_kses_data( __("Insert labels separate with comma", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "Label1, Label2, Label3, Label4",
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
			)
		) );
		
		vc_map( array(
			"base" => "trx_graph_item",
			"name" => esc_html__("Graph item", "micro-office"),
			"description" => wp_kses_data( __("Graph item", "micro-office") ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_graph_item',
			"class" => "trx_sc_single trx_sc_graph_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_graph'),
			"as_parent" => array('except' => 'trx_graph'),
			"params" => array(
				array(
						"param_name" => "datas",
						"heading" => __("Datas", "micro-office"),
						"description" => __("Insert datas separate with comma", "micro-office"),
						"class" => "",
						"admin_label" => true,
						"value" => '30,50,40,70',
						"type" => "textfield"
					),
					array(
						"param_name" => "color",
						"heading" => __("Color", "micro-office"),
						"description" => __("Item color", "micro-office"),
						"class" => "",
						"admin_label" => true,
						"value" => "#76D3E1",
						"type" => "colorpicker"
					)
			)
		) );
		
		class WPBakeryShortCode_Trx_Graph extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Graph_Item extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>