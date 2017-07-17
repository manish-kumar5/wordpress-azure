<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_list_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_list_theme_setup' );
	function micro_office_sc_list_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_list_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_list_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_list')) {	
	function micro_office_sc_list($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "ul",
			"icon" => "icon-right",
			"icon_color" => "",
			"color" => "",
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
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($style) == '' || (trim($icon) == '' && $style=='iconed')) $style = 'ul';
		micro_office_storage_set('sc_list_data', array(
			'counter' => 0,
            'icon' => empty($icon) || micro_office_param_is_inherit($icon) ? "icon-right" : $icon,
            'icon_color' => $icon_color,
            'style' => $style
            )
        );
		$output = '<' . ($style=='ol' ? 'ol' : 'ul')
				. ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_list sc_list_style_' . esc_attr($style) . (!empty($class) ? ' '.esc_attr($class) : '') . '"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. '>'
				. do_shortcode($content)
				. '</' .($style=='ol' ? 'ol' : 'ul') . '>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_list', $atts, $content);
	}
	micro_office_require_shortcode('trx_list', 'micro_office_sc_list');
}


if (!function_exists('micro_office_sc_list_item')) {	
	function micro_office_sc_list_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"color" => "",
			"icon" => "",
			"icon_color" => "",
			"title" => "",
			"link" => "",
			"target" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		micro_office_storage_inc_array('sc_list_data', 'counter');
		$css .= $color !== '' ? 'color:' . esc_attr($color) .';' : '';
		if (trim($icon) == '' || micro_office_param_is_inherit($icon)) $icon = micro_office_storage_get_array('sc_list_data', 'icon');
		if (trim($color) == '' || micro_office_param_is_inherit($icon_color)) $icon_color = micro_office_storage_get_array('sc_list_data', 'icon_color');
		$content = do_shortcode($content);
		if (empty($content)) $content = $title;
		$output = '<li' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_list_item' 
			. (!empty($class) ? ' '.esc_attr($class) : '')
			. (micro_office_storage_get_array('sc_list_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
			. (micro_office_storage_get_array('sc_list_data', 'counter') == 1 ? ' first' : '')  
			. '"' 
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. ($title ? ' title="'.esc_attr($title).'"' : '') 
			. '>' 
			. (!empty($link) ? '<a href="'.esc_url($link).'"' . (!empty($target) ? ' target="'.esc_attr($target).'"' : '') . '>' : '')
			. (micro_office_storage_get_array('sc_list_data', 'style')=='iconed' && $icon!='' ? '<span class="sc_list_icon '.esc_attr($icon).'"'.($icon_color !== '' ? ' style="color:'.esc_attr($icon_color).';"' : '').'></span>' : '')
			. trim($content)
			. (!empty($link) ? '</a>': '')
			. '</li>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_list_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_list_item', 'micro_office_sc_list_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_list_reg_shortcodes' ) ) {
	
	function micro_office_sc_list_reg_shortcodes() {
	
		micro_office_sc_map("trx_list", array(
			"title" => esc_html__("List", "micro-office"),
			"desc" => wp_kses_data( __("List items with specific bullets", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Bullet's style", "micro-office"),
					"desc" => wp_kses_data( __("Bullet's style for each list item", "micro-office") ),
					"value" => "ul",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('list_styles')
				), 
				"color" => array(
					"title" => esc_html__("Color", "micro-office"),
					"desc" => wp_kses_data( __("List items color", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"icon" => array(
					"title" => esc_html__('List icon',  'micro-office'),
					"desc" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)",  'micro-office') ),
					"dependency" => array(
						'style' => array('iconed')
					),
					"value" => "",
					"type" => "icons",
					"options" => micro_office_get_sc_param('icons')
				),
				"icon_color" => array(
					"title" => esc_html__("Icon color", "micro-office"),
					"desc" => wp_kses_data( __("List icons color", "micro-office") ),
					"value" => "",
					"dependency" => array(
						'style' => array('iconed')
					),
					"type" => "color"
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
				"name" => "trx_list_item",
				"title" => esc_html__("Item", "micro-office"),
				"desc" => wp_kses_data( __("List item with specific bullet", "micro-office") ),
				"decorate" => false,
				"container" => true,
				"params" => array(
					"_content_" => array(
						"title" => esc_html__("List item content", "micro-office"),
						"desc" => wp_kses_data( __("Current list item content", "micro-office") ),
						"rows" => 4,
						"value" => "",
						"type" => "textarea"
					),
					"title" => array(
						"title" => esc_html__("List item title", "micro-office"),
						"desc" => wp_kses_data( __("Current list item title (show it as tooltip)", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"color" => array(
						"title" => esc_html__("Color", "micro-office"),
						"desc" => wp_kses_data( __("Text color for this item", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"icon" => array(
						"title" => esc_html__('List icon',  'micro-office'),
						"desc" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)",  'micro-office') ),
						"value" => "",
						"type" => "icons",
						"options" => micro_office_get_sc_param('icons')
					),
					"icon_color" => array(
						"title" => esc_html__("Icon color", "micro-office"),
						"desc" => wp_kses_data( __("Icon color for this item", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"link" => array(
						"title" => esc_html__("Link URL", "micro-office"),
						"desc" => wp_kses_data( __("Link URL for the current list item", "micro-office") ),
						"divider" => true,
						"value" => "",
						"type" => "text"
					),
					"target" => array(
						"title" => esc_html__("Link target", "micro-office"),
						"desc" => wp_kses_data( __("Link target for the current list item", "micro-office") ),
						"value" => "",
						"type" => "text"
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
if ( !function_exists( 'micro_office_sc_list_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_list_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_list",
			"name" => esc_html__("List", "micro-office"),
			"description" => wp_kses_data( __("List items with specific bullets", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			"class" => "trx_sc_collection trx_sc_list",
			'icon' => 'icon_trx_list',
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"as_parent" => array('only' => 'trx_list_item'),
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Bullet's style", "micro-office"),
					"description" => wp_kses_data( __("Bullet's style for each list item", "micro-office") ),
					"class" => "",
					"admin_label" => true,
					"value" => array_flip(micro_office_get_sc_param('list_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("List items color", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List icon", "micro-office"),
					"description" => wp_kses_data( __("Select list icon from Fontello icons set (only for style=Iconed)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => micro_office_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "micro-office"),
					"description" => wp_kses_data( __("List icons color", "micro-office") ),
					"class" => "",
					'dependency' => array(
						'element' => 'style',
						'value' => array('iconed')
					),
					"value" => "",
					"type" => "colorpicker"
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
				[trx_list_item][/trx_list_item]
				[trx_list_item][/trx_list_item]
			'
		) );
		
		
		vc_map( array(
			"base" => "trx_list_item",
			"name" => esc_html__("List item", "micro-office"),
			"description" => wp_kses_data( __("List item with specific bullet", "micro-office") ),
			"class" => "trx_sc_container trx_sc_list_item",
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => true,
			'icon' => 'icon_trx_list_item',
			"as_child" => array('only' => 'trx_list'), // Use only|except attributes to limit parent (separate multiple values with comma)
			"as_parent" => array('except' => 'trx_list'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("List item title", "micro-office"),
					"description" => wp_kses_data( __("Title for the current list item (show it as tooltip)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link URL", "micro-office"),
					"description" => wp_kses_data( __("Link URL for the current list item", "micro-office") ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "target",
					"heading" => esc_html__("Link target", "micro-office"),
					"description" => wp_kses_data( __("Link target for the current list item", "micro-office") ),
					"admin_label" => true,
					"group" => esc_html__('Link', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("Text color for this item", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("List item icon", "micro-office"),
					"description" => wp_kses_data( __("Select list item icon from Fontello icons set (only for style=Iconed)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => micro_office_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon_color",
					"heading" => esc_html__("Icon color", "micro-office"),
					"description" => wp_kses_data( __("Icon color for this item", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css')
			)
		
		) );
		
		class WPBakeryShortCode_Trx_List extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_List_Item extends MICRO_OFFICE_VC_ShortCodeContainer {}
	}
}
?>