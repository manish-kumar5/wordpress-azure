<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_accordion_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_accordion_theme_setup' );
	function micro_office_sc_accordion_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_accordion_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_accordion_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_accordion')) {	
	function micro_office_sc_accordion($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"initial" => "1",
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$initial = max(0, (int) $initial);
		micro_office_storage_set('sc_accordion_data', array(
			'counter' => 0,
            'show_counter' => micro_office_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || micro_office_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || micro_office_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-ui-accordion', false, array('jquery','jquery-ui-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (micro_office_param_is_on($counter) ? ' sc_show_counter' : '') 
				. '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. ' data-active="' . ($initial-1) . '"'
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_accordion', $atts, $content);
	}
	micro_office_require_shortcode('trx_accordion', 'micro_office_sc_accordion');
}


if (!function_exists('micro_office_sc_accordion_item')) {	
	function micro_office_sc_accordion_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"icon_closed" => "",
			"icon_opened" => "",
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		micro_office_storage_inc_array('sc_accordion_data', 'counter');
		if (empty($icon_closed) || micro_office_param_is_inherit($icon_closed)) $icon_closed = micro_office_storage_get_array('sc_accordion_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || micro_office_param_is_inherit($icon_opened)) $icon_opened = micro_office_storage_get_array('sc_accordion_data', 'icon_opened', '', "icon-minus");
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_accordion_item' 
				. (!empty($class) ? ' '.esc_attr($class) : '')
				. (micro_office_storage_get_array('sc_accordion_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
				. (micro_office_storage_get_array('sc_accordion_data', 'counter') == 1 ? ' first' : '') 
				. '">'
				. '<h5 class="sc_accordion_title">'
				. (!micro_office_param_is_off($icon_closed) ? '<span class="sc_accordion_icon sc_accordion_icon_closed '.esc_attr($icon_closed).'"></span>' : '')
				. (!micro_office_param_is_off($icon_opened) ? '<span class="sc_accordion_icon sc_accordion_icon_opened '.esc_attr($icon_opened).'"></span>' : '')
				. (micro_office_storage_get_array('sc_accordion_data', 'show_counter') ? '<span class="sc_items_counter">'.(micro_office_storage_get_array('sc_accordion_data', 'counter')).'</span>' : '')
				. ($title)
				. '</h5>'
				. '<div class="sc_accordion_content"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. do_shortcode($content) 
				. '</div>'
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_accordion_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_accordion_item', 'micro_office_sc_accordion_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_accordion_reg_shortcodes' ) ) {
	
	function micro_office_sc_accordion_reg_shortcodes() {
	
		micro_office_sc_map("trx_accordion", array(
			"title" => esc_html__("Accordion", "micro-office"),
			"desc" => wp_kses_data( __("Accordion items", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"initial" => array(
					"title" => esc_html__("Initially opened item", "micro-office"),
					"desc" => wp_kses_data( __("Number of initially opened item", "micro-office") ),
					"value" => 1,
					"min" => 0,
					"type" => "spinner"
				),
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
				"name" => "trx_accordion_item",
				"title" => esc_html__("Item", "micro-office"),
				"desc" => wp_kses_data( __("Accordion item", "micro-office") ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Accordion item title", "micro-office"),
						"desc" => wp_kses_data( __("Title for current accordion item", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"_content_" => array(
						"title" => esc_html__("Accordion item content", "micro-office"),
						"desc" => wp_kses_data( __("Current accordion item content", "micro-office") ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"id" => micro_office_get_sc_param('id'),
					"class" => micro_office_get_sc_param('class'),
					"css" => micro_office_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_accordion_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_accordion_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_accordion",
			"name" => esc_html__("Accordion", "micro-office"),
			"description" => wp_kses_data( __("Accordion items", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_accordion',
			"class" => "trx_sc_collection trx_sc_accordion",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_accordion_item'),	// Use only|except attributes to limit child shortcodes (separate multiple values with comma)
			"params" => array(
				array(
					"param_name" => "initial",
					"heading" => esc_html__("Initially opened item", "micro-office"),
					"description" => wp_kses_data( __("Number of initially opened item", "micro-office") ),
					"class" => "",
					"value" => 1,
					"type" => "textfield"
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
			'default_content' => '
				[trx_accordion_item title="' . esc_html__( 'Item 1 title', 'micro-office' ) . '"][/trx_accordion_item]
				[trx_accordion_item title="' . esc_html__( 'Item 2 title', 'micro-office' ) . '"][/trx_accordion_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", "micro-office").'">'.esc_html__("Add item", "micro-office").'</button>
				</div>
			',
			'js_view' => 'VcTrxAccordionView'
		) );
		
		
		vc_map( array(
			"base" => "trx_accordion_item",
			"name" => esc_html__("Accordion item", "micro-office"),
			"description" => wp_kses_data( __("Inner accordion item", "micro-office") ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_accordion_item',
			"as_child" => array('only' => 'trx_accordion'), 	// Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_accordion'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for current accordion item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css')
			),
		  'js_view' => 'VcTrxAccordionTabView'
		) );

		class WPBakeryShortCode_Trx_Accordion extends MICRO_OFFICE_VC_ShortCodeAccordion {}
		class WPBakeryShortCode_Trx_Accordion_Item extends MICRO_OFFICE_VC_ShortCodeAccordionItem {}
	}
}
?>