<?php 
if (is_singular()) {
	if (micro_office_get_theme_option('use_ajax_views_counter')=='yes') {
		micro_office_storage_set_array('js_vars', 'ajax_views_counter', array(
			'post_id' => get_the_ID(),
			'post_views' => micro_office_get_post_views(get_the_ID())
		));
	} else
		micro_office_set_post_views(get_the_ID());
}
?>