<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_image_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_image_theme_setup' );
	function micro_office_sc_image_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_image_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_image_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_image')) {	
	function micro_office_sc_image($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"align" => "",
			"shape" => "square",
			"src" => "",
			"url" => "",
			"icon" => "",
			"link" => "",
			"full_width" => "",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => "",
			"width" => "",
			"height" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width, $height);
		$src = $src!='' ? $src : $url;
		if ($src > 0) {
			$attach = wp_get_attachment_image_src( $src, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$src = $attach[0];
		}
		if (!empty($width) || !empty($height)) {
			$w = !empty($width) && strlen(intval($width)) == strlen($width) ? $width : null;
			$h = !empty($height) && strlen(intval($height)) == strlen($height) ? $height : null;
			if ($w || $h) $src = micro_office_get_resized_image_url($src, $w, $h);
		}
		if (trim($link)) micro_office_enqueue_popup();
		$output = empty($src) ? '' : ('<figure' . ($id ? ' id="'.esc_attr($id).'"' : '') 
			. ' class="sc_image ' . ($align && $align!='none' ? ' align' . esc_attr($align) : '') . (!empty($shape) ? ' sc_image_shape_'.esc_attr($shape) : '') . (!empty($class) ? ' '.esc_attr($class) : '') . ($full_width == 'yes' ? ' full_width' : '') . '"'
			. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
			. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
			. '>'
				. (trim($link) ? '<a href="'.esc_url($link).'">' : '')
				. '<img src="'.esc_url($src).'" alt="" />'
				. (trim($link) ? '</a>' : '')
				. (trim($title) || trim($icon) ? '<figcaption><span'.($icon ? ' class="'.esc_attr($icon).'"' : '').'></span> ' . ($title) . '</figcaption>' : '')
			. '</figure>');
		return apply_filters('micro_office_shortcode_output', $output, 'trx_image', $atts, $content);
	}
	micro_office_require_shortcode('trx_image', 'micro_office_sc_image');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_image_reg_shortcodes' ) ) {
	
	function micro_office_sc_image_reg_shortcodes() {
	
		micro_office_sc_map("trx_image", array(
			"title" => esc_html__("Image", "micro-office"),
			"desc" => wp_kses_data( __("Insert image into your post (page)", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"url" => array(
					"title" => esc_html__("URL for image file", "micro-office"),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site", "micro-office") ),
					"readonly" => false,
					"value" => "",
					"type" => "media",
					"before" => array(
						'sizes' => true		// If you want allow user select thumb size for image. Otherwise, thumb size is ignored - image fullsize used
					)
				),
				"title" => array(
					"title" => esc_html__("Title", "micro-office"),
					"desc" => wp_kses_data( __("Image title (if need)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"icon" => array(
					"title" => esc_html__("Icon before title",  'micro-office'),
					"desc" => wp_kses_data( __('Select icon for the title from Fontello icons set',  'micro-office') ),
					"value" => "",
					"type" => "icons",
					"options" => micro_office_get_sc_param('icons')
				),
				"align" => array(
					"title" => esc_html__("Float image", "micro-office"),
					"desc" => wp_kses_data( __("Float image to left or right side", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('float')
				), 
				"full_width" => array(
					"title" => esc_html__("Full width", "micro-office"),
					"desc" => wp_kses_data( __("Remove content left and right margins", "micro-office") ),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				), 
				"shape" => array(
					"title" => esc_html__("Image Shape", "micro-office"),
					"desc" => wp_kses_data( __("Shape of the image: square (rectangle) or round", "micro-office") ),
					"value" => "square",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						"square" => esc_html__('Square', 'micro-office'),
						"round" => esc_html__('Round', 'micro-office')
					)
				), 
				"link" => array(
					"title" => esc_html__("Link", "micro-office"),
					"desc" => wp_kses_data( __("The link URL from the image", "micro-office") ),
					"value" => "",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_image_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_image_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_image",
			"name" => esc_html__("Image", "micro-office"),
			"description" => wp_kses_data( __("Insert image", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_image',
			"class" => "trx_sc_single trx_sc_image",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "url",
					"heading" => esc_html__("Select image", "micro-office"),
					"description" => wp_kses_data( __("Select image from library", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Image alignment", "micro-office"),
					"description" => wp_kses_data( __("Align image to left or right side", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "full_width",
					"heading" => esc_html__("Full width", "micro-office"),
					"description" => wp_kses_data( __("Remove content left and right margins", "micro-office") ),
					"value" => array(esc_html__('Full', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "shape",
					"heading" => esc_html__("Image shape", "micro-office"),
					"description" => wp_kses_data( __("Shape of the image: square or round", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Square', 'micro-office') => 'square',
						esc_html__('Round', 'micro-office') => 'round'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Image's title", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Title's icon", "micro-office"),
					"description" => wp_kses_data( __("Select icon for the title from Fontello icons set", "micro-office") ),
					"class" => "",
					"value" => micro_office_get_sc_param('icons'),
					"type" => "dropdown"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Link", "micro-office"),
					"description" => wp_kses_data( __("The link URL from the image", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
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
		
		class WPBakeryShortCode_Trx_Image extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>