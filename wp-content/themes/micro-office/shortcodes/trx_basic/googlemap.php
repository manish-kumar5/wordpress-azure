<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_googlemap_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_googlemap_theme_setup' );
	function micro_office_sc_googlemap_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_googlemap_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_googlemap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_googlemap')) {	
	function micro_office_sc_googlemap($atts, $content = null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"zoom" => 16,
			"style" => 'default',
			"scheme" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "100%",
			"height" => "400",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width, $height);
		if (empty($id)) $id = 'sc_googlemap_'.str_replace('.', '', mt_rand());
		if (empty($style)) $style = micro_office_get_custom_option('googlemap_style');
		$api_key = micro_office_get_theme_option('api_google');
		wp_enqueue_script( 'googlemap', micro_office_get_protocol().'://maps.google.com/maps/api/js'.($api_key ? '?key='.$api_key : ''), array(), null, true );
		wp_enqueue_script( 'micro_office-googlemap-script', micro_office_get_file_url('js/core.googlemap.js'), array(), null, true );
		micro_office_storage_set('sc_googlemap_markers', array());
		$content = do_shortcode($content);
		$output = '';
		$markers = micro_office_storage_get('sc_googlemap_markers');
		if (count($markers) == 0) {
			$markers[] = array(
				'title' => micro_office_get_custom_option('googlemap_title'),
				'description' => micro_office_strmacros(micro_office_get_custom_option('googlemap_description')),
				'latlng' => micro_office_get_custom_option('googlemap_latlng'),
				'address' => micro_office_get_custom_option('googlemap_address'),
				'point' => micro_office_get_custom_option('googlemap_marker')
			);
		}
		$output .= 
			($content ? '<div id="'.esc_attr($id).'_wrap" class="sc_googlemap_wrap'
					. ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. '">' : '')
			. '<div id="'.esc_attr($id).'"'
				. ' class="sc_googlemap'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. ' data-zoom="'.esc_attr($zoom).'"'
				. ' data-style="'.esc_attr($style).'"'
				. '>';
		$cnt = 0;
		foreach ($markers as $marker) {
			$cnt++;
			if (empty($marker['id'])) $marker['id'] = $id.'_'.intval($cnt);
			$output .= '<div id="'.esc_attr($marker['id']).'" class="sc_googlemap_marker"'
				. ' data-title="'.esc_attr($marker['title']).'"'
				. ' data-description="'.esc_attr(micro_office_strmacros($marker['description'])).'"'
				. ' data-address="'.esc_attr($marker['address']).'"'
				. ' data-latlng="'.esc_attr($marker['latlng']).'"'
				. ' data-point="'.esc_attr($marker['point']).'"'
				. '></div>';
		}
		$output .= '</div>'
			. ($content ? '<div class="sc_googlemap_content">' . trim($content) . '</div></div>' : '');
			
		return apply_filters('micro_office_shortcode_output', $output, 'trx_googlemap', $atts, $content);
	}
	micro_office_require_shortcode("trx_googlemap", "micro_office_sc_googlemap");
}


if (!function_exists('micro_office_sc_googlemap_marker')) {	
	function micro_office_sc_googlemap_marker($atts, $content = null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"address" => "",
			"latlng" => "",
			"point" => "",
			// Common params
			"id" => ""
		), $atts)));
		if (!empty($point)) {
			if ($point > 0) {
				$attach = wp_get_attachment_image_src( $point, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$point = $attach[0];
			}
		}
		$content = do_shortcode($content);
		micro_office_storage_set_array('sc_googlemap_markers', '', array(
			'id' => $id,
			'title' => $title,
			'description' => !empty($content) ? $content : $address,
			'latlng' => $latlng,
			'address' => $address,
			'point' => $point ? $point : micro_office_get_custom_option('googlemap_marker')
			)
		);
		return '';
	}
	micro_office_require_shortcode("trx_googlemap_marker", "micro_office_sc_googlemap_marker");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_googlemap_reg_shortcodes' ) ) {
	
	function micro_office_sc_googlemap_reg_shortcodes() {
	
		micro_office_sc_map("trx_googlemap", array(
			"title" => esc_html__("Google map", "micro-office"),
			"desc" => wp_kses_data( __("Insert Google map with specified markers", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"zoom" => array(
					"title" => esc_html__("Zoom", "micro-office"),
					"desc" => wp_kses_data( __("Map zoom factor", "micro-office") ),
					"divider" => true,
					"value" => 16,
					"min" => 1,
					"max" => 20,
					"type" => "spinner"
				),
				"style" => array(
					"title" => esc_html__("Map style", "micro-office"),
					"desc" => wp_kses_data( __("Select map style", "micro-office") ),
					"value" => "default",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('googlemap_styles')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "micro-office"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('schemes')
				),
				"width" => micro_office_shortcodes_width('100%'),
				"height" => micro_office_shortcodes_height(240),
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
				"name" => "trx_googlemap_marker",
				"title" => esc_html__("Google map marker", "micro-office"),
				"desc" => wp_kses_data( __("Google map marker", "micro-office") ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"address" => array(
						"title" => esc_html__("Address", "micro-office"),
						"desc" => wp_kses_data( __("Address of this marker", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"latlng" => array(
						"title" => esc_html__("Latitude and Longitude", "micro-office"),
						"desc" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"point" => array(
						"title" => esc_html__("URL for marker image file", "micro-office"),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "micro-office") ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"title" => array(
						"title" => esc_html__("Title", "micro-office"),
						"desc" => wp_kses_data( __("Title for this marker", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Description", "micro-office"),
						"desc" => wp_kses_data( __("Description for this marker", "micro-office") ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => micro_office_get_sc_param('id')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_googlemap_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_googlemap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_googlemap",
			"name" => esc_html__("Google map", "micro-office"),
			"description" => wp_kses_data( __("Insert Google map with desired address or coordinates", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_googlemap',
			"class" => "trx_sc_collection trx_sc_googlemap",
			"content_element" => true,
			"is_container" => true,
			"as_parent" => array('only' => 'trx_googlemap_marker,trx_form,trx_section,trx_block,trx_promo'),
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "zoom",
					"heading" => esc_html__("Zoom", "micro-office"),
					"description" => wp_kses_data( __("Map zoom factor", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "16",
					"type" => "textfield"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "micro-office"),
					"description" => wp_kses_data( __("Map custom style", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('googlemap_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "micro-office"),
					"description" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_vc_width('100%'),
				micro_office_vc_height(240),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			)
		) );
		
		vc_map( array(
			"base" => "trx_googlemap_marker",
			"name" => esc_html__("Googlemap marker", "micro-office"),
			"description" => wp_kses_data( __("Insert new marker into Google map", "micro-office") ),
			"class" => "trx_sc_collection trx_sc_googlemap_marker",
			'icon' => 'icon_trx_googlemap_marker',
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			"as_child" => array('only' => 'trx_googlemap'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "address",
					"heading" => esc_html__("Address", "micro-office"),
					"description" => wp_kses_data( __("Address of this marker", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "latlng",
					"heading" => esc_html__("Latitude and Longitude", "micro-office"),
					"description" => wp_kses_data( __("Comma separated marker's coorditanes (instead Address)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for this marker", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "point",
					"heading" => esc_html__("URL for marker image file", "micro-office"),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for this marker. If empty - use default marker", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				micro_office_get_vc_param('id')
			)
		) );
		
		class WPBakeryShortCode_Trx_Googlemap extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Googlemap_Marker extends MICRO_OFFICE_VC_ShortCodeCollection {}
	}
}
?>