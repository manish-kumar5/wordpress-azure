<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_reviews_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_reviews_theme_setup' );
	function micro_office_sc_reviews_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_reviews_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_reviews_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_reviews')) {	
	function micro_office_sc_reviews($atts, $content = null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"align" => "right",
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
		$output = micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main'))
			? '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_reviews'
							. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
							. ($class ? ' '.esc_attr($class) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
						. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
						. '>'
					. trim(micro_office_get_reviews_placeholder())
					. '</div>'
			: '';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_reviews', $atts, $content);
	}
	micro_office_require_shortcode("trx_reviews", "micro_office_sc_reviews");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_reviews_reg_shortcodes' ) ) {
	
	function micro_office_sc_reviews_reg_shortcodes() {
	
		micro_office_sc_map("trx_reviews", array(
			"title" => esc_html__("Reviews", "micro-office"),
			"desc" => wp_kses_data( __("Insert reviews block in the single post", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"align" => array(
					"title" => esc_html__("Alignment", "micro-office"),
					"desc" => wp_kses_data( __("Align counter to left, center or right", "micro-office") ),
					"divider" => true,
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
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
if ( !function_exists( 'micro_office_sc_reviews_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_reviews_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_reviews",
			"name" => esc_html__("Reviews", "micro-office"),
			"description" => wp_kses_data( __("Insert reviews block in the single post", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_reviews',
			"class" => "trx_sc_single trx_sc_reviews",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Align counter to left, center or right", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Reviews extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>