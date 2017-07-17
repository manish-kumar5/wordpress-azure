<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_hide_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_hide_theme_setup' );
	function micro_office_sc_hide_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_hide_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_hide')) {	
	function micro_office_sc_hide($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"selector" => "",
			"hide" => "on",
			"delay" => 0
		), $atts)));
		$selector = trim(chop($selector));
		if (!empty($selector)) {
			micro_office_storage_concat('js_code', '
				'.($delay>0 ? 'setTimeout(function() {' : '').'
					jQuery("'.esc_attr($selector).'").' . ($hide=='on' ? 'hide' : 'show') . '();
				'.($delay>0 ? '},'.($delay).');' : '').'
			');
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_hide', $atts, $content);
	}
	micro_office_require_shortcode('trx_hide', 'micro_office_sc_hide');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_hide_reg_shortcodes' ) ) {
	
	function micro_office_sc_hide_reg_shortcodes() {
	
		micro_office_sc_map("trx_hide", array(
			"title" => esc_html__("Hide/Show any block", "micro-office"),
			"desc" => wp_kses_data( __("Hide or Show any block with desired CSS-selector", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"selector" => array(
					"title" => esc_html__("Selector", "micro-office"),
					"desc" => wp_kses_data( __("Any block's CSS-selector", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"hide" => array(
					"title" => esc_html__("Hide or Show", "micro-office"),
					"desc" => wp_kses_data( __("New state for the block: hide or show", "micro-office") ),
					"value" => "yes",
					"size" => "small",
					"options" => micro_office_get_sc_param('yes_no'),
					"type" => "switch"
				)
			)
		));
	}
}
?>