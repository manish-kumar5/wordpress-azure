<?php
/* WP-Pro-Quiz support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('micro_office_wp_pro_quiz_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_wp_pro_quiz_theme_setup', 1 );
	function micro_office_wp_pro_quiz_theme_setup() {
		// Register shortcode in the shortcodes list
		if (micro_office_exists_wp_pro_quiz()) {
			if (is_admin()) {
				add_filter( 'micro_office_filter_importer_options',			'micro_office_wp_pro_quiz_importer_set_options', 10, 1 );
				add_action( 'micro_office_action_importer_params',			'micro_office_wp_pro_quiz_importer_show_params', 10, 1 );
				add_action( 'micro_office_action_importer_import',			'micro_office_wp_pro_quiz_importer_import', 10, 2 );
				add_action( 'micro_office_action_importer_import_fields',	'micro_office_wp_pro_quiz_importer_import_fields', 10, 1 );
				add_action( 'micro_office_action_importer_export',			'micro_office_wp_pro_quiz_importer_export', 10, 1 );
				add_action( 'micro_office_action_importer_export_fields',	'micro_office_wp_pro_quiz_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'micro_office_filter_importer_required_plugins',	'micro_office_wp_pro_quiz_importer_required_plugins', 10, 2 );
			add_filter( 'micro_office_filter_required_plugins',				'micro_office_wp_pro_quiz_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'micro_office_exists_wp_pro_quiz' ) ) {
	function micro_office_exists_wp_pro_quiz() {
		return class_exists('WpProQuiz_Controller_Admin');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'micro_office_wp_pro_quiz_required_plugins' ) ) {
	
	function micro_office_wp_pro_quiz_required_plugins($list=array()) {
		if (in_array('wp-pro-quiz', micro_office_storage_get('required_plugins'))) {
			$path = micro_office_get_file_dir('plugins/install/wp-pro-quiz.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('WP-Pro-Quiz', 'micro-office'),
					'slug' 		=> 'wp-pro-quiz',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_required_plugins' ) ) {
	
	function micro_office_wp_pro_quiz_importer_required_plugins($not_installed='', $list='') {
		if (micro_office_strpos($list, 'wp_pro_quiz')!==false && !micro_office_exists_wp_pro_quiz() )
			$not_installed .= '<br>' . esc_html__('WP-Pro-Quiz', 'micro-office');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_set_options' ) ) {
	
	function micro_office_wp_pro_quiz_importer_set_options($options=array()) {
		if ( in_array('wp-pro-quiz', micro_office_storage_get('required_plugins')) && micro_office_exists_wp_pro_quiz() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_wp_pro_quiz'] = str_replace('name.ext', 'wp_pro_quiz.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_show_params' ) ) {
	
	function micro_office_wp_pro_quiz_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'wp_pro_quiz',
			'title' => esc_html__('Import WP-Pro-Quiz', 'micro-office'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_import' ) ) {
	
	function micro_office_wp_pro_quiz_importer_import($importer, $action) {
		if ( $action == 'import_wp_pro_quiz' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('wp_pro_quiz', esc_html__('WP-Pro-Quiz', 'micro-office'));
		}
	}
}

// Display import progress
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_import_fields' ) ) {
	
	function micro_office_wp_pro_quiz_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'wp_pro_quiz',
			'title' => esc_html__('WP-Pro-Quiz', 'micro-office')
			));
	}
}

// Export posts
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_export' ) ) {
	
	function micro_office_wp_pro_quiz_importer_export($importer) {
		micro_office_fpc(micro_office_get_file_dir('core/core.importer/export/wp_pro_quiz.txt'), serialize( array(
			'wp_pro_quiz_master' => $importer->export_dump('wp_pro_quiz_master'),
			'wp_pro_quiz_question' => $importer->export_dump('wp_pro_quiz_question')
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'micro_office_wp_pro_quiz_importer_export_fields' ) ) {
	
	function micro_office_wp_pro_quiz_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'wp_pro_quiz',
			'title' => esc_html__('WP-Pro-Quiz', 'micro-office')
			));
	}
}

?>
