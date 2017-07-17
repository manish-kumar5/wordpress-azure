<?php
// Get template args
extract(micro_office_template_get_args('counters'));

$show_all_counters = !empty($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';

// Views
if ($show_all_counters || micro_office_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php micro_office_show_layout($counters_tag); ?> class="post_counters_item post_counters_views icon-eye-1" title="<?php echo esc_attr( sprintf(__('Views - %s', 'micro-office'), $post_data['post_views']) ); ?>"><span class="post_counters_number"><?php micro_office_show_layout($post_data['post_views']); ?></span><?php if (micro_office_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Views', 'micro-office'); ?></<?php micro_office_show_layout($counters_tag); ?>>
	<?php
}

// Likes
if ($show_all_counters || micro_office_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	micro_office_enqueue_messages();
	$likes = isset($_COOKIE['micro_office_likes']) ? $_COOKIE['micro_office_likes'] : '';
	$allow = micro_office_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo !empty($allow) ? 'enabled' : 'disabled'; ?>" title="<?php echo !empty($allow) ? esc_attr__('Like', 'micro-office') : esc_attr__('Dislike', 'micro-office'); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_attr_e('Like', 'micro-office'); ?>"
		data-title-dislike="<?php esc_attr_e('Dislike', 'micro-office'); ?>"><span class="post_counters_number"><?php micro_office_show_layout($post_data['post_likes']); ?></span><?php if (micro_office_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Likes', 'micro-office'); ?></a>
	<?php
}

// Comments
if ($show_all_counters || micro_office_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment-1" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'micro-office'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php micro_office_show_layout($post_data['post_comments']); ?></span><?php if (micro_office_strpos($post_options['counters'], 'captions')!==false) echo ' '.esc_html__('Comments', 'micro-office'); ?></a>
	<?php 
}
 
// Rating
$rating = $post_data['post_reviews_'.(micro_office_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || micro_office_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php micro_office_show_layout($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'micro-office'), $rating) ); ?>"><span class="post_counters_number"><?php micro_office_show_layout($rating); ?></span></<?php micro_office_show_layout($counters_tag); ?>>
	<?php
}


// Edit page link
if (micro_office_strpos($post_options['counters'], 'edit')!==false) {
	edit_post_link( esc_html__( 'Edit', 'micro-office' ), '<span class="post_edit edit-link">', '</span>' );
}

// Markup for search engines
if (is_single() && micro_office_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(micro_office_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(micro_office_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>