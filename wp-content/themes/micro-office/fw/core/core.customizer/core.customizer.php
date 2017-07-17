<?php
/**
 * Theme colors and fonts customization
 */


// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_core_customizer_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_core_customizer_theme_setup', 1 );
	function micro_office_core_customizer_theme_setup() {

		// Load Color schemes then Theme Options are loaded
		add_action('micro_office_action_load_main_options',					'micro_office_core_customizer_load_options');

		// Recompile LESS and save CSS
		add_action('micro_office_action_compile_less',						'micro_office_core_customizer_compile_less');
		add_filter('micro_office_filter_prepare_less',						'micro_office_core_customizer_prepare_less');

		if ( is_admin() ) {
	
			// Ajax Save and Export Action handler
			add_action('wp_ajax_micro_office_options_save', 				'micro_office_core_customizer_save_options');
			add_action('wp_ajax_nopriv_micro_office_options_save',			'micro_office_core_customizer_save_options');
	
			// Ajax Delete color scheme Action handler
			add_action('wp_ajax_micro_office_options_scheme_delete', 		'micro_office_core_customizer_scheme_delete');
			add_action('wp_ajax_nopriv_micro_office_options_scheme_delete',	'micro_office_core_customizer_scheme_delete');

			// Ajax Copy color scheme Action handler
			add_action('wp_ajax_micro_office_options_scheme_copy', 			'micro_office_core_customizer_scheme_copy');
			add_action('wp_ajax_nopriv_micro_office_options_scheme_copy',	'micro_office_core_customizer_scheme_copy');
		}
		
	}
}

if ( !function_exists( 'micro_office_core_customizer_theme_setup2' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_core_customizer_theme_setup2', 11 );
	function micro_office_core_customizer_theme_setup2() {

		if ( is_admin() ) {

			// Add Theme Options in WP menu
			add_action('admin_menu', 								'micro_office_core_customizer_admin_menu_item');
		}
		
	}
}

// Add 'Color Schemes' in the menu 'Theme Options'
if ( !function_exists( 'micro_office_core_customizer_admin_menu_item' ) ) {
	
	function micro_office_core_customizer_admin_menu_item() {
		micro_office_admin_add_menu_item('theme', array(
			'page_title' => esc_html__('Fonts & Colors', 'micro-office'),
			'menu_title' => esc_html__('Fonts & Colors', 'micro-office'),
			'capability' => 'manage_options',
			'menu_slug'  => 'micro_office_options_customizer',
			'callback'   => 'micro_office_core_customizer_page',
			'icon'		 => ''
			)
		);
	}
}


// Step 1: Load Font settings and Color schemes when Theme Options are loaded
if ( !function_exists( 'micro_office_core_customizer_load_options' ) ) {
	
	function micro_office_core_customizer_load_options() {
		$mode = isset($_POST['mode']) ? $_POST['mode'] : '';
		$override = isset($_POST['override']) ? $_POST['override'] : '';
		if ($mode!='reset' || $override!='customizer') {
			$storage = get_option( micro_office_storage_get('options_prefix') . '_options_custom_colors' );
			if (!empty($storage)) {
				$schemes = micro_office_storage_get('custom_colors');
				$scheme_chg = false;
				if (is_array($schemes) && count($schemes) > 0) {
					foreach ($schemes as $k=>$v) {
						if (is_array($v)) {
							foreach ($v as $k1=>$v1) {
								if (isset($storage[$k][$k1])) {
									$scheme_chg = $scheme_chg || $v1!=$storage[$k][$k1];
									$schemes[$k][$k1]=$storage[$k][$k1];
								}
							}
						} else if (isset($storage[$k])) {
							$scheme_chg = $scheme_chg || $v!=$storage[$k];
							$schemes[$k] = $storage[$k];
						}
					}
					if ($scheme_chg) {
						micro_office_storage_set('custom_colors', $schemes);
					}
				}
			}
			$storage = get_option( micro_office_storage_get('options_prefix') . '_options_custom_fonts' );
			if (!empty($storage)) {
				$fonts = micro_office_storage_get('custom_fonts');
				$fonts_chg = false;
				if (is_array($fonts) && count($fonts) > 0) {
					foreach ($fonts as $slug=>$font) {
						if (is_array($font) && count($font) > 0) {
							foreach ($font as $key=>$value) {
								if (isset($storage[$slug][$key])) {
									$fonts_chg = $fonts_chg || $fonts[$slug][$key] != $storage[$slug][$key];
									$fonts[$slug][$key] = $storage[$slug][$key];
								}
							}
						}
					}
					if ($fonts_chg) {
						micro_office_storage_set('custom_fonts', $fonts);
					}
				}
			}
		}
	}
}


// Ajax Save and Export Action handler
if ( !function_exists( 'micro_office_core_customizer_save_options' ) ) {
	
	
	function micro_office_core_customizer_save_options() {

		$mode = $_POST['mode'];
		$override = empty($_POST['override']) ? '' : $_POST['override'];

		if (!in_array($mode, array('save', 'reset')) || !in_array($override, array('customizer')))
			return;

		if ( !wp_verify_nonce( micro_office_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		parse_str($_POST['data'], $data);

		// Refresh array with schemes from POST data
		$colors = micro_office_storage_get('custom_colors');
		if ($mode == 'save') {
			if (is_array($colors) && count($colors) > 0) {
				$order = !empty($data['micro_office_options_schemes_order']) ? explode(',', $data['micro_office_options_schemes_order']) : array_keys($colors);
				$schemes = array();
				foreach ($order as $slug) {
					$new_slug = $data[$slug.'-slug'];
					if (empty($new_slug)) $new_slug = micro_office_get_slug($scheme['title']);
					if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
						$schemes[$new_slug] = array();
						foreach ($colors[$slug] as $key=>$value) {
							$schemes[$new_slug][$key] = isset($data[$slug.'-'.$key]) ? $data[$slug.'-'.$key] : $value;
						}
					}
				}
				$colors = apply_filters('micro_office_filter_save_custom_colors', $schemes);
				micro_office_storage_set('custom_colors', $colors);
				update_option( micro_office_storage_get('options_prefix') . '_options_custom_colors', $colors);
			}
		} else if ($mode == 'reset') {
			delete_option( micro_office_storage_get('options_prefix') . '_options_custom_colors');
		}

		// Refresh array with fonts from POST data
		$fonts = micro_office_storage_get('custom_fonts');
		if ($mode == 'save') {
			if (is_array($fonts) && count($fonts) > 0) {
				foreach ($fonts as $slug=>$font) {
					if (is_array($font) && count($font) > 0) {
						foreach ($font as $key=>$value) {
							if (isset($data[$slug.'-'.$key]))
								$fonts[$slug][$key] = micro_office_is_inherit_option($data[$slug.'-'.$key]) ? '' : $data[$slug.'-'.$key];
						}
					}
				}
				$fonts = apply_filters('micro_office_filter_save_custom_fonts', $fonts);
				micro_office_storage_set('custom_fonts', $fonts);
				update_option( micro_office_storage_get('options_prefix') . '_options_custom_fonts', $fonts);
			}
		} else if ($mode == 'reset') {
			delete_option( micro_office_storage_get('options_prefix') . '_options_custom_fonts');
		}
		
		
		// Save theme.css with new fonts and colors
		if (micro_office_get_theme_setting('less_compiler')=='no') {
			// Save custom css
			micro_office_fpc( micro_office_get_file_dir('css/theme.css'), micro_office_get_custom_css() );
		} else {
			// Recompile theme.less
			do_action('micro_office_action_compile_less');
		}
		
		die();
	}
}


// Ajax Delete color scheme Action handler
if ( !function_exists( 'micro_office_core_customizer_scheme_delete' ) ) {
	
	
	function micro_office_core_customizer_scheme_delete() {

		if ( !wp_verify_nonce( micro_office_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$scheme = $_POST['scheme'];
		$colors = micro_office_storage_get('custom_colors');
		$order = !empty($_POST['order']) ? explode(',', $_POST['order']) : array_keys($colors);
		$response = array( 'error' => '' );

		// Refresh array with schemes from POST data
		if (isset($colors[$scheme])) {
			if (count($colors) > 1) {
				$schemes = array();
				foreach ($order as $slug) {
					if ($slug == $scheme) continue;
					if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
						$schemes[$slug] = $colors[$slug];
					}
				}
				$schemes = apply_filters('micro_office_filter_save_custom_colors', $schemes);
				micro_office_storage_set('custom_colors', $schemes);
				update_option( micro_office_storage_get('options_prefix') . '_options_custom_colors', $schemes);
			} else
				$response['error'] = sprintf(esc_html__('You cannot delete last color scheme!', 'micro-office'), $scheme);
		} else
			$response['error'] = sprintf(esc_html__('Color Scheme %s not found!', 'micro-office'), $scheme);

		// Recompile LESS files with new fonts and colors
		do_action('micro_office_action_compile_less');
		
		echo json_encode($response);
		die();
	}
}


// Ajax Copy color scheme Action handler
if ( !function_exists( 'micro_office_core_customizer_scheme_copy' ) ) {
	
	
	function micro_office_core_customizer_scheme_copy() {

		if ( !wp_verify_nonce( micro_office_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();

		$scheme = $_POST['scheme'];
		$colors = micro_office_storage_get('custom_colors');
		$order = !empty($_POST['order']) ? explode(',', $_POST['order']) : array_keys($colors);
		$response = array( 'error' => '' );

		// Refresh array with schemes from POST data
		if (isset($colors[$scheme])) {
			// Generate slug for the scheme's copy
			$i = 0;
			do {
				$new_slug = $scheme.'_copy'.($i ? $i : '');
				$i++;
			} while (isset($colors[$new_slug]));
			// Copy schemes
			$schemes = array();
			foreach ($order as $slug) {
				if (is_array($colors[$slug]) && count($colors[$slug]) > 0) {
					$schemes[$slug] = $colors[$slug];
					if ($slug == $scheme) {
						$schemes[$new_slug] = $colors[$slug];
						$schemes[$new_slug]['title'] .= ' '.esc_html__('(Copy)', 'micro-office');
					}
				}
			}
			$schemes = apply_filters('micro_office_filter_save_custom_colors', $schemes);
			micro_office_storage_set('custom_colors', $schemes);
			update_option( micro_office_storage_get('options_prefix') . '_options_custom_colors', $schemes);
		} else
			$response['error'] = sprintf(esc_html__('Color Scheme %s not found!', 'micro-office'), $scheme);

		// Recompile LESS files with new fonts and colors
		do_action('micro_office_action_compile_less');
		
		echo json_encode($response);
		die();
	}
}

// Recompile LESS files when color schemes or theme options are saved
if (!function_exists('micro_office_core_customizer_compile_less')) {
	
	function micro_office_core_customizer_compile_less() {
		if (micro_office_get_theme_setting('less_compiler')=='no') return;
		$files = array();
		if (file_exists(micro_office_get_file_dir('css/_utils.less'))) 	$files[] = micro_office_get_file_dir('css/_utils.less');
		$files = apply_filters('micro_office_filter_compile_less', $files);
		if (count($files) > 0) micro_office_compile_less($files);
	}
}






/* Customizer page builder
-------------------------------------------------------------------- */

// Show Customizer page
if ( !function_exists( 'micro_office_core_customizer_page' ) ) {
	function micro_office_core_customizer_page() {

		$options = array();

		$start_partition = true;

		// Default color schemes
		$colors = micro_office_storage_get('custom_colors');
		if (is_array($colors) && count($colors) > 0) {
			
			$demo_block = '';
			if (micro_office_get_theme_setting('customizer_demo') && file_exists(MICRO_OFFICE_FW_PATH . 'core/core.customizer/core.customizer.demo.php')) {
				ob_start();
				require_once MICRO_OFFICE_FW_PATH . 'core/core.customizer/core.customizer.demo.php';
				$demo_block = ob_get_contents();
				ob_end_clean();
			}
			$options["partition_schemes"] = array(
				"title" => esc_html__('Color schemes', 'micro-office'),
				"override" => "customizer",
				"icon" => "iconadmin-palette",
				"type" => "partition");
			if ($start_partition) {
				$options["partition_schemes"]["start"] = "partitions";
				$start_partition = false;
			}

			$start_tab = true;
						
			foreach ($colors as $slug=>$scheme) {

				$options["tab_{$slug}"] = array(
					"title" => $scheme['title'],
					"override" => "customizer",
					"icon" => "iconadmin-palette",
					"type" => "tab");
				if ($start_tab) {
					$options["tab_{$slug}"]["start"] = "tabs";
					$start_tab = false;
				}

				$options["{$slug}-description"] = array(
					"title" => sprintf(esc_html__('Color scheme "%s"', 'micro-office'), $scheme['title']),
					"desc" => wp_kses_data( sprintf(__('Specify the color for each element in the scheme "%s". After that you will be able to use your color scheme for the entire page, any part thereof and/or for the shortcodes!', 'micro-office'), $scheme['title']) ),
					"override" => "customizer",
					"type" => "info");




				// Buttons
				$options["{$slug}-buttons_label"] = array(
					"desc" => wp_kses_data( __("You can duplicate current color scheme (appear on new tab) or delete it (if not last scheme)", 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "4_6 first",
					"type" => "label");
	
				$options["{$slug}-button_copy"] = array(
					"title" => esc_html__('Copy',  'micro-office'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_6",
					"icon" => "iconadmin-docs",
					"action" => "scheme_copy",
					"type" => "button");
	
				$options["{$slug}-button_delete"] = array(
					"title" => esc_html__('Delete',  'micro-office'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_6 last",
					"icon" => "iconadmin-trash",
					"action" => "scheme_delete",
					"type" => "button");





				// Scheme name and slug
				$options["{$slug}-title_label"] = array(
					"title" => esc_html__('Scheme names', 'micro-office'),
					"desc" => wp_kses_data( __('Specify scheme title (to represent this color scheme in the lists) and scheme slug (to use this color scheme in the shortcodes).<br>Attention! If you change scheme title or slug - you must save options (press Save), then reload the page (press F5) after the success saving message appear!', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-title"] = array(
					"title" => esc_html__('Title',  'micro-office'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"std" => "",
					"val" => $scheme['title'],
					"type" => "text");

				$options["{$slug}-slug"] = array(
					"title" => esc_html__('Slug',  'micro-office'),
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"std" => "",
					"val" => $slug,
					"type" => "text");



				// Demo block
				if ($demo_block) {
					$options["{$slug}-demo"] = array(
						"title" => esc_html__('Usage demo', 'micro-office'),
						"desc" => wp_kses_data( __('Below you can see the example of decoration of the page with selected colors.', 'micro-office') )
									. trim($demo_block),
						"override" => "customizer",
						"type" => "info");
				}



if (isset($scheme['bg_color'])) {
				// Page/Block colors
				$options["{$slug}-block_info"] = array(
					"title" => esc_html__('Page/Block decoration', 'micro-office'),
					"desc" => wp_kses_data( __('Specify border and background to decorate whole page (if scheme accepted to the page) or entire block/section.', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");
	
				// Border
				$options["{$slug}-bd_color_label"] = array(
					"title" => esc_html__('Border color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the border color and it hover state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bd_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['bd_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-bd_color_empty"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"type" => "label");
	
				// Background color
				$options["{$slug}-bg_color_label"] = array(
					"title" => esc_html__('Background color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the background color and it hover state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-bg_color_empty"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5",
					"type" => "label");
}


if (isset($scheme['bg_image'])) {
				// Background image 1
				$options["{$slug}-bg_image_label"] = array(
					"title" => esc_html__('Background image', 'micro-office'),
					"desc" => wp_kses_data( __('Select first background image and it display parameters', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_image"] = array(
					"title" => esc_html__('Image', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-bg_image_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-bg_image_position"] = array(
					"title" => esc_html__('Position', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-bg_image_repeat"] = array(
					"title" => esc_html__('Repeat', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-bg_image_attachment"] = array(
					"title" => esc_html__('Attachment', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_attachments(),
					"type" => "select");
	
				// Background image 2
				$options["{$slug}-bg_image2_label"] = array(
					"title" => esc_html__('Background image 2', 'micro-office'),
					"desc" => wp_kses_data( __('Select second background image and it display parameters', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-bg_image2"] = array(
					"title" => esc_html__('Image', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image2'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-bg_image2_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-bg_image2_position"] = array(
					"title" => esc_html__('Position', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image2_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-bg_image2_repeat"] = array(
					"title" => esc_html__('Repeat', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image2_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-bg_image2_attachment"] = array(
					"title" => esc_html__('Attachment', 'micro-office'),
					"std" => "",
					"val" => $scheme['bg_image2_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"options" => micro_office_get_list_bg_image_attachments(),
					"type" => "select");
}


				// Accent colors
if (isset($scheme['accent2'])) {

				$options["{$slug}-accent_info"] = array(
					"title" => esc_html__('Accented colors', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the accented areas in your site.', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");

				// Accent 2 color
				$options["{$slug}-accent2_label"] = array(
					"title" => esc_html__('Accent 2', 'micro-office'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-accent2"] = array(
					"std" => "",
					"val" => $scheme['accent2'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent2_hover"] = array(
					"std" => "",
					"val" => $scheme['accent2_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['accent3'])) {
				// Accent 3 color
				$options["{$slug}-accent3_label"] = array(
					"title" => esc_html__('Accent 3', 'micro-office'),
					"desc" => wp_kses_data( __('Select color for accented elements and their hover state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-accent3"] = array(
					"std" => "",
					"val" => $scheme['accent3'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-accent3_hover"] = array(
					"std" => "",
					"val" => $scheme['accent3_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"style" => "tiny",
					"type" => "color");
}


if (isset($scheme['text'])) {
				// Text colors
				$options["{$slug}-text_info"] = array(
					"title" => esc_html__('Text and Headers', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the plain text, post info blocks and headers', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");
	
				// Text - simple text, links in the text and their hover state
				$options["{$slug}-text_label"] = array(
					"title" => esc_html__('Text', 'micro-office'),
					"desc" => wp_kses_data( __('Select colors for the text: normal text color, light text (for example - post info) and dark text (headers, bold text, etc.)', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-text"] = array(
					"title" => esc_html__('Text', 'micro-office'),
					"std" => "",
					"val" => $scheme['text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_light"] = array(
					"title" => esc_html__('Light', 'micro-office'),
					"std" => "",
					"val" => $scheme['text_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_dark"] = array(
					"title" => esc_html__('Dark', 'micro-office'),
					"std" => "",
					"val" => $scheme['text_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['text_link'])) {

				// Text links
				$options["{$slug}-text_link_label"] = array(
					"title" => esc_html__('Text links', 'micro-office'),
					"desc" => wp_kses_data( __('Select color for the links and their hover state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");


				$options["{$slug}-text_link"] = array(
					"title" => esc_html__('Link', 'micro-office'),
					"std" => "",
					"val" => $scheme['text_link'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-text_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['text_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['inverse_text'])) {
				// Inverse blocks
				$options["{$slug}-inverse_info"] = array(
					"title" => esc_html__('Inverse blocks', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the headers, plain text, links and post info blocks in the accented areas (with background color equal to text link)', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");

				// Inverse text
				$options["{$slug}-inverse_label"] = array(
					"title" => esc_html__('Inverse text', 'micro-office'),
					"desc" => wp_kses_data( __('Select colors for inversed text (text on accented background)', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-inverse_text"] = array(
					"title" => esc_html__('Text', 'micro-office'),
					"std" => "",
					"val" => $scheme['inverse_text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_light"] = array(
					"title" => esc_html__('Light', 'micro-office'),
					"std" => "",
					"val" => $scheme['inverse_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_dark"] = array(
					"title" => esc_html__('Dark', 'micro-office'),
					"std" => "",
					"val" => $scheme['inverse_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-inverse_label2"] = array(
					"title" => esc_html__('Inverse links', 'micro-office'),
					"desc" => wp_kses_data( __('Select colors for inversed links (links on accented background)', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-inverse_link"] = array(
					"title" => esc_html__('Link', 'micro-office'),
					"std" => "",
					"val" => $scheme['inverse_link'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-inverse_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['inverse_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5 last",
					"style" => "tiny",
					"type" => "color");
}


if (isset($scheme['input_text'])) {
				// Form field's colors
				$options["{$slug}-input_info"] = array(
					"title" => esc_html__('Input colors: form fields and textareas', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors to decorate input fields in the forms', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");
	
				// Text in the inputs
				$options["{$slug}-input_text_label"] = array(
					"title" => esc_html__('Text', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the input fields for all states: disabled, inactive, active', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-input_text"] = array(
					"title" => esc_html__('Inactive', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-input_light"] = array(
					"title" => esc_html__('Disabled', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-input_dark"] = array(
					"title" => esc_html__('Active', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
				
				// Border
				$options["{$slug}-input_bd_color_label"] = array(
					"title" => esc_html__('Border color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the border colors for the normal state and for active (focused) field', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-input_bd_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_bd_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-input_bd_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_bd_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				// Background Color
				$options["{$slug}-input_bg_color_label"] = array(
					"title" => esc_html__('Background Color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the background colors for the normal state and for active (focused) field', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-input_bg_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_bg_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-input_bg_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['input_bg_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
}

if (isset($scheme['alter_text'])) {
				// Alternative colors (highlight blocks, form fields, etc.)
				$options["{$slug}-alter_info"] = array(
					"title" => esc_html__('Alternative colors: Highlighted areas, submenu items, etc.', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors to decorate highlighted blocks in the text, submenu items, etc.', 'micro-office') ),
					"override" => "customizer",
					"type" => "info");
	
				// Text in the highlight block
				$options["{$slug}-alter_text_label"] = array(
					"title" => esc_html__('Text and Headers', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the plain text, post info blocks and headers in the highlight blocks', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_text"] = array(
					"title" => esc_html__('Text', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_text'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_light"] = array(
					"title" => esc_html__('Light', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_light'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_dark"] = array(
					"title" => esc_html__('Dark', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_dark'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				// Links in the highlight block
				$options["{$slug}-alter_link_label"] = array(
					"title" => esc_html__('Links', 'micro-office'),
					"desc" => wp_kses_data( __('Specify colors for the links in the highlight blocks', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_link"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_link'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
				$options["{$slug}-alter_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
				
				// Border
				$options["{$slug}-alter_bd_color_label"] = array(
					"title" => esc_html__('Border color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the border colors for the normal and hovered state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bd_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bd_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-alter_bd_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bd_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				// Background Color
				$options["{$slug}-alter_bg_color_label"] = array(
					"title" => esc_html__('Background Color', 'micro-office'),
					"desc" => wp_kses_data( __('Select the background colors for the normal and hovered state', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bg_color"] = array(
					"title" => esc_html__('Color', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_color'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");

				$options["{$slug}-alter_bg_hover"] = array(
					"title" => esc_html__('Hover', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_hover'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"style" => "tiny",
					"type" => "color");
	
if (isset($scheme['alter_bg_image'])) {
				// Background image
				$options["{$slug}-alter_bg_image_label"] = array(
					"title" => esc_html__('Background image', 'micro-office'),
					"desc" => wp_kses_data( __('Select alter background image and it display parameters', 'micro-office') ),
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");
	
				$options["{$slug}-alter_bg_image"] = array(
					"title" => esc_html__('Image', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_image'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "3_5",
					"type" => "media");

				$options["{$slug}-alter_bg_image_label2"] = array(
					"override" => "customizer",
					"divider" => false,
					"columns" => "2_5 first",
					"type" => "label");

				$options["{$slug}-alter_bg_image_position"] = array(
					"title" => esc_html__('Position', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_image_position'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_positions(),
					"type" => "select");
		
				$options["{$slug}-alter_bg_image_repeat"] = array(
					"title" => esc_html__('Repeat', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_image_repeat'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_repeats(),
					"type" => "select");

				$options["{$slug}-alter_bg_image_attachment"] = array(
					"title" => esc_html__('Attachment', 'micro-office'),
					"std" => "",
					"val" => $scheme['alter_bg_image_attachment'],
					"override" => "customizer",
					"divider" => false,
					"columns" => "1_5",
					"options" => micro_office_get_list_bg_image_attachments(),
					"type" => "select");
}
}
			}
		}


		// Default fonts settings
		$fonts = micro_office_storage_get('custom_fonts');
		if (is_array($fonts) && count($fonts) > 0) {

			$options["partition_fonts"] = array(
				"title" => esc_html__('Fonts', 'micro-office'),
				"override" => "customizer",
				"icon" => "iconadmin-font",
				"type" => "partition");
			if ($start_partition) {
				$options["partition_fonts"]["start"] = "partitions";
				$start_partition = false;
			}

			$options["info_fonts_1"] = array(
				"title" => esc_html__('Typography settings', 'micro-office'),
				"desc" => wp_kses_data( __('Select fonts, sizes and styles for the headings and paragraphs. You can use Google fonts and custom fonts.<br><br>How to install custom @font-face fonts into the theme?<br>All @font-face fonts are located in "theme_name/css/font-face/" folder in the separate subfolders for the each font. Subfolder name is a font-family name!<br>Place full set of the font files (for each font style and weight) and css-file named stylesheet.css in the each subfolder.<br>Create your @font-face kit by using Fontsquirrel @font-face Generator (or any other) and then extract the font kit (with folder in the kit) into the "theme_name/css/font-face" folder to install.', 'micro-office') ),
				"type" => "info");

			$show_titles = true;
			
			$list_fonts = micro_office_get_list_fonts(true);
			$list_styles = micro_office_get_list_fonts_styles(false);
			$list_weight = array(
				'inherit' => esc_html__("Inherit", 'micro-office'), 
				'100' => esc_html__('100 (Light)', 'micro-office'), 
				'300' => esc_html__('300 (Thin)',  'micro-office'),
				'400' => esc_html__('400 (Normal)', 'micro-office'),
				'500' => esc_html__('500 (Semibold)', 'micro-office'),
				'600' => esc_html__('600 (Semibold)', 'micro-office'),
				'700' => esc_html__('700 (Bold)', 'micro-office'),
				'900' => esc_html__('900 (Black)', 'micro-office')
			);

			foreach ($fonts as $slug=>$font) {
				if (isset($font['font-family'])) {
					$options["{$slug}-font-family"] = array(
						"title" => isset($font['title']) ? $font['title'] : micro_office_strtoproper($slug),
						"desc" => isset($font['description']) ? $font['description'] : '',
						"divider" => false,
						"columns" => "2_8 first",
						"std" => "",
						"val" => $font['font-family'] ? $font['font-family'] : 'inherit',
						"options" => $list_fonts,
						"type" => "fonts");
				}
				if (isset($font['font-size'])) {
					$options["{$slug}-font-size"] = array(
						"title" => $show_titles ? esc_html__('Size', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => micro_office_is_inherit_option($font['font-size']) ? '' : $font['font-size'],
						"type" => "text");
				}
				if (isset($font['line-height'])) {
					$options["{$slug}-line-height"] = array(
						"title" => $show_titles ? esc_html__('Line height', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => micro_office_is_inherit_option($font['line-height']) ? '' : $font['line-height'],
						"type" => "text");
				} else {
					$options["{$slug}-line-height"] = array(
						"title" => $show_titles ? esc_html__('Line height', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}
				if (isset($font['font-weight'])) {
					$options["{$slug}-font-weight"] = array(
						"title" => $show_titles ? esc_html__('Weight', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => $font['font-weight'] ? $font['font-weight'] : 'inherit',
						"options" => $list_weight,
						"type" => "select");
				}
				if (isset($font['font-style'])) {
					$options["{$slug}-font-style"] = array(
						"title" => $show_titles ? esc_html__('Style', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => $font['font-style'] ? $font['font-style'] : 'inherit',
						"multiple" => true,
						"options" => $list_styles,
						"type" => "checklist");
				}
				if (isset($font['margin-top'])) {
					$options["{$slug}-margin-top"] = array(
						"title" => $show_titles ? esc_html__('Margin Top', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => micro_office_is_inherit_option($font['margin-top']) ? '' : $font['margin-top'],
						"type" => "text");
				} else {
					$options["{$slug}-margin-top"] = array(
						"title" => $show_titles ? esc_html__('Margin Top', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}
				if (isset($font['margin-bottom'])) {
					$options["{$slug}-margin-bottom"] = array(
						"title" => $show_titles ? esc_html__('Margin Bottom', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"std" => "",
						"val" => micro_office_is_inherit_option($font['margin-bottom']) ? '' : $font['margin-bottom'],
						"type" => "text");
				} else {
					$options["{$slug}-margin-bottom"] = array(
						"title" => $show_titles ? esc_html__('Margin Bottom', 'micro-office') : '',
						"desc" => '',
						"divider" => false,
						"columns" => "1_8",
						"type" => "label");
				}

				$show_titles = false;
			}
		}

		// Load required styles and scripts for this page
		micro_office_core_customizer_load_scripts();
		// Prepare javascripts global variables
		micro_office_core_customizer_prepare_scripts();
		
		// Build Options page
		micro_office_options_page_start(array(
			'title' => esc_html__('Fonts & Colors', 'micro-office'),
			"icon" => "iconadmin-cog",
			"subtitle" => esc_html__('Fonts settings & Color schemes', 'micro-office'),
			"description" => wp_kses_data( __('Customize fonts and colors for your site.', 'micro-office') ),
			'data' => $options,
			'create_form' => true,
			'buttons' => array('save', 'reset'),
			'override' => 'customizer'
		));

		if (is_array($options) && count($options) > 0) {
			foreach ($options as $id=>$option) { 
				micro_office_options_show_field($id, $option);
			}
		}
	
		micro_office_options_page_stop();
	}
}



// Prepare LESS variables before LESS files compilation
// Duplicate rules set for each color scheme
if (!function_exists('micro_office_core_customizer_prepare_less')) {
	
	function micro_office_core_customizer_prepare_less() {

		// Prefix for override rules
		$prefix = micro_office_get_theme_setting('less_prefix');
		// Use nested selectors: increase .css size, but allow use nested color schemes
		$nested = micro_office_get_theme_setting('less_nested');

		$out = '';

		// Custom fonts
		$fonts_list = micro_office_get_list_fonts(false);
		$custom_fonts = micro_office_get_custom_fonts();
		if (is_array($custom_fonts) && count($custom_fonts) > 0) {
		foreach ($custom_fonts as $slug => $font) {
			
			// Prepare variables with separate font rules
			if (!empty($font['font-family']) && !micro_office_is_inherit_option($font['font-family']))
				$out .= "@{$slug}_ff: \"" . str_replace(' ('.esc_html__('uploaded font', 'micro-office').')', '', $font['font-family']) . '"' 
							. (isset($fonts_list[$font['font-family']]['family']) 
									? ',' . $fonts_list[$font['font-family']]['family'] 
									: '' 
								) 
							. ";\n";
			else
				$out .= "@{$slug}_ff: inherit;\n";

			if (!empty($font['font-size']) && !micro_office_is_inherit_option($font['font-size']))
				$out .= "@{$slug}_fs: " . micro_office_prepare_css_value($font['font-size']) . ";\n";
			else
				$out .= "@{$slug}_fs: inherit;\n";
			
			if (!empty($font['line-height']) && !micro_office_is_inherit_option($font['line-height']))
				$out .= "@{$slug}_lh: " . micro_office_prepare_css_value($font['line-height']) . ";\n";
			else
				$out .= "@{$slug}_lh: inherit;\n";

			if (!empty($font['font-weight']) && !micro_office_is_inherit_option($font['font-weight']))
				$out .= "@{$slug}_fw: " . trim($font['font-weight']) . ";\n";
			else
				$out .= "@{$slug}_fw: inherit;\n";

			if (!empty($font['font-style']) && !micro_office_is_inherit_option($font['font-style']) && micro_office_strpos($font['font-style'], 'i')!==false)
				$out .= "@{$slug}_fl: italic;\n";
			else
				$out .= "@{$slug}_fl: inherit;\n";

			if (!empty($font['font-style']) && !micro_office_is_inherit_option($font['font-style']) && micro_office_strpos($font['font-style'], 'u')!==false)
				$out .= "@{$slug}_td: underline;\n";
			else
				$out .= "@{$slug}_td: inherit;\n";

			if (!empty($font['margin-top']) && !micro_office_is_inherit_option($font['margin-top']))
				$out .= "@{$slug}_mt: " . micro_office_prepare_css_value($font['margin-top']) . ";\n";
			else
				$out .= "@{$slug}_mt: inherit;\n";

			if (!empty($font['margin-bottom']) && !micro_office_is_inherit_option($font['margin-bottom']))
				$out .= "@{$slug}_mb: " . micro_office_prepare_css_value($font['margin-bottom']) . ";\n";
			else
				$out .= "@{$slug}_mb: inherit;\n";

			$out .= "\n";


			// Prepare less-function with summary font settings
			$out .= ".{$slug}_font() {\n";
			if (!empty($font['font-family']) && !micro_office_is_inherit_option($font['font-family']))
				$out .= "\tfont-family:\"" . esc_attr($font['font-family']) . '"' . (isset($fonts_list[$font['font-family']]['family']) ? ',' . $fonts_list[$font['font-family']]['family'] : '' ) . ";\n";
			if (!empty($font['font-size']) && !micro_office_is_inherit_option($font['font-size']))
				$out .= "\tfont-size:" . micro_office_prepare_css_value($font['font-size']) . ";\n";
			if (!empty($font['line-height']) && !micro_office_is_inherit_option($font['line-height']))
				$out .= "\tline-height: " . micro_office_prepare_css_value($font['line-height']) . ";\n";
			if (!empty($font['font-weight']) && !micro_office_is_inherit_option($font['font-weight']))
				$out .= "\tfont-weight: " . trim($font['font-weight']) . ";\n";
			if (!empty($font['font-style']) && !micro_office_is_inherit_option($font['font-style']) && micro_office_strpos($font['font-style'], 'i')!==false)
				$out .= "\tfont-style: italic;\n";
			if (!empty($font['font-style']) && !micro_office_is_inherit_option($font['font-style']) && micro_office_strpos($font['font-style'], 'u')!==false)
				$out .= "\ttext-decoration: underline;\n";
			$out .= "}\n\n";

			$out .= ".{$slug}_margins() {\n";
			if (!empty($font['margin-top']) && !micro_office_is_inherit_option($font['margin-top']))
				$out .= "\tmargin-top: " . micro_office_prepare_css_value($font['margin-top']) . ";\n";
			if (!empty($font['margin-bottom']) && !micro_office_is_inherit_option($font['margin-bottom']))
				$out .= "\tmargin-bottom: " . micro_office_prepare_css_value($font['margin-bottom']) . ";\n";
			$out .= "}\n\n";
		}
		}

		$out .= "\n";


	
		// Prepare variables with separate colors
		$custom_colors = micro_office_get_custom_colors();
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $key => $value) {
						if ($key == 'title' || micro_office_strpos($key, 'bg_image')!==false) continue;
						$out .= "@{$scheme}_{$key}: " . esc_attr(
							!empty($value) 
								? $value
								: (micro_office_strpos($key, 'bg_image')!==false
									? 'none'
									: 'inherit'
									)
							) . ";\n";
					}
				}
			}
		}
			
		$out .= "\n";
			

		// Prepare less-function with summary color settings

		// .scheme_color(text_hover)
		$out .= ".scheme_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_color(text_hover, @alpha)
		$out .= ".scheme_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color(text_hover)
		$out .= ".scheme_bg_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color(text_hover, @alpha)
		$out .= ".scheme_bg_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color_self(text_hover)
		$out .= ".scheme_bg_color_self(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . "&.scheme_{$scheme}" . ($nested ? ", [class*=\"scheme_\"] &.scheme_{$scheme}" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_color_self(text_hover, @alpha)
		$out .= ".scheme_bg_color_self(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . "&.scheme_{$scheme}" . ($nested ? ", [class*=\"scheme_\"] &.scheme_{$scheme}" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg(text_hover)
		$out .= ".scheme_bg(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "background: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg(text_hover, @alpha)
		$out .= ".scheme_bg(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "background: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bg_image()
		$out .= ".scheme_bg_image() {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (!empty($data['bg_image']) || !empty($data['bg_image2'])) {
					$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n";
					$comma = '';
					if (!empty($data['bg_image2'])) {
						$out .= "background: url(".esc_url($data['bg_image2']).') '.esc_attr($data['bg_image2_repeat']).' '.esc_attr($data['bg_image2_position']).' '.esc_attr($data['bg_image2_attachment']);
						$comma = ',';
					}
					if (!empty($data['bg_image'])) {
						$out .= ($comma ? $comma : "background:") . "url(".esc_url($data['bg_image']).') '.esc_attr($data['bg_image_repeat']).' '.esc_attr($data['bg_image_position']).' '.esc_attr($data['bg_image_attachment']);
					}
					$out .= ";\n";
					$out .= "}\n";
				}
			}
		}
		$out .= "}\n";

		// .scheme_alter_bg_image()
		$out .= ".scheme_alter_bg_image() {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				if (!empty($data['alter_bg_image'])) {
					$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n";
					$out .= "background: url(".esc_url($data['alter_bg_image']).') '.esc_attr($data['alter_bg_image_repeat']).' '.esc_attr($data['alter_bg_image_position']).' '.esc_attr($data['alter_bg_image_attachment']);
					$out .= "}\n";
				}
			}
		}
		$out .= "}\n";

		// .scheme_bd_color(text_hover)
		$out .= ".scheme_bd_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_bd_color(text_hover, @alpha)
		$out .= ".scheme_bd_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "border-color: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdt_color(text_hover)
		$out .= ".scheme_bdt_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-top-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdb_color(text_hover)
		$out .= ".scheme_bdb_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-bottom-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdl_color(text_hover)
		$out .= ".scheme_bdl_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-left-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";
			
		// .scheme_bdr_color(text_hover)
		$out .= ".scheme_bdr_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "border-right-color: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_stroke_color(text_hover)
		$out .= ".scheme_stroke_color(@color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "stroke: @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_stroke_color(text_hover, @alpha)
		$out .= ".scheme_stroke_color(@color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@r: red(@@color_var);\n"
					. "@g: green(@@color_var);\n"
					. "@b: blue(@@color_var);\n"
					. "stroke: rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_gradient_top(text_link, 0%, text_hover, 70%);
		$out .= ".scheme_gradient_top(@color_name, @color_percent, @color2_name, @color2_percent) {\n";	// when (@color_percent <= @color2_percent) {\n
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@color2_var: '{$scheme}_@{color2_name}';\n"
					. "background: -webkit-gradient(linear, left top, left bottom, color-stop(@color_percent, @@color_var), color-stop(@color2_percent, @@color2_var));\n"
					. "background: -webkit-linear-gradient(top, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:    -moz-linear-gradient(top, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:     -ms-linear-gradient(top, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:      -o-linear-gradient(top, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:         linear-gradient(to bottom, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_gradient_left(text_link, 0%, text_hover, 70%);
		$out .= ".scheme_gradient_left(@color_name, @color_percent, @color2_name, @color2_percent) {\n";	// when (@color_percent <= @color2_percent) {\n
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@color2_var: '{$scheme}_@{color2_name}';\n"
					. "background: -webkit-gradient(linear, left top, right top, color-stop(@color_percent, @@color_var), color-stop(@color2_percent, @@color2_var));\n"
					. "background: -webkit-linear-gradient(left, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:    -moz-linear-gradient(left, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:     -ms-linear-gradient(left, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:      -o-linear-gradient(left, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "background:         linear-gradient(to right, @@color_var @color_percent, @@color2_var @color2_percent);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_box_shadow(x, y, spread, size, color)
		$out .= ".scheme_box_shadow(@x, @y, @spread, @size, @color_name) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "-webkit-box-shadow: @x @y @spread @size @@color_var;\n"
					. "   -moz-box-shadow: @x @y @spread @size @@color_var;\n"
					. "    -ms-box-shadow: @x @y @spread @size @@color_var;\n"
					. "        box-shadow: @x @y @spread @size @@color_var;\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		// .scheme_box_shadow(x, y, spread, size, color, alpha)
		$out .= ".scheme_box_shadow(@x, @y, @spread, @size, @color_name, @alpha) {\n";
		if (is_array($custom_colors) && count($custom_colors) > 0) {
			foreach ($custom_colors as $scheme => $data) {
				$out .= $prefix . ".scheme_{$scheme} &" . ($nested ? ", [class*=\"scheme_\"] .scheme_{$scheme} &" : '') . " {\n"
					. "@color_var: '{$scheme}_@{color_name}';\n"
					. "@c: @@color_var;\n"
					. "@r: red(@c);\n"
					. "@g: green(@c);\n"
					. "@b: blue(@c);\n"
					. "-webkit-box-shadow: @x @y @spread @size rgba(@r, @g, @b, @alpha);\n"
					. "   -moz-box-shadow: @x @y @spread @size rgba(@r, @g, @b, @alpha);\n"
					. "    -ms-box-shadow: @x @y @spread @size rgba(@r, @g, @b, @alpha);\n"
					. "        box-shadow: @x @y @spread @size rgba(@r, @g, @b, @alpha);\n"
					. "}\n";
			}
		}
		$out .= "}\n";

		return $out;
	}
}




/* Customizer scripts
-------------------------------------------------------------------- */

// Add customizer scripts
if (!function_exists('micro_office_core_customizer_load_scripts')) {
	function micro_office_core_customizer_load_scripts() {
		if (file_exists(micro_office_get_file_dir('core/core.customizer/core.customizer.css')))
			wp_enqueue_style( 'micro_office-core-customizer-style',	micro_office_get_file_url('core/core.customizer/core.customizer.css'), array(), null);
		if (file_exists(micro_office_get_file_dir('core/core.customizer/core.customizer.js')))
			wp_enqueue_script( 'micro_office-core-customizer-script', micro_office_get_file_url('core/core.customizer/core.customizer.js'), array(), null );
	}
}


// Prepare javascripts global variables for customizer admin page
if ( !function_exists( 'micro_office_core_customizer_prepare_scripts' ) ) {
	function micro_office_core_customizer_prepare_scripts() {
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_delete', 			esc_html__("Delete color scheme", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_delete_confirm',	esc_html__("Do you really want to delete this color scheme?", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_delete_complete',	esc_html__("Current color scheme is successfully deleted!", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_delete_failed',	esc_html__("Error while delete color scheme! Try again later.", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_copy', 			esc_html__("Copy color scheme", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_copy_confirm', 	esc_html__("Duplicate this color scheme?", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_copy_complete', 	esc_html__("Current color scheme is successfully duplicated!", 'micro-office'));
		micro_office_storage_set_array2('js_vars', 'to_strings', 'scheme_copy_failed', 		esc_html__("Error while duplicate color scheme! Try again later.", 'micro-office'));
	}
}




/* Typography utilities
-------------------------------------------------------------------- */

// Return fonts parameters for customization
if ( !function_exists( 'micro_office_get_custom_fonts' ) ) {
	function micro_office_get_custom_fonts() {
		return apply_filters('micro_office_filter_get_custom_fonts', !micro_office_storage_empty('custom_fonts') ? micro_office_storage_get('custom_fonts') : array());
	}
}

// Add custom font parameters
if (!function_exists('micro_office_add_custom_font')) {
	function micro_office_add_custom_font($key, $data) {
		if (micro_office_storage_empty('custom_fonts', $key)) micro_office_storage_set_array('custom_fonts', $key, $data);
	}
}

// Return one or all font settings
if (!function_exists('micro_office_get_custom_font_settings')) {
	function micro_office_get_custom_font_settings($key, $param_name='') {
		return micro_office_storage_get_array('custom_fonts', $key, $param_name);
	}
}

// Return fonts for css generator
if ( !function_exists( 'micro_office_get_custom_fonts_properties' ) ) {
	function micro_office_get_custom_fonts_properties() {
		$fnt = micro_office_get_custom_fonts();
		$rez = array();
		foreach ($fnt as $k=>$f) {
			foreach ($f as $prop=>$val) {
				if ($prop == 'font-style') {
					if (micro_office_strpos($val, 'i')!==false)
						$rez[$k.'_fl'] = 'italic';
					if (micro_office_strpos($val, 'u')!==false)
						$rez[$k.'_td'] = 'underline';
				} else {
					$p = str_replace(
						array(
							'font-family',
							'font-size',
							'font-weight',
							'line-height',
							'margin-top',
							'margin-bottom'
						),
						array(
							'ff', 'fs', 'fw', 'lh', 'mt', 'mb'
						),
						$prop);
					$rez[$k.'_'.$p] = $val ? $val : 'inherit';
				}
			}
		}
		return $rez;
	}
}

// Return fonts for css generator
if ( !function_exists( 'micro_office_get_custom_font_css' ) ) {
	function micro_office_get_custom_font_css($fnt) {
		$css = '';
		$fnt = micro_office_storage_get_array('custom_fonts', $fnt);
		if (is_array($fnt)) {
			foreach ($fnt as $prop=>$val) {
				if (empty($val) || (micro_office_strpos($prop, 'font-')===false && micro_office_strpos($prop, 'line-')===false)) continue;
				if ($prop=='font-style') {
					if (micro_office_strpos($val, 'i')!==false)
						$css .= ($css ? ';' : '') . $prop . ':italic';
					if (micro_office_strpos($val, 'u')!==false)
						$css .= ($css ? ';' : '') . 'text_decoration:underline';
				} else
					$css .= ($css ? ';' : '') . $prop . ':' . $val;
			}
		}
		return $css;
	}
}

// Return fonts for css generator
if ( !function_exists( 'micro_office_get_custom_margins_css' ) ) {
	function micro_office_get_custom_margins_css($fnt) {
		$css = '';
		$fnt = micro_office_storage_get_array('custom_fonts', $fnt);
		if (is_array($fnt)) {
			foreach ($fnt as $prop=>$val) {
				if (empty($val) || micro_office_strpos($prop, 'margin-')===false) continue;
				$css .= ($css ? ';' : '') . $prop . ':' . $val;
			}
		}
		return $css;
	}
}






/* Color Scheme utilities
-------------------------------------------------------------------- */

// Add color scheme
if (!function_exists('micro_office_add_color_scheme')) {
	function micro_office_add_color_scheme($key, $data) {
		if (micro_office_storage_empty('custom_colors', $key)) micro_office_storage_set_array('custom_colors', $key, $data);
	}
}

// Return color schemes
if ( !function_exists( 'micro_office_get_custom_colors' ) ) {
	function micro_office_get_custom_colors() {
		return apply_filters('micro_office_filter_get_custom_colors', !micro_office_storage_empty('custom_colors') ? micro_office_storage_get('custom_colors') : array());
	}
}

// Return color schemes list, prepended inherit
if ( !function_exists( 'micro_office_get_list_color_schemes' ) ) {
	function micro_office_get_list_color_schemes($prepend_inherit=false) {
		$list = array();
		$colors = micro_office_storage_get('custom_colors');
		if (!empty($colors) && is_array($colors)) {
			foreach ($colors as $k=>$v) {
				$list[$k] = $v['title'];
			}
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return scheme color
if (!function_exists('micro_office_get_scheme_color')) {
	function micro_office_get_scheme_color($clr_name='', $clr='') {
		if (empty($clr)) {
			$scheme = micro_office_get_custom_option('body_scheme');
			if (empty($scheme) || micro_office_storage_empty('custom_colors', $scheme)) $scheme = 'original';
			$clr = micro_office_storage_get_array('custom_colors', $scheme, $clr_name);
		}
		return apply_filters('micro_office_filter_get_scheme_color', $clr, $clr_name, $scheme);
	}
}

// Return scheme colors
if (!function_exists('micro_office_get_scheme_colors')) {
	function micro_office_get_scheme_colors($scheme='') {
		if (empty($scheme)) $scheme = micro_office_get_custom_option('body_scheme');
		if (empty($scheme) || micro_office_storage_empty('custom_colors', $scheme)) $scheme = 'original';
		return micro_office_storage_get_array('custom_colors', $scheme);
	}
}
?>