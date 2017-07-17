<?php
/* Visual Composer support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('micro_office_vc_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_vc_theme_setup', 1 );
	function micro_office_vc_theme_setup() {
		if (micro_office_exists_visual_composer()) {
			if (is_admin()) {
				add_filter( 'micro_office_filter_importer_options',				'micro_office_vc_importer_set_options' );
			}
			add_action('micro_office_action_add_styles',		 				'micro_office_vc_frontend_scripts' );
		}
		if (is_admin()) {
			add_filter( 'micro_office_filter_importer_required_plugins',		'micro_office_vc_importer_required_plugins', 10, 2 );
			add_filter( 'micro_office_filter_required_plugins',					'micro_office_vc_required_plugins' );
		}
	}
}

// Check if Visual Composer installed and activated
if ( !function_exists( 'micro_office_exists_visual_composer' ) ) {
	function micro_office_exists_visual_composer() {
		return class_exists('Vc_Manager');
	}
}

// Check if Visual Composer in frontend editor mode
if ( !function_exists( 'micro_office_vc_is_frontend' ) ) {
	function micro_office_vc_is_frontend() {
		return (isset($_GET['vc_editable']) && $_GET['vc_editable']=='true')
			|| (isset($_GET['vc_action']) && $_GET['vc_action']=='vc_inline');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'micro_office_vc_required_plugins' ) ) {
	
	function micro_office_vc_required_plugins($list=array()) {
		if (in_array('visual_composer', micro_office_storage_get('required_plugins'))) {
			$path = micro_office_get_file_dir('plugins/install/js_composer.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Visual Composer', 'micro-office'),
					'slug' 		=> 'js_composer',
					'source'	=> $path,
					'required' 	=> false
				);
			}
		}
		return $list;
	}
}

// Enqueue VC custom styles
if ( !function_exists( 'micro_office_vc_frontend_scripts' ) ) {
	
	function micro_office_vc_frontend_scripts() {
		if (file_exists(micro_office_get_file_dir('css/plugin.visual-composer.css')))
			wp_enqueue_style( 'micro_office-plugin.visual-composer-style',  micro_office_get_file_url('css/plugin.visual-composer.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check VC in the required plugins
if ( !function_exists( 'micro_office_vc_importer_required_plugins' ) ) {
	
	function micro_office_vc_importer_required_plugins($not_installed='', $list='') {
		if (!micro_office_exists_visual_composer() )		// && micro_office_strpos($list, 'visual_composer')!==false
			$not_installed .= '<br>' . esc_html__('Visual Composer', 'micro-office');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'micro_office_vc_importer_set_options' ) ) {
	
	function micro_office_vc_importer_set_options($options=array()) {
		if ( in_array('visual_composer', micro_office_storage_get('required_plugins')) && micro_office_exists_visual_composer() ) {
			// Add slugs to export options for this plugin
			$options['additional_options'][] = 'wpb_js_templates';
		}
		return $options;
	}
}
?>
