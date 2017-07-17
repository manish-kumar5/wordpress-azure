<?php
/**
 * Micro Office Framework: messages subsystem
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Theme init
if (!function_exists('micro_office_messages_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_messages_theme_setup' );
	function micro_office_messages_theme_setup() {
		// Core messages strings
		add_filter('micro_office_filter_localize_script', 'micro_office_messages_localize_script');
	}
}


/* Session messages
------------------------------------------------------------------------------------- */

if (!function_exists('micro_office_get_error_msg')) {
	function micro_office_get_error_msg() {
		return micro_office_storage_get('error_msg');
	}
}

if (!function_exists('micro_office_set_error_msg')) {
	function micro_office_set_error_msg($msg) {
		$msg2 = micro_office_get_error_msg();
		micro_office_storage_set('error_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('micro_office_get_success_msg')) {
	function micro_office_get_success_msg() {
		return micro_office_storage_get('success_msg');
	}
}

if (!function_exists('micro_office_set_success_msg')) {
	function micro_office_set_success_msg($msg) {
		$msg2 = micro_office_get_success_msg();
		micro_office_storage_set('success_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}

if (!function_exists('micro_office_get_notice_msg')) {
	function micro_office_get_notice_msg() {
		return micro_office_storage_get('notice_msg');
	}
}

if (!function_exists('micro_office_set_notice_msg')) {
	function micro_office_set_notice_msg($msg) {
		$msg2 = micro_office_get_notice_msg();
		micro_office_storage_set('notice_msg', trim($msg2) . ($msg2=='' ? '' : '<br />') . trim($msg));
	}
}


/* System messages (save when page reload)
------------------------------------------------------------------------------------- */
if (!function_exists('micro_office_set_system_message')) {
	function micro_office_set_system_message($msg, $status='info', $hdr='') {
		update_option(micro_office_storage_get('options_prefix') . '_message', array('message' => $msg, 'status' => $status, 'header' => $hdr));
	}
}

if (!function_exists('micro_office_get_system_message')) {
	function micro_office_get_system_message($del=false) {
		$msg = get_option(micro_office_storage_get('options_prefix') . '_message', false);
		if (!$msg)
			$msg = array('message' => '', 'status' => '', 'header' => '');
		else if ($del)
			micro_office_del_system_message();
		return $msg;
	}
}

if (!function_exists('micro_office_del_system_message')) {
	function micro_office_del_system_message() {
		delete_option(micro_office_storage_get('options_prefix') . '_message');
	}
}


/* Messages strings
------------------------------------------------------------------------------------- */

if (!function_exists('micro_office_messages_localize_script')) {
	
	function micro_office_messages_localize_script($vars) {
		$vars['strings'] = array(
			'ajax_error'		=> esc_html__('Invalid server answer', 'micro-office'),
			'bookmark_add'		=> esc_html__('Add the bookmark', 'micro-office'),
            'bookmark_added'	=> esc_html__('Current page has been successfully added to the bookmarks. You can see it in the right panel on the tab \'Bookmarks\'', 'micro-office'),
            'bookmark_del'		=> esc_html__('Delete this bookmark', 'micro-office'),
            'bookmark_title'	=> esc_html__('Enter bookmark title', 'micro-office'),
            'bookmark_exists'	=> esc_html__('Current page already exists in the bookmarks list', 'micro-office'),
			'search_error'		=> esc_html__('Error occurs in AJAX search! Please, type your query and press search icon for the traditional search way.', 'micro-office'),
			'email_confirm'		=> esc_html__('On the e-mail address "%s" we sent a confirmation email. Please, open it and click on the link.', 'micro-office'),
			'reviews_vote'		=> esc_html__('Thanks for your vote! New average rating is:', 'micro-office'),
			'reviews_error'		=> esc_html__('Error saving your vote! Please, try again later.', 'micro-office'),
			'error_like'		=> esc_html__('Error saving your like! Please, try again later.', 'micro-office'),
			'error_global'		=> esc_html__('Global error text', 'micro-office'),
			'name_empty'		=> esc_html__('The name can\'t be empty', 'micro-office'),
			'name_long'			=> esc_html__('Too long name', 'micro-office'),
			'email_empty'		=> esc_html__('Too short (or empty) email address', 'micro-office'),
			'email_long'		=> esc_html__('Too long email address', 'micro-office'),
			'email_not_valid'	=> esc_html__('Invalid email address', 'micro-office'),
			'subject_empty'		=> esc_html__('The subject can\'t be empty', 'micro-office'),
			'subject_long'		=> esc_html__('Too long subject', 'micro-office'),
			'text_empty'		=> esc_html__('The message text can\'t be empty', 'micro-office'),
			'text_long'			=> esc_html__('Too long message text', 'micro-office'),
			'send_complete'		=> esc_html__("Send message complete!", 'micro-office'),
			'send_error'		=> esc_html__('Transmit failed!', 'micro-office'),
			'not_agree'			=> esc_html__('Please, check \'I agree with Terms and Conditions\'', 'micro-office'),
			'login_empty'		=> esc_html__('The Login field can\'t be empty', 'micro-office'),
			'login_long'		=> esc_html__('Too long login field', 'micro-office'),
			'login_success'		=> esc_html__('Login success! The page will be reloaded in 3 sec.', 'micro-office'),
			'login_failed'		=> esc_html__('Login failed!', 'micro-office'),
			'password_empty'	=> esc_html__('The password can\'t be empty and shorter then 4 characters', 'micro-office'),
			'password_long'		=> esc_html__('Too long password', 'micro-office'),
			'password_not_equal'	=> esc_html__('The passwords in both fields are not equal', 'micro-office'),
			'registration_success'	=> esc_html__('Registration success! Please log in!', 'micro-office'),
			'registration_failed'	=> esc_html__('Registration failed!', 'micro-office'),
			'geocode_error'			=> esc_html__('Geocode was not successful for the following reason:', 'micro-office'),
			'googlemap_not_avail'	=> esc_html__('Google map API not available!', 'micro-office'),
			'editor_save_success'	=> esc_html__("Post content saved!", 'micro-office'),
			'editor_save_error'		=> esc_html__("Error saving post data!", 'micro-office'),
			'editor_delete_post'	=> esc_html__("You really want to delete the current post?", 'micro-office'),
			'editor_delete_post_header'	=> esc_html__("Delete post", 'micro-office'),
			'editor_delete_success'	=> esc_html__("Post deleted!", 'micro-office'),
			'editor_delete_error'	=> esc_html__("Error deleting post!", 'micro-office'),
			'editor_caption_cancel'	=> esc_html__('Cancel', 'micro-office'),
			'editor_caption_close'	=> esc_html__('Close', 'micro-office')
			);
		return $vars;
	}
}
?>