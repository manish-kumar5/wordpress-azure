<?php
/**
 * Single post
 */
get_header(); 

$single_style = micro_office_storage_get('single_style');
if (empty($single_style)) $single_style = micro_office_get_custom_option('single_style');

while ( have_posts() ) { the_post();
	micro_office_show_post_layout(
		array(
			'layout' => $single_style,
			'sidebar' => !micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main')),
			'content' => micro_office_get_template_property($single_style, 'need_content'),
			'terms_list' => micro_office_get_template_property($single_style, 'need_terms')
		)
	);
}

get_footer();
?>