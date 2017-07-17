<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_tooltip_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_tooltip_theme_setup' );
	function micro_office_sc_tooltip_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_tooltip_reg_shortcodes');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_tooltip')) {	
	function micro_office_sc_tooltip($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		$output = '<span' . ($id ? ' id="'.esc_attr($id).'"' : '') 
					. ' class="sc_tooltip_parent'. (!empty($class) ? ' '.esc_attr($class) : '').'"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
						. do_shortcode($content)
						. '<span class="sc_tooltip">' . ($title) . '</span>'
					. '</span>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_tooltip', $atts, $content);
	}
	micro_office_require_shortcode('trx_tooltip', 'micro_office_sc_tooltip');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_tooltip_reg_shortcodes' ) ) {
	
	function micro_office_sc_tooltip_reg_shortcodes() {
	
		micro_office_sc_map("trx_tooltip", array(
			"title" => esc_html__("Tooltip", "micro-office"),
			"desc" => wp_kses_data( __("Create tooltip for selected text", "micro-office") ),
			"decorate" => false,
			"container" => true,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", "micro-office"),
					"desc" => wp_kses_data( __("Tooltip title (required)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"_content_" => array(
					"title" => esc_html__("Tipped content", "micro-office"),
					"desc" => wp_kses_data( __("Highlighted content with tooltip", "micro-office") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
				),
				"id" => micro_office_get_sc_param('id'),
				"class" => micro_office_get_sc_param('class'),
				"css" => micro_office_get_sc_param('css')
			)
		));
	}
}
?>