<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_columns_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_columns_theme_setup' );
	function micro_office_sc_columns_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_columns_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_columns_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_columns')) {	
	function micro_office_sc_columns($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"count" => "2",
			"fluid" => "no",
			"margins" => "yes",
			"equalheight" => "no",
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
		$css .= micro_office_get_css_dimensions_from_values($width, $height);
		$count = max(1, min(12, (int) $count));
		micro_office_storage_set('sc_columns_data', array(
			'counter' => 1,
			'equal_selector' => '',
            'after_span2' => false,
            'after_span3' => false,
            'after_span4' => false,
            'count' => $count
            )
        );
		$content = do_shortcode($content);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="columns_wrap sc_columns'
					. ' columns_' . (micro_office_param_is_on($fluid) ? 'fluid' : 'nofluid') 
					. (!empty($margins) && micro_office_param_is_off($margins) ? ' no_margins' : '') 
					. ' sc_columns_count_' . esc_attr($count)
					. (!empty($class) ? ' '.esc_attr($class) : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!micro_office_param_is_off($equalheight) ? ' data-equal-height="'.esc_attr(micro_office_storage_get_array('sc_columns_data', 'equal_selector')).'"' : '')
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. '>'
					. trim($content)
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_columns', $atts, $content);
	}
	micro_office_require_shortcode('trx_columns', 'micro_office_sc_columns');
}


if (!function_exists('micro_office_sc_column_item')) {	
	function micro_office_sc_column_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"span" => "1",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_tile" => "no",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => ""
		), $atts)));
		$css .= ($align !== '' ? 'text-align:' . esc_attr($align) . ';' : '') 
			. ($color !== '' ? 'color:' . esc_attr($color) . ';' : '');
		$span = max(1, min(11, (int) $span));
		if (!empty($bg_image)) {
			if ($bg_image > 0) {
				$attach = wp_get_attachment_image_src( $bg_image, 'full' );
				if (isset($attach[0]) && $attach[0]!='')
					$bg_image = $attach[0];
			}
		}
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="column-'.($span > 1 ? esc_attr($span) : 1).'_'.esc_attr(micro_office_storage_get_array('sc_columns_data', 'count')).' sc_column_item sc_column_item_'.esc_attr(micro_office_storage_get_array('sc_columns_data', 'counter')) 
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (micro_office_storage_get_array('sc_columns_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (micro_office_storage_get_array('sc_columns_data', 'counter') == 1 ? ' first' : '') 
					. ($span > 1 ? ' span_'.esc_attr($span) : '') 
					. (micro_office_storage_get_array('sc_columns_data', 'after_span2') ? ' after_span_2' : '') 
					. (micro_office_storage_get_array('sc_columns_data', 'after_span3') ? ' after_span_3' : '') 
					. (micro_office_storage_get_array('sc_columns_data', 'after_span4') ? ' after_span_4' : '') 
					. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '>'
					. ($bg_color!=='' || $bg_image !== '' ? '<div class="sc_column_item_inner" style="'
							. ($bg_color !== '' ? 'background-color:' . esc_attr($bg_color) . ';' : '')
							. ($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(micro_office_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
							. '">' : '')
						. do_shortcode($content)
					. ($bg_color!=='' || $bg_image !== '' ? '</div>' : '')
					. '</div>';
		micro_office_storage_inc_array('sc_columns_data', 'counter', $span);
		micro_office_storage_set_array('sc_columns_data', 'after_span2', $span==2);
		micro_office_storage_set_array('sc_columns_data', 'after_span3', $span==3);
		micro_office_storage_set_array('sc_columns_data', 'after_span4', $span==4);
		micro_office_storage_set_array('sc_columns_data', 'equal_selector', $bg_color!=='' || $bg_image !== '' ? '.sc_column_item_inner' : '.sc_column_item');
		return apply_filters('micro_office_shortcode_output', $output, 'trx_column_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_column_item', 'micro_office_sc_column_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_columns_reg_shortcodes' ) ) {
	
	function micro_office_sc_columns_reg_shortcodes() {
	
		micro_office_sc_map("trx_columns", array(
			"title" => esc_html__("Columns", "micro-office"),
			"desc" => wp_kses_data( __("Insert up to 5 columns in your page (post)", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"fluid" => array(
					"title" => esc_html__("Fluid columns", "micro-office"),
					"desc" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "micro-office") ),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				), 
				"margins" => array(
					"title" => esc_html__("Margins between columns", "micro-office"),
					"desc" => wp_kses_data( __("Add margins between columns", "micro-office") ),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
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
				"name" => "trx_column_item",
				"title" => esc_html__("Column", "micro-office"),
				"desc" => wp_kses_data( __("Column item", "micro-office") ),
				"container" => true,
				"params" => array(
					"span" => array(
						"title" => esc_html__("Merge columns", "micro-office"),
						"desc" => wp_kses_data( __("Count merged columns from current", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"align" => array(
						"title" => esc_html__("Alignment", "micro-office"),
						"desc" => wp_kses_data( __("Alignment text in the column", "micro-office") ),
						"value" => "",
						"type" => "checklist",
						"dir" => "horizontal",
						"options" => micro_office_get_sc_param('align')
					),
					"color" => array(
						"title" => esc_html__("Fore color", "micro-office"),
						"desc" => wp_kses_data( __("Any color for objects in this column", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", "micro-office"),
						"desc" => wp_kses_data( __("Any background color for this column", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"bg_image" => array(
						"title" => esc_html__("URL for background image file", "micro-office"),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", "micro-office") ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					),
					"bg_tile" => array(
						"title" => esc_html__("Tile background image", "micro-office"),
						"desc" => wp_kses_data( __("Do you want tile background image or image cover whole column?", "micro-office") ),
						"value" => "no",
						"dependency" => array(
							'bg_image' => array('not_empty')
						),
						"type" => "switch",
						"options" => micro_office_get_sc_param('yes_no')
					),
					"_content_" => array(
						"title" => esc_html__("Column item content", "micro-office"),
						"desc" => wp_kses_data( __("Current column item content", "micro-office") ),
						"divider" => true,
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => micro_office_get_sc_param('id'),
					"class" => micro_office_get_sc_param('class'),
					"animation" => micro_office_get_sc_param('animation'),
					"css" => micro_office_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_columns_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_columns_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_columns",
			"name" => esc_html__("Columns", "micro-office"),
			"description" => wp_kses_data( __("Insert columns with margins", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_columns',
			"class" => "trx_sc_columns",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_column_item'),
			"params" => array(
				array(
					"param_name" => "count",
					"heading" => esc_html__("Columns count", "micro-office"),
					"description" => wp_kses_data( __("Number of the columns in the container.", "micro-office") ),
					"admin_label" => true,
					"value" => "2",
					"type" => "textfield"
				),
				array(
					"param_name" => "fluid",
					"heading" => esc_html__("Fluid columns", "micro-office"),
					"description" => wp_kses_data( __("To squeeze the columns when reducing the size of the window (fluid=yes) or to rebuild them (fluid=no)", "micro-office") ),
					"value" => array(esc_html__('Fluid columns', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "equalheight",
					"heading" => esc_html__("Equal height", "micro-office"),
					"description" => wp_kses_data( __("Make equal height for all columns in the row", "micro-office") ),
					"value" => array("Equal height" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "margins",
					"heading" => esc_html__("Margins between columns", "micro-office"),
					"description" => wp_kses_data( __("Add margins between columns", "micro-office") ),
					"std" => "yes",
					"value" => array(esc_html__('Disable margins between columns', 'micro-office') => 'no'),
					"type" => "checkbox"
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
			'default_content' => '
				[trx_column_item][/trx_column_item]
				[trx_column_item][/trx_column_item]
			',
			'js_view' => 'VcTrxColumnsView'
		) );
		
		
		vc_map( array(
			"base" => "trx_column_item",
			"name" => esc_html__("Column", "micro-office"),
			"description" => wp_kses_data( __("Column item", "micro-office") ),
			"show_settings_on_create" => true,
			"class" => "trx_sc_collection trx_sc_column_item",
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_column_item',
			"as_child" => array('only' => 'trx_columns'),
			"as_parent" => array('except' => 'trx_columns'),
			"params" => array(
				array(
					"param_name" => "span",
					"heading" => esc_html__("Merge columns", "micro-office"),
					"description" => wp_kses_data( __("Count merged columns from current", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Alignment text in the column", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", "micro-office"),
					"description" => wp_kses_data( __("Any color for objects in this column", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Any background color for this column", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("URL for background image file", "micro-office"),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the background", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", "micro-office"),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole column?", "micro-office") ),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css')
			),
			'js_view' => 'VcTrxColumnItemView'
		) );
		
		class WPBakeryShortCode_Trx_Columns extends MICRO_OFFICE_VC_ShortCodeColumns {}
		class WPBakeryShortCode_Trx_Column_Item extends MICRO_OFFICE_VC_ShortCodeCollection {}
	}
}
?>