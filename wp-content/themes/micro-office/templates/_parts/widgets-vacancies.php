<?php
// Get template args
extract(micro_office_template_get_args('widgets-vacancies'));

$post_id = get_the_ID();
$post_title = get_the_title();
$post_link = !isset($show_links) || $show_links ? get_permalink($post_id) : '';
$post_meta = get_post_meta($post_id, micro_office_storage_get('options_prefix') . '_post_options', true);
$post_location = (!empty($post_meta['vacancy_location']) ? $post_meta['vacancy_location'] : '');
$post_employment = (!empty($post_meta['vacancy_employment']) ? $post_meta['vacancy_employment'] : '');
$employment = $post_employment == 'freelance' ? 'Freelance' : ($post_employment == 'full' ? 'Full Time' : 'Part Time');

$output = '<article class="post_item  with_thumb' . ($post_number==1 ? ' first' : '') . '">';

$post_thumb = micro_office_get_resized_image_tag($post_id, 75, 75);
if ($post_thumb) {
	$output .= '<div class="post_thumb">' . ($post_thumb) . '</div>';
}


$output .= '<div class="post_content">'
			.'<h6 class="post_title">'
				.($post_link ? '<a href="' . esc_url($post_link) . '">' : '') . ($post_title) . ($post_link ? '</a>' : '')
			.'</h6>'
			.'<div class="post_location">'.($post_location).'</div>'
			.'<div class="post_employment '.($post_employment).'">'.($employment).'</div>'
		.'</div>'
	.'</article>';

// Return result
micro_office_storage_set('widgets_vacancies_output', $output);
?>