<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_br_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_br_theme_setup' );
	function micro_office_sc_br_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_br_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_br')) {	
	function micro_office_sc_br($atts, $content = null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			"clear" => ""
		), $atts)));
		$output = in_array($clear, array('left', 'right', 'both', 'all')) 
			? '<div class="clearfix" style="clear:' . str_replace('all', 'both', $clear) . '"></div>'
			: '<br />';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_br', $atts, $content);
	}
	micro_office_require_shortcode("trx_br", "micro_office_sc_br");
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_br_reg_shortcodes' ) ) {
	
	function micro_office_sc_br_reg_shortcodes() {
	
		micro_office_sc_map("trx_br", array(
			"title" => esc_html__("Break", "micro-office"),
			"desc" => wp_kses_data( __("Line break with clear floating (if need)", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"clear" => 	array(
					"title" => esc_html__("Clear floating", "micro-office"),
					"desc" => wp_kses_data( __("Clear floating (if need)", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"options" => array(
						'none' => esc_html__('None', 'micro-office'),
						'left' => esc_html__('Left', 'micro-office'),
						'right' => esc_html__('Right', 'micro-office'),
						'both' => esc_html__('Both', 'micro-office')
					)
				)
			)
		));
	}
}
?>