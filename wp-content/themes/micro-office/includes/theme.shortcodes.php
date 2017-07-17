<?php
if (!function_exists('micro_office_theme_shortcodes_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_theme_shortcodes_setup', 1 );
	function micro_office_theme_shortcodes_setup() {
		add_filter('micro_office_filter_googlemap_styles', 'micro_office_theme_shortcodes_googlemap_styles');
	}
}


// Add theme-specific Google map styles
if ( !function_exists( 'micro_office_theme_shortcodes_googlemap_styles' ) ) {
	function micro_office_theme_shortcodes_googlemap_styles($list) {
		$list['simple']		= esc_html__('Simple', 'micro-office');
		$list['greyscale']	= esc_html__('Greyscale', 'micro-office');
		$list['inverse']	= esc_html__('Inverse', 'micro-office');
		$list['apple']		= esc_html__('Apple', 'micro-office');
		return $list;
	}
}
?>