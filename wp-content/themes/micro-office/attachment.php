<?php
/**
 * Attachment page
 */
get_header(); 

while ( have_posts() ) { the_post();

	// Move micro_office_set_post_views to the javascript - counter will work under cache system
	if (micro_office_get_custom_option('use_ajax_views_counter')=='no') {
		micro_office_set_post_views(get_the_ID());
	}

	micro_office_show_post_layout(
		array(
			'layout' => 'attachment',
			'sidebar' => !micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main'))
		)
	);

}

get_footer();
?>