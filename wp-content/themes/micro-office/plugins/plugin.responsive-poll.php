<?php
/* Responsive Poll support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('micro_office_responsive_poll_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_responsive_poll_theme_setup', 1 );
	function micro_office_responsive_poll_theme_setup() {
		// Register shortcode in the shortcodes list
		if (micro_office_exists_responsive_poll()) {
			add_action('micro_office_action_add_styles', 					'micro_office_responsive_poll_frontend_scripts');
			add_action('micro_office_action_shortcodes_list',				'micro_office_responsive_poll_reg_shortcodes');
			if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
				add_action('micro_office_action_shortcodes_list_vc',		'micro_office_responsive_poll_reg_shortcodes_vc');
			if (is_admin()) {
				add_filter( 'micro_office_filter_importer_options',			'micro_office_responsive_poll_importer_set_options', 10, 1 );
				add_action( 'micro_office_action_importer_params',			'micro_office_responsive_poll_importer_show_params', 10, 1 );
				add_action( 'micro_office_action_importer_import',			'micro_office_responsive_poll_importer_import', 10, 2 );
				add_action( 'micro_office_action_importer_import_fields',	'micro_office_responsive_poll_importer_import_fields', 10, 1 );
				add_action( 'micro_office_action_importer_export',			'micro_office_responsive_poll_importer_export', 10, 1 );
				add_action( 'micro_office_action_importer_export_fields',	'micro_office_responsive_poll_importer_export_fields', 10, 1 );
			}
		}
		if (is_admin()) {
			add_filter( 'micro_office_filter_importer_required_plugins',	'micro_office_responsive_poll_importer_required_plugins', 10, 2 );
			add_filter( 'micro_office_filter_required_plugins',				'micro_office_responsive_poll_required_plugins' );
		}
	}
}

// Check if plugin installed and activated
if ( !function_exists( 'micro_office_exists_responsive_poll' ) ) {
	function micro_office_exists_responsive_poll() {
		return class_exists('Weblator_Polling');
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'micro_office_responsive_poll_required_plugins' ) ) {
	
	function micro_office_responsive_poll_required_plugins($list=array()) {
		if (in_array('responsive_poll', micro_office_storage_get('required_plugins'))) {
			$path = micro_office_get_file_dir('plugins/install/responsive-poll.zip');
			if (file_exists($path)) {
				$list[] = array(
					'name' 		=> esc_html__('Responsive Poll', 'micro-office'),
					'slug' 		=> 'responsive-poll',
					'source'	=> $path,
					'required' 	=> false
					);
			}
		}
		return $list;
	}
}

// Enqueue custom styles
if ( !function_exists( 'micro_office_responsive_poll_frontend_scripts' ) ) {
	
	function micro_office_responsive_poll_frontend_scripts() {
		if (file_exists(micro_office_get_file_dir('css/plugin.responsive-poll.css')))
			wp_enqueue_style( 'micro_office-plugin.responsive-poll-style',  micro_office_get_file_url('css/plugin.responsive-poll.css'), array(), null );
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check in the required plugins
if ( !function_exists( 'micro_office_responsive_poll_importer_required_plugins' ) ) {
	
	function micro_office_responsive_poll_importer_required_plugins($not_installed='', $list='') {
		if (micro_office_strpos($list, 'responsive_poll')!==false && !micro_office_exists_responsive_poll() )
			$not_installed .= '<br>' . esc_html__('Responsive Poll', 'micro-office');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'micro_office_responsive_poll_importer_set_options' ) ) {
	
	function micro_office_responsive_poll_importer_set_options($options=array()) {
		if ( in_array('responsive_poll', micro_office_storage_get('required_plugins')) && micro_office_exists_responsive_poll() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_responsive_poll'] = str_replace('name.ext', 'responsive_poll.txt', $v['file_with_']);
				}
			}
		}
		return $options;
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'micro_office_responsive_poll_importer_show_params' ) ) {
	
	function micro_office_responsive_poll_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'responsive_poll',
			'title' => esc_html__('Import Responsive Poll', 'micro-office'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'micro_office_responsive_poll_importer_import' ) ) {
	
	function micro_office_responsive_poll_importer_import($importer, $action) {
		if ( $action == 'import_responsive_poll' ) {
			$importer->response['start_from_id'] = 0;
			$importer->import_dump('responsive_poll', esc_html__('Responsive Poll', 'micro-office'));
			global $wpdb;
			$wpdb->update( 
				'wp_weblator_polls', 
				array( 
					'poll_deleted_date' => NULL
				), 
				array( 'poll_deleted_date' => '0000-00-00 00:00:00' ), 
				array( 
					'%s'
				), 
				array( '%s' ) 
			);
			$wpdb->update( 
				'wp_weblator_poll_options', 
				array( 
					'option_deleted_date' => NULL
				), 
				array( 'option_deleted_date' => '0000-00-00 00:00:00' ), 
				array( 
					'%s'
				), 
				array( '%s' ) 
			);
		}
	}
}

// Display import progress
if ( !function_exists( 'micro_office_responsive_poll_importer_import_fields' ) ) {
	
	function micro_office_responsive_poll_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'responsive_poll',
			'title' => esc_html__('Responsive Poll', 'micro-office')
			));
	}
}

// Export posts
if ( !function_exists( 'micro_office_responsive_poll_importer_export' ) ) {
	
	function micro_office_responsive_poll_importer_export($importer) {
		micro_office_fpc(micro_office_get_file_dir('core/core.importer/export/responsive_poll.txt'), serialize( array(
			'weblator_polls'		=> $importer->export_dump('weblator_polls'),
			'weblator_poll_options'	=> $importer->export_dump('weblator_poll_options'),
			'weblator_poll_votes'	=> $importer->export_dump('weblator_poll_votes')
			) )
		);
	}
}

// Display exported data in the fields
if ( !function_exists( 'micro_office_responsive_poll_importer_export_fields' ) ) {
	
	function micro_office_responsive_poll_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'responsive_poll',
			'title' => esc_html__('Responsive Poll', 'micro-office')
			));
	}
}


// Lists
//------------------------------------------------------------------------

// Return Responsive Pollst list, prepended inherit (if need)
if ( !function_exists( 'micro_office_get_list_responsive_polls' ) ) {
	function micro_office_get_list_responsive_polls($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_responsive_polls'))=='') {
			$list = array();
			if (micro_office_exists_responsive_poll()) {
				global $wpdb;
				$rows = $wpdb->get_results( "SELECT id, poll_name FROM " . esc_sql($wpdb->prefix . "weblator_polls") );
				if (is_array($rows) && count($rows) > 0) {
					foreach ($rows as $row) {
						$list[$row->id] = $row->poll_name;
					}
				}
			}
			$list = apply_filters('micro_office_filter_list_responsive_polls', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_responsive_polls', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}



// Shortcodes
//------------------------------------------------------------------------

// Register shortcode in the shortcodes list
if (!function_exists('micro_office_responsive_poll_reg_shortcodes')) {
	
	function micro_office_responsive_poll_reg_shortcodes() {
		if (micro_office_storage_isset('shortcodes')) {

			$polls_list = micro_office_get_list_responsive_polls();

			micro_office_sc_map_before('trx_popup', 'poll', array(
					"title" => esc_html__("Poll", "micro-office"),
					"desc" => esc_html__("Insert poll", "micro-office"),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"id" => array(
							"title" => esc_html__("Poll ID", "micro-office"),
							"desc" => esc_html__("Select Poll to insert into current page", "micro-office"),
							"value" => "",
							"size" => "medium",
							"options" => $polls_list,
							"type" => "select"
							)
						)
					)
			);
		}
	}
}


// Register shortcode in the VC shortcodes list
if (!function_exists('micro_office_responsive_poll_reg_shortcodes_vc')) {
	
	function micro_office_responsive_poll_reg_shortcodes_vc() {

		$polls_list = micro_office_get_list_responsive_polls();

		// Calculated fields form
		vc_map( array(
				"base" => "poll",
				"name" => esc_html__("Poll", "micro-office"),
				"description" => esc_html__("Insert poll", "micro-office"),
				"category" => esc_html__('Content', 'micro-office'),
				'icon' => 'icon_trx_poll',
				"class" => "trx_sc_single trx_sc_poll",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "id",
						"heading" => esc_html__("Poll ID", "micro-office"),
						"description" => esc_html__("Select Poll to insert into current page", "micro-office"),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip($polls_list),
						"type" => "dropdown"
					)
				)
			) );
			
		class WPBakeryShortCode_Poll extends MICRO_OFFICE_VC_ShortCodeSingle {}

	}
}
?>
