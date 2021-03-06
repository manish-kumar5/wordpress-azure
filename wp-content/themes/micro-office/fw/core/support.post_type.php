<?php
/**
 * Micro Office Framework: Supported post types settings
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Theme init
if (!function_exists('micro_office_post_type_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_post_type_theme_setup', 9 );
	function micro_office_post_type_theme_setup() {
		if ( !micro_office_options_is_used() ) return;
		$post_type = micro_office_admin_get_current_post_type();
		if (empty($post_type)) $post_type = 'post';
		$override_key = micro_office_get_override_key($post_type, 'post_type');
		if ($override_key) {
			// Set post type action
			add_action('save_post',				'micro_office_post_type_save_options');
			add_action('add_meta_boxes',		'micro_office_post_type_add_meta_box');
			add_action('admin_enqueue_scripts', 'micro_office_post_type_admin_scripts');
			// Create meta box
			micro_office_storage_set('post_meta_box', array(
				'id' => 'post-meta-box',
				'title' => esc_html__('Post Options', 'micro-office'),
				'page' => $post_type,
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array()
				)
			);
		}
	}
}


// Admin scripts
if (!function_exists('micro_office_post_type_admin_scripts')) {
	
	function micro_office_post_type_admin_scripts() {
	}
}


// Add meta box
if (!function_exists('micro_office_post_type_add_meta_box')) {
	
	function micro_office_post_type_add_meta_box() {
		$mb = micro_office_storage_get('post_meta_box');
		add_meta_box($mb['id'], $mb['title'], 'micro_office_post_type_show_meta_box', $mb['page'], $mb['context'], $mb['priority']);
	}
}

// Callback function to show fields in meta box
if (!function_exists('micro_office_post_type_show_meta_box')) {
	function micro_office_post_type_show_meta_box() {
		global $post;
		
		$post_type = micro_office_admin_get_current_post_type();
		$override_key = micro_office_get_override_key($post_type, 'post_type');
		
		// Use nonce for verification
		echo '<input type="hidden" name="meta_box_post_nonce" value="' .esc_attr(wp_create_nonce(admin_url())).'" />';
		echo '<input type="hidden" name="meta_box_post_type" value="'.esc_attr($post_type).'" />';
	
		$custom_options = apply_filters('micro_office_filter_post_load_custom_options', get_post_meta($post->ID, micro_office_storage_get('options_prefix') . '_post_options', true), $post_type, $post->ID);

		$mb = micro_office_storage_get('post_meta_box');
		$post_options = micro_office_array_merge(micro_office_storage_get('options'), $mb['fields']);

		do_action('micro_office_action_post_before_show_meta_box', $post_type, $post->ID);
	
		micro_office_options_page_start(array(
			'data' => $post_options,
			'add_inherit' => true,
			'create_form' => false,
			'buttons' => array('import', 'export'),
			'override' => $override_key
		));

		if (is_array($post_options) && count($post_options) > 0) {
			foreach ($post_options as $id=>$option) { 
				if (!isset($option['override']) || !in_array($override_key, explode(',', $option['override']))) continue;

				$option = apply_filters('micro_office_filter_post_show_custom_field_option', $option, $id, $post_type, $post->ID);
				$meta = isset($custom_options[$id]) 
								? apply_filters('micro_office_filter_post_show_custom_field_value', $custom_options[$id], $option, $id, $post_type, $post->ID) 
								: (isset($option['inherit']) && !$option['inherit'] ? $option['std'] : '');

				do_action('micro_office_action_post_before_show_custom_field', $post_type, $post->ID, $option, $id, $meta);

				micro_office_options_show_field($id, $option, $meta);

				do_action('micro_office_action_post_after_show_custom_field', $post_type, $post->ID, $option, $id, $meta);
			}
		}
	
		micro_office_options_page_stop();
		
		do_action('micro_office_action_post_after_show_meta_box', $post_type, $post->ID);
		
	}
}


// Save data from meta box
if (!function_exists('micro_office_post_type_save_options')) {
	
	function micro_office_post_type_save_options($post_id) {

		// verify nonce
		if ( !wp_verify_nonce( micro_office_get_value_gp('meta_box_post_nonce'), admin_url() ) )
			return $post_id;

		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		$post_type = isset($_POST['meta_box_post_type']) ? $_POST['meta_box_post_type'] : $_POST['post_type'];
		$override_key = micro_office_get_override_key($post_type, 'post_type');

		// check permissions
		$capability = 'page';
		$post_types = get_post_types( array( 'name' => $post_type), 'objects' );
		if (!empty($post_types) && is_array($post_types)) {
			foreach ($post_types  as $type) {
				$capability = $type->capability_type;
				break;
			}
		}
		if (!current_user_can('edit_'.($capability), $post_id)) {
			return $post_id;
		}

		$custom_options = array();

		$post_options = array_merge(micro_office_storage_get('options'), micro_office_storage_get_array('post_meta_box', 'fields'));

		if (micro_office_options_merge_new_values($post_options, $custom_options, $_POST, 'save', $override_key)) {
			update_post_meta($post_id, micro_office_storage_get('options_prefix') . '_post_options', apply_filters('micro_office_filter_post_save_custom_options', $custom_options, $post_type, $post_id));
		}

		// Init post counters
		global $post;
		if ( !empty($post->ID) && $post_id==$post->ID ) {
			micro_office_get_post_views($post_id);
			micro_office_get_post_likes($post_id);
		}
	}
}
?>