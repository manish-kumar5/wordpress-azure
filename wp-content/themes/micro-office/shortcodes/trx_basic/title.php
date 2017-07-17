<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_title_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_title_theme_setup' );
	function micro_office_sc_title_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_title_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_title_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_title')) {	
	function micro_office_sc_title($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"type" => "1",
			"style" => "regular",
			"align" => "",
			"font_weight" => "",
			"font_size" => "",
			"color" => "",
			"icon" => "",
			"image" => "",
			"picture" => "",
			"image_size" => "small",
			"position" => "left",
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
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width)
			.($align && $align!='none' && !micro_office_param_is_inherit($align) ? 'text-align:' . esc_attr($align) .';' : '')
			.($color ? 'color:' . esc_attr($color) .';' : '')
			.($font_weight && !micro_office_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) .';' : '')
			.($font_size   ? 'font-size:' . esc_attr($font_size) .';' : '')
			;
		$type = min(6, max(1, $type));
		if ($picture > 0) {
			$attach = wp_get_attachment_image_src( $picture, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$picture = $attach[0];
		}
		$pic = $style!='iconed' 
			? '' 
			: '<span class="sc_title_icon sc_title_icon_'.esc_attr($position).'  sc_title_icon_'.esc_attr($image_size).($icon!='' && $icon!='none' ? ' '.esc_attr($icon) : '').'"'.'>'
				.($picture ? '<img src="'.esc_url($picture).'" alt="" />' : '')
				.(empty($picture) && $image && $image!='none' ? '<img src="'.esc_url(micro_office_strpos($image, 'http')===0 ? $image : micro_office_get_file_url('images/icons/'.($image).'.png')).'" alt="" />' : '')
				.'</span>';
		$output = '<h' . esc_attr($type) . ($id ? ' id="'.esc_attr($id).'"' : '')
				. ' class="sc_title sc_title_'.esc_attr($style)
					.($align && $align!='none' && !micro_office_param_is_inherit($align) ? ' sc_align_' . esc_attr($align) : '')
					.(!empty($class) ? ' '.esc_attr($class) : '')
					.'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. '>'
					. ($pic)
					. ($style=='divider' ? '<span class="sc_title_divider_before"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
					. do_shortcode($content) 
					. ($style=='divider' ? '<span class="sc_title_divider_after"'.($color ? ' style="background-color: '.esc_attr($color).'"' : '').'></span>' : '')
				. '</h' . esc_attr($type) . '>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_title', $atts, $content);
	}
	micro_office_require_shortcode('trx_title', 'micro_office_sc_title');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_title_reg_shortcodes' ) ) {
	
	function micro_office_sc_title_reg_shortcodes() {
	
		micro_office_sc_map("trx_title", array(
			"title" => esc_html__("Title", "micro-office"),
			"desc" => wp_kses_data( __("Create header tag (1-6 level) with many styles", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Title content", "micro-office"),
					"desc" => wp_kses_data( __("Title content", "micro-office") ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"type" => array(
					"title" => esc_html__("Title type", "micro-office"),
					"desc" => wp_kses_data( __("Title type (header level)", "micro-office") ),
					"divider" => true,
					"value" => "1",
					"type" => "select",
					"options" => array(
						'1' => esc_html__('Header 1', 'micro-office'),
						'2' => esc_html__('Header 2', 'micro-office'),
						'3' => esc_html__('Header 3', 'micro-office'),
						'4' => esc_html__('Header 4', 'micro-office'),
						'5' => esc_html__('Header 5', 'micro-office'),
						'6' => esc_html__('Header 6', 'micro-office'),
					)
				),
				"style" => array(
					"title" => esc_html__("Title style", "micro-office"),
					"desc" => wp_kses_data( __("Title style", "micro-office") ),
					"value" => "regular",
					"type" => "select",
					"options" => array(
						'regular' => esc_html__('Regular', 'micro-office'),
						'underline' => esc_html__('Underline', 'micro-office'),
						'divider' => esc_html__('Divider', 'micro-office'),
						'iconed' => esc_html__('With icon (image)', 'micro-office')
					)
				),
				"align" => array(
					"title" => esc_html__("Alignment", "micro-office"),
					"desc" => wp_kses_data( __("Title text alignment", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
				), 
				"font_size" => array(
					"title" => esc_html__("Font_size", "micro-office"),
					"desc" => wp_kses_data( __("Custom font size. If empty - use theme default", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "micro-office"),
					"desc" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", "micro-office") ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'inherit' => esc_html__('Default', 'micro-office'),
						'100' => esc_html__('Thin (100)', 'micro-office'),
						'300' => esc_html__('Light (300)', 'micro-office'),
						'400' => esc_html__('Normal (400)', 'micro-office'),
						'600' => esc_html__('Semibold (600)', 'micro-office'),
						'700' => esc_html__('Bold (700)', 'micro-office'),
						'900' => esc_html__('Black (900)', 'micro-office')
					)
				),
				"color" => array(
					"title" => esc_html__("Title color", "micro-office"),
					"desc" => wp_kses_data( __("Select color for the title", "micro-office") ),
					"value" => "",
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
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_title_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_title_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_title",
			"name" => esc_html__("Title", "micro-office"),
			"description" => wp_kses_data( __("Create header tag (1-6 level) with many styles", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_title',
			"class" => "trx_sc_single trx_sc_title",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "content",
					"heading" => esc_html__("Title content", "micro-office"),
					"description" => wp_kses_data( __("Title content", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textarea_html"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Title type", "micro-office"),
					"description" => wp_kses_data( __("Title type (header level)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Header 1', 'micro-office') => '1',
						esc_html__('Header 2', 'micro-office') => '2',
						esc_html__('Header 3', 'micro-office') => '3',
						esc_html__('Header 4', 'micro-office') => '4',
						esc_html__('Header 5', 'micro-office') => '5',
						esc_html__('Header 6', 'micro-office') => '6'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Title style", "micro-office"),
					"description" => wp_kses_data( __("Title style: only text (regular) or with icon/image (iconed)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Regular', 'micro-office') => 'regular',
						esc_html__('Underline', 'micro-office') => 'underline',
						esc_html__('Divider', 'micro-office') => 'divider',
						esc_html__('With icon (image)', 'micro-office') => 'iconed'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Title text alignment", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "micro-office"),
					"description" => wp_kses_data( __("Custom font size. If empty - use theme default", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "micro-office"),
					"description" => wp_kses_data( __("Custom font weight. If empty or inherit - use theme default", "micro-office") ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'micro-office') => 'inherit',
						esc_html__('Thin (100)', 'micro-office') => '100',
						esc_html__('Light (300)', 'micro-office') => '300',
						esc_html__('Normal (400)', 'micro-office') => '400',
						esc_html__('Semibold (600)', 'micro-office') => '600',
						esc_html__('Bold (700)', 'micro-office') => '700',
						esc_html__('Black (900)', 'micro-office') => '900'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Title color", "micro-office"),
					"description" => wp_kses_data( __("Select color for the title", "micro-office") ),
					"class" => "",
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
			'js_view' => 'VcTrxTextView'
		) );
		
		class WPBakeryShortCode_Trx_Title extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>