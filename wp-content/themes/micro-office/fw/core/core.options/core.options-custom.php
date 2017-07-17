<?php
/**
 * Micro Office Framework: Theme options custom fields
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_options_custom_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_options_custom_theme_setup' );
	function micro_office_options_custom_theme_setup() {

		if ( is_admin() ) {
			add_action("admin_enqueue_scripts",	'micro_office_options_custom_load_scripts');
		}
		
	}
}

// Load required styles and scripts for custom options fields
if ( !function_exists( 'micro_office_options_custom_load_scripts' ) ) {
	//add_action("admin_enqueue_scripts", 'micro_office_options_custom_load_scripts');
	function micro_office_options_custom_load_scripts() {
		wp_enqueue_script( 'micro_office-options-custom-script',	micro_office_get_file_url('core/core.options/js/core.options-custom.js'), array(), null, true );	
	}
}


// Show theme specific fields in Post (and Page) options
if ( !function_exists( 'micro_office_show_custom_field' ) ) {
	function micro_office_show_custom_field($id, $field, $value) {
		$output = '';
		switch ($field['type']) {
			case 'reviews':
				$output .= '<div class="reviews_block">' . trim(micro_office_reviews_get_markup($field, $value, true)) . '</div>';
				break;
	
			case 'mediamanager':
				wp_enqueue_media( );
				$output .= '<a id="'.esc_attr($id).'" class="button mediamanager micro_office_media_selector"
					data-param="' . esc_attr($id) . '"
					data-choose="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'micro-office') : esc_html__( 'Choose Image', 'micro-office')).'"
					data-update="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Add to Gallery', 'micro-office') : esc_html__( 'Choose Image', 'micro-office')).'"
					data-multiple="'.esc_attr(isset($field['multiple']) && $field['multiple'] ? 'true' : 'false').'"
					data-linked-field="'.esc_attr($field['media_field_id']).'"
					>' . (isset($field['multiple']) && $field['multiple'] ? esc_html__( 'Choose Images', 'micro-office') : esc_html__( 'Choose Image', 'micro-office')) . '</a>';
				break;
		}
		return apply_filters('micro_office_filter_show_custom_field', $output, $id, $field, $value);
	}
}
?>