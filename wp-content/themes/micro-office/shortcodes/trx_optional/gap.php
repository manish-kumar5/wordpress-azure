<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_gap_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_gap_theme_setup' );
	function micro_office_sc_gap_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_gap_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_gap_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

//[trx_gap]Fullwidth content[/trx_gap]

if (!function_exists('micro_office_sc_gap')) {	
	function micro_office_sc_gap($atts, $content = null) {
		if (micro_office_in_shortcode_blogger()) return '';
		$output = micro_office_gap_start() . do_shortcode($content) . micro_office_gap_end();
		return apply_filters('micro_office_shortcode_output', $output, 'trx_gap', $atts, $content);
	}
	micro_office_require_shortcode("trx_gap", "micro_office_sc_gap");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_gap_reg_shortcodes' ) ) {
	
	function micro_office_sc_gap_reg_shortcodes() {
	
		micro_office_sc_map("trx_gap", array(
			"title" => esc_html__("Gap", "micro-office"),
			"desc" => wp_kses_data( __("Insert gap (fullwidth area) in the post content. Attention! Use the gap only in the posts (pages) without left or right sidebar", "micro-office") ),
			"decorate" => true,
			"container" => true,
			"params" => array(
				"_content_" => array(
					"title" => esc_html__("Gap content", "micro-office"),
					"desc" => wp_kses_data( __("Gap inner content", "micro-office") ),
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_gap_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_gap_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_gap",
			"name" => esc_html__("Gap", "micro-office"),
			"description" => wp_kses_data( __("Insert gap (fullwidth area) in the post content", "micro-office") ),
			"category" => esc_html__('Structure', 'micro-office'),
			'icon' => 'icon_trx_gap',
			"class" => "trx_sc_collection trx_sc_gap",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => false,
			"params" => array(
			)
		) );
		
		class WPBakeryShortCode_Trx_Gap extends MICRO_OFFICE_VC_ShortCodeCollection {}
	}
}
?>