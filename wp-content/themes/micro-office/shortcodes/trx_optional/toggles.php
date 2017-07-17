<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_toggles_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_toggles_theme_setup' );
	function micro_office_sc_toggles_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_toggles_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_toggles_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_toggles')) {	
	function micro_office_sc_toggles($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"counter" => "off",
			"icon_closed" => "icon-plus",
			"icon_opened" => "icon-minus",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		micro_office_storage_set('sc_toggle_data', array(
			'counter' => 0,
            'show_counter' => micro_office_param_is_on($counter),
            'icon_closed' => empty($icon_closed) || micro_office_param_is_inherit($icon_closed) ? "icon-plus" : $icon_closed,
            'icon_opened' => empty($icon_opened) || micro_office_param_is_inherit($icon_opened) ? "icon-minus" : $icon_opened
            )
        );
		wp_enqueue_script('jquery-effects-slide', false, array('jquery','jquery-effects-core'), null, true);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_toggles'
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (micro_office_param_is_on($counter) ? ' sc_show_counter' : '') 
					. '"'
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. '>'
				. do_shortcode($content)
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_toggles', $atts, $content);
	}
	micro_office_require_shortcode('trx_toggles', 'micro_office_sc_toggles');
}


if (!function_exists('micro_office_sc_toggles_item')) {	
	function micro_office_sc_toggles_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"open" => "",
			"icon_closed" => "",
			"icon_opened" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		micro_office_storage_inc_array('sc_toggle_data', 'counter');
		if (empty($icon_closed) || micro_office_param_is_inherit($icon_closed)) $icon_closed = micro_office_storage_get_array('sc_toggles_data', 'icon_closed', '', "icon-plus");
		if (empty($icon_opened) || micro_office_param_is_inherit($icon_opened)) $icon_opened = micro_office_storage_get_array('sc_toggles_data', 'icon_opened', '', "icon-minus");
		$css .= micro_office_param_is_on($open) ? 'display:block;' : '';
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_toggles_item'.(micro_office_param_is_on($open) ? ' sc_active' : '')
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. (micro_office_storage_get_array('sc_toggle_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
					. (micro_office_storage_get_array('sc_toggle_data', 'counter') == 1 ? ' first' : '')
					. '">'
					. '<h5 class="sc_toggles_title'.(micro_office_param_is_on($open) ? ' ui-state-active' : '').'">'
					. (micro_office_storage_get_array('sc_toggle_data', 'show_counter') ? '<span class="sc_items_counter">'.(micro_office_storage_get_array('sc_toggle_data', 'counter')).'</span>' : '')
					. ($title) 
					. '</h5>'
					. '<div class="sc_toggles_content"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						.'>' 
						. do_shortcode($content) 
					. '</div>'
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_toggles_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_toggles_item', 'micro_office_sc_toggles_item');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_toggles_reg_shortcodes' ) ) {
	
	function micro_office_sc_toggles_reg_shortcodes() {
	
		micro_office_sc_map("trx_toggles", array(
			"title" => esc_html__("Toggles", "micro-office"),
			"desc" => wp_kses_data( __("Toggles items", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
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
				"name" => "trx_toggles_item",
				"title" => esc_html__("Toggles item", "micro-office"),
				"desc" => wp_kses_data( __("Toggles item", "micro-office") ),
				"container" => true,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Toggles item title", "micro-office"),
						"desc" => wp_kses_data( __("Title for current toggles item", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"open" => array(
						"title" => esc_html__("Open on show", "micro-office"),
						"desc" => wp_kses_data( __("Open current toggles item on show", "micro-office") ),
						"value" => "no",
						"type" => "switch",
						"options" => micro_office_get_sc_param('yes_no')
					),
					"_content_" => array(
						"title" => esc_html__("Toggles item content", "micro-office"),
						"desc" => wp_kses_data( __("Current toggles item content", "micro-office") ),
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
if ( !function_exists( 'micro_office_sc_toggles_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_toggles_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_toggles",
			"name" => esc_html__("Toggles", "micro-office"),
			"description" => wp_kses_data( __("Toggles items", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_toggles',
			"class" => "trx_sc_collection trx_sc_toggles",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_toggles_item'),
			"params" => array(
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			),
			'default_content' => '
				[trx_toggles_item title="' . esc_html__( 'Item 1 title', 'micro-office' ) . '"][/trx_toggles_item]
				[trx_toggles_item title="' . esc_html__( 'Item 2 title', 'micro-office' ) . '"][/trx_toggles_item]
			',
			"custom_markup" => '
				<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
					%content%
				</div>
				<div class="tab_controls">
					<button class="add_tab" title="'.esc_attr__("Add item", "micro-office").'">'.esc_html__("Add item", "micro-office").'</button>
				</div>
			',
			'js_view' => 'VcTrxTogglesView'
		) );
		
		
		vc_map( array(
			"base" => "trx_toggles_item",
			"name" => esc_html__("Toggles item", "micro-office"),
			"description" => wp_kses_data( __("Single toggles item", "micro-office") ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_toggles_item',
			"as_child" => array('only' => 'trx_toggles'),
			"as_parent" => array('except' => 'trx_toggles'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for current toggles item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "open",
					"heading" => esc_html__("Open on show", "micro-office"),
					"description" => wp_kses_data( __("Open current toggle item on show", "micro-office") ),
					"class" => "",
					"value" => array("Opened" => "yes" ),
					"type" => "checkbox"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css')
			),
			'js_view' => 'VcTrxTogglesTabView'
		) );
		class WPBakeryShortCode_Trx_Toggles extends MICRO_OFFICE_VC_ShortCodeToggles {}
		class WPBakeryShortCode_Trx_Toggles_Item extends MICRO_OFFICE_VC_ShortCodeTogglesItem {}
	}
}
?>