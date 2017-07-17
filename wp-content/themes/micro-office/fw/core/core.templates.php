<?php
/**
 * Micro Office Framework: templates and thumbs management
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('micro_office_templates_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_templates_theme_setup' );
	function micro_office_templates_theme_setup() {

		// Add custom thumb sizes into media manager
		add_filter( 'image_size_names_choose', 'micro_office_show_thumb_sizes');
	}
}



/* Templates
-------------------------------------------------------------------------------- */

// Add template (layout name)
if (!function_exists('micro_office_add_template')) {
	function micro_office_add_template($tpl) {
		if (empty($tpl['mode']))						$tpl['mode'] = 'blog';
		if (empty($tpl['template']))					$tpl['template'] = $tpl['layout'];
		if (empty($tpl['need_content']))				$tpl['need_content'] = false;
		if (empty($tpl['need_terms']))					$tpl['need_terms'] = false;
		if (empty($tpl['need_columns']))				$tpl['need_columns'] = false;
		if (empty($tpl['need_isotope']))				$tpl['need_isotope'] = false;
		if (!isset($tpl['h_crop']) && isset($tpl['h']))	$tpl['h_crop'] = $tpl['h'];
		micro_office_storage_set_array('registered_templates', $tpl['layout'], $tpl);
		if (!empty($tpl['thumb_title']) || !empty($tpl['thumb_slug']))
			micro_office_add_thumb_sizes( $tpl );
		else 
			$tpl['thumb_title'] = '';
	}
}

// Return template file name
if (!function_exists('micro_office_get_template_name')) {
	function micro_office_get_template_name($layout_name) {
		$tpl = micro_office_storage_get_array('registered_templates', $layout_name);
		return !empty($tpl['template']) ? $tpl['template'] : $tpl['layout'];
	}
}

// Return true, if template required content
if (!function_exists('micro_office_get_template_property')) {
	function micro_office_get_template_property($layout_name, $what) {
		$tpl = micro_office_storage_get_array('registered_templates', $layout_name);
		return !empty($tpl[$what]) ? $tpl[$what] : '';
	}
}

// Return template output function name
if (!function_exists('micro_office_get_template_function_name')) {
	function micro_office_get_template_function_name($layout_name) {
		$tpl = micro_office_storage_get_array('registered_templates', $layout_name);
		return 'micro_office_template_'.str_replace(array('-', '.'), '_', $tpl['template']).'_output';
	}
}

// Set template arguments
if (!function_exists('micro_office_template_set_args')) {
	function micro_office_template_set_args($tpl, $args) {
		micro_office_storage_push_array('call_args', $tpl, $args);
	}
}


// Get template arguments
if (!function_exists('micro_office_template_get_args')) {
	function micro_office_template_get_args($tpl) {
		return micro_office_storage_pop_array('call_args', $tpl, array());
	}
}


// Look for last template arguments (without removing it from storage)
if (!function_exists('micro_office_template_last_args')) {
	function micro_office_template_last_args($tpl) {
		$args = micro_office_storage_get_array('call_args', $tpl, array());
		return is_array($args) ? array_pop($args) : array();
	}
}


/* Thumbs
-------------------------------------------------------------------------------- */

// Add image dimensions with layout name
if (!function_exists('micro_office_add_thumb_sizes')) {
	function micro_office_add_thumb_sizes($sizes) {
		static $mult = 0;
		if ($mult == 0) $mult = min(4, max(1, micro_office_get_theme_option("retina_ready")));
		if (!isset($sizes['h_crop']))		$sizes['h_crop'] =  isset($sizes['h']) ? $sizes['h'] : null;
		if (empty($sizes['thumb_title']))	$sizes['thumb_title'] = micro_office_strtoproper(!empty($sizes['layout']) ? $sizes['layout'] : $sizes['thumb_slug']);
		$thumb_slug = !empty($sizes['thumb_slug']) ? $sizes['thumb_slug'] : micro_office_get_slug($sizes['thumb_title']);
		if (empty($sizes['layout']))		$sizes['layout'] = $thumb_slug;
		if (micro_office_storage_get_array('thumb_sizes', $thumb_slug)=='') {
			micro_office_storage_set_array('thumb_sizes', $thumb_slug, $sizes);
			// Register WP thumb size
			if (micro_office_get_theme_setting('add_image_size') && (!isset($sizes['add_image_size']) || $sizes['add_image_size'])) {
				// Image thumb and retina version
				add_image_size( 'micro_office-'.$thumb_slug, $sizes['w'], $sizes['h'], $sizes['h']!=null );
				if ($mult > 1)
					add_image_size( 'micro_office-'.$thumb_slug.'-@retina', $sizes['w'] ? $sizes['w']*$mult : $sizes['w'], $sizes['h'] ? $sizes['h']*$mult : $sizes['h'], $sizes['h']!=null );
				// Cropped image thumb and retina version
				if ($sizes['h']!=$sizes['h_crop']) {
					add_image_size( 'micro_office-'.$thumb_slug.'_crop', $sizes['w'], $sizes['h_crop'], true );
					if ($mult > 1) 
						add_image_size( 'micro_office-'.$thumb_slug.'_crop-@retina', $sizes['w'] ? $sizes['w']*$mult : $sizes['w'], $sizes['h_crop'] ? $sizes['h_crop']*$mult : $sizes['h_crop'], true );
				}
			}
		}
	}
}

// Return image dimensions
if (!function_exists('micro_office_get_thumb_sizes')) {
	function micro_office_get_thumb_sizes($opt) {
		$opt = array_merge(array(
			'layout' => 'excerpt',
			'thumb_slug' => ''
		), $opt);
		$tpl = micro_office_storage_get_array('registered_templates', $opt['layout']);
		$thumb_slug = !empty($opt['thumb_slug']) 
						? $opt['thumb_slug'] 
						: (empty($tpl['thumb_slug']) 
							? (empty($tpl['thumb_title']) 
								? '' 
								: micro_office_get_slug($tpl['thumb_title'])
								) 
							: micro_office_get_slug($tpl['thumb_slug'])
							);
		$thumb_size = micro_office_storage_get_array('thumb_sizes', $thumb_slug);
		return !empty($thumb_size) ? $thumb_size : array('w'=>null, 'h'=>null, 'h_crop'=>null);
	}
}

// Show custom thumb sizes into media manager sizes list
if (!function_exists('micro_office_show_thumb_sizes')) {
	function micro_office_show_thumb_sizes( $sizes ) {
		$thumb_sizes = micro_office_storage_get('thumb_sizes');
		if (is_array($thumb_sizes) && count($thumb_sizes) > 0) {
			$rez = array();
			foreach ($thumb_sizes as $k=>$v)
				$rez[$k] = !empty($v['thumb_title']) ? $v['thumb_title'] : $k;
			$sizes = array_merge( $sizes, $rez);
		}
		return $sizes;
	}
}

// AJAX callback: Get attachment url
if ( !function_exists( 'micro_office_callback_get_attachment_url' ) ) {
	function micro_office_callback_get_attachment_url() {
		if ( !wp_verify_nonce( micro_office_get_value_gp('nonce'), admin_url('admin-ajax.php') ) )
			die();
	
		$response = array('error'=>'');
		
		$id = (int) $_REQUEST['attachment_id'];
		
		$response['data'] = wp_get_attachment_url($id);
		
		echo json_encode($response);
		die();
	}
}
?>