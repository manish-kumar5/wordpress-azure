<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_socials_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_socials_theme_setup' );
	function micro_office_sc_socials_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_socials_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_socials_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_socials')) {	
	function micro_office_sc_socials($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"size" => "small",		// tiny | small | medium | large
			"shape" => "square",	// round | square
			"type" => micro_office_get_theme_setting('socials_type'),	// icons | images
			"socials" => "",
			"custom" => "no",
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
		micro_office_storage_set('sc_social_data', array(
			'icons' => false,
            'type' => $type
            )
        );
		if (!empty($socials)) {
			$allowed = explode('|', $socials);
			$list = array();
			for ($i=0; $i<count($allowed); $i++) {
				$s = explode('=', $allowed[$i]);
				if (!empty($s[1])) {
					$list[] = array(
						'icon'	=> $type=='images' ? micro_office_get_socials_url($s[0]) : 'icon-'.trim($s[0]),
						'url'	=> $s[1]
						);
				}
			}
			if (count($list) > 0) micro_office_storage_set_array('sc_social_data', 'icons', $list);
		} else if (micro_office_param_is_on($custom))
			$content = do_shortcode($content);
		if (micro_office_storage_get_array('sc_social_data', 'icons')===false) micro_office_storage_set_array('sc_social_data', 'icons', micro_office_get_custom_option('social_icons'));
		$output = micro_office_prepare_socials(micro_office_storage_get_array('sc_social_data', 'icons'));
		$output = $output
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_socials sc_socials_type_' . esc_attr($type) . ' sc_socials_shape_' . esc_attr($shape) . ' sc_socials_size_' . esc_attr($size) . (!empty($class) ? ' '.esc_attr($class) : '') . '"' 
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. '>' 
				. ($output)
				. '</div>'
			: '';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_socials', $atts, $content);
	}
	micro_office_require_shortcode('trx_socials', 'micro_office_sc_socials');
}


if (!function_exists('micro_office_sc_social_item')) {	
	function micro_office_sc_social_item($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"name" => "",
			"url" => "",
			"icon" => ""
		), $atts)));
		if (empty($icon)) {
			if (!empty($name)) {
				$type = micro_office_storage_get_array('sc_social_data', 'type');
				if ($type=='images') {
					if (file_exists(micro_office_get_socials_dir($name.'.png')))
						$icon = micro_office_get_socials_url($name.'.png');
				} else
					$icon = 'icon-'.esc_attr($name);
			}
		} else if ((int) $icon > 0) {
			$attach = wp_get_attachment_image_src( $icon, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$icon = $attach[0];
		}
		if (!empty($icon) && !empty($url)) {
			if (micro_office_storage_get_array('sc_social_data', 'icons')===false) micro_office_storage_set_array('sc_social_data', 'icons', array());
			micro_office_storage_set_array2('sc_social_data', 'icons', '', array(
				'icon' => $icon,
				'url' => $url
				)
			);
		}
		return '';
	}
	micro_office_require_shortcode('trx_social_item', 'micro_office_sc_social_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_socials_reg_shortcodes' ) ) {
	
	function micro_office_sc_socials_reg_shortcodes() {
	
		micro_office_sc_map("trx_socials", array(
			"title" => esc_html__("Social icons", "micro-office"),
			"desc" => wp_kses_data( __("List of social icons (with hovers)", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"socials" => array(
					"title" => esc_html__("Manual socials list", "micro-office"),
					"desc" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "text"
				),
				"custom" => array(
					"title" => esc_html__("Custom socials", "micro-office"),
					"desc" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", "micro-office") ),
					"divider" => true,
					"value" => "no",
					"options" => micro_office_get_sc_param('yes_no'),
					"type" => "switch"
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
				"name" => "trx_social_item",
				"title" => esc_html__("Custom social item", "micro-office"),
				"desc" => wp_kses_data( __("Custom social item: name, profile url and icon url", "micro-office") ),
				"decorate" => false,
				"container" => false,
				"params" => array(
					"name" => array(
						"title" => esc_html__("Social name", "micro-office"),
						"desc" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"url" => array(
						"title" => esc_html__("Your profile URL", "micro-office"),
						"desc" => wp_kses_data( __("URL of your profile in specified social network", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"icon" => array(
						"title" => esc_html__("URL (source) for icon file", "micro-office"),
						"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", "micro-office") ),
						"readonly" => false,
						"value" => "",
						"type" => "media"
					)
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_socials_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_socials_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_socials",
			"name" => esc_html__("Social icons", "micro-office"),
			"description" => wp_kses_data( __("Custom social icons", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_socials',
			"class" => "trx_sc_collection trx_sc_socials",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_social_item'),
			"params" => array_merge(array(
				array(
					"param_name" => "socials",
					"heading" => esc_html__("Manual socials list", "micro-office"),
					"description" => wp_kses_data( __("Custom list of social networks. For example: twitter=http://twitter.com/my_profile|facebook=http://facebook.com/my_profile. If empty - use socials from Theme options.", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "custom",
					"heading" => esc_html__("Custom socials", "micro-office"),
					"description" => wp_kses_data( __("Make custom icons from inner shortcodes (prepare it on tabs)", "micro-office") ),
					"class" => "",
					"value" => array(esc_html__('Custom socials', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			))
		) );
		
		
		vc_map( array(
			"base" => "trx_social_item",
			"name" => esc_html__("Custom social item", "micro-office"),
			"description" => wp_kses_data( __("Custom social item: name, profile url and icon url", "micro-office") ),
			"show_settings_on_create" => true,
			"content_element" => true,
			"is_container" => false,
			'icon' => 'icon_trx_social_item',
			"class" => "trx_sc_single trx_sc_social_item",
			"as_child" => array('only' => 'trx_socials'),
			"as_parent" => array('except' => 'trx_socials'),
			"params" => array(
				array(
					"param_name" => "name",
					"heading" => esc_html__("Social name", "micro-office"),
					"description" => wp_kses_data( __("Name (slug) of the social network (twitter, facebook, linkedin, etc.)", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "url",
					"heading" => esc_html__("Your profile URL", "micro-office"),
					"description" => wp_kses_data( __("URL of your profile in specified social network", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("URL (source) for icon file", "micro-office"),
					"description" => wp_kses_data( __("Select or upload image or write URL from other site for the current social icon", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				)
			)
		) );
		
		class WPBakeryShortCode_Trx_Socials extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Social_Item extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>