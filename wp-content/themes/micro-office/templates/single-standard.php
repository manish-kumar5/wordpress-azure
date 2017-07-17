<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_single_standard_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_single_standard_theme_setup', 1 );
	function micro_office_template_single_standard_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'single-standard',
			'mode'   => 'single',
			'need_content' => true,
			'need_terms' => true,
			'title'  => esc_html__('Single standard', 'micro-office'),
			'thumb_title'  => esc_html__('Fullwidth image (crop)', 'micro-office'),
			'w'		 => 1170,
			'h'		 => 659
		));
	}
}

// Template output
if ( !function_exists( 'micro_office_template_single_standard_output' ) ) {
	function micro_office_template_single_standard_output($post_options, $post_data) {
		$post_data['post_views']++;
		$avg_author = 0;
		$avg_users  = 0;
		if (!$post_data['post_protected'] && $post_options['reviews'] && micro_office_get_custom_option('show_reviews')=='yes') {
			$avg_author = $post_data['post_reviews_author'];
			$avg_users  = $post_data['post_reviews_users'];
		}
		$show_title = micro_office_get_custom_option('show_post_title')=='yes' && (micro_office_get_custom_option('show_post_title_on_quotes')=='yes' || !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'))) && !micro_office_is_buddypress_page();
		$show_title = micro_office_get_custom_option('show_page_title')=='yes' ? false : $show_title;
		$title_tag = micro_office_get_custom_option('show_page_title')=='yes' ? 'h2' : 'h1';

		micro_office_open_wrapper('<article class="' 
				. join(' ', get_post_class('itemscope'
					. ' post_item post_item_single'
					. ' post_featured_' . esc_attr($post_options['post_class'])
					. ' post_format_' . esc_attr($post_data['post_format'])))
				. '"'
				. ' itemscope itemtype="http://schema.org/'.($avg_author > 0 || $avg_users > 0 ? 'Review' : 'Article')
				. '">');

		if ($show_title && $post_options['location'] == 'center') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php micro_office_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
		<?php 
		}

		if (!$post_data['post_protected'] && (
			!empty($post_options['dedicated']) ||
			(micro_office_get_custom_option('show_featured_image')=='yes' && $post_data['post_thumb'])	// && $post_data['post_format']!='gallery' && $post_data['post_format']!='image')
		)) {
			?>
			<section class="post_featured">
			<?php
			if (!empty($post_options['dedicated'])) {
				micro_office_show_layout($post_options['dedicated']);
			} else {
				micro_office_enqueue_popup();
				?>
				<div class="post_thumb" data-image="<?php echo esc_url($post_data['post_attachment']); ?>" data-title="<?php echo esc_attr($post_data['post_title']); ?>">
					<a class="hover_icon hover_icon_view" href="<?php echo esc_url($post_data['post_attachment']); ?>" title="<?php echo esc_attr($post_data['post_title']); ?>"><?php micro_office_show_layout($post_data['post_thumb']); ?></a>
				</div>
				<?php 
			}
			?>
			</section>
			<?php
		}
			
		
		micro_office_open_wrapper('<section class="post_content'.(!$post_data['post_protected'] && $post_data['post_edit_enable'] ? ' '.esc_attr('post_content_editor_present') : '').'" itemprop="'.($avg_author > 0 || $avg_users > 0 ? 'reviewBody' : 'articleBody').'">');

		if ($show_title && $post_options['location'] != 'center') {
			?>
			<<?php echo esc_html($title_tag); ?> itemprop="<?php echo (float) $avg_author > 0 || (float) $avg_users > 0 ? 'itemReviewed' : 'headline'; ?>" class="post_title entry-title"><span class="post_icon <?php echo esc_attr($post_data['post_icon']); ?>"></span><?php micro_office_show_layout($post_data['post_title']); ?></<?php echo esc_html($title_tag); ?>>
			<?php 
		}

		if (!$post_data['post_protected'] && micro_office_get_custom_option('show_post_info')=='yes' && !micro_office_is_buddypress_page()) {
			$post_options['info_parts'] = array('snippets'=>true);
			micro_office_template_set_args('post-info', array(
				'post_options' => $post_options,
				'post_data' => $post_data
			));
			get_template_part(micro_office_get_file_slug('templates/_parts/post-info.php'));
		}
		
		micro_office_template_set_args('reviews-block', array(
			'post_options' => $post_options,
			'post_data' => $post_data,
			'avg_author' => $avg_author,
			'avg_users' => $avg_users
		));
		get_template_part(micro_office_get_file_slug('templates/_parts/reviews-block.php'));
			
		if ($post_data['post_type'] == 'ajde_events') { 
			$evcal_srow = get_post_meta($post_data['post_id'], 'evcal_srow', true);
			$evcal_erow = get_post_meta($post_data['post_id'], 'evcal_erow', true);
			$start_date= gmdate("d.m.Y", $evcal_srow);
			$end_date= gmdate("d.m.Y", $evcal_erow);
				
			echo '<div class="event_head">'
					.'<div class="event_start"><span class="day">'.(gmdate("d", $evcal_srow)).'</span><span class="month">'.(gmdate("M", $evcal_srow)).'</span></div>'
					.'<div class="event_decr">'
						.'<h4 class="event_title">'.trim($post_data['post_title']).'</h4>'
						.'<div class="event_info">'.trim(gmdate("M d, Y g:i a", $evcal_srow)).' - '.($start_date == $end_date ? trim(gmdate("g:i a", $evcal_erow)) : trim(gmdate("M d, Y g:i a", $evcal_erow))).'</div>'
					.'</div>'
				.'</div>';
		}
		
		// Post content
		if ($post_data['post_protected']) { 
			micro_office_show_layout($post_data['post_excerpt']);
			echo get_the_password_form(); 
		} else {
			if (!micro_office_storage_empty('reviews_markup') && micro_office_strpos($post_data['post_content'], micro_office_get_reviews_placeholder())===false) 
				$post_data['post_content'] = micro_office_sc_reviews(array()) . ($post_data['post_content']);
			micro_office_show_layout(micro_office_gap_wrapper(micro_office_reviews_wrapper($post_data['post_content'])));
			wp_link_pages( array( 
				'before' => '<nav class="pagination_single"><span class="pager_pages">' . esc_html__( 'Pages:', 'micro-office' ) . '</span>', 
				'after' => '</nav>',
				'link_before' => '<span class="pager_numbers">',
				'link_after' => '</span>'
				)
			); 
			if ( micro_office_get_custom_option('show_post_tags') == 'yes' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links)) {
				?>
				<div class="post_info post_info_bottom">
					<span class="post_info_item post_info_tags"><span class="tags_caption"><?php esc_html_e('Tags:', 'micro-office'); ?></span> <?php echo join(', ', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_links); ?></span>
				</div>
				<?php 
			}
		} 

		// Prepare args for all rest template parts
		// This parts not pop args from storage!
		micro_office_template_set_args('single-footer', array(
			'post_options' => $post_options,
			'post_data' => $post_data
		));

		if (!$post_data['post_protected'] && $post_data['post_edit_enable']) {
			get_template_part(micro_office_get_file_slug('templates/_parts/editor-area.php'));
		}
			
		micro_office_close_wrapper();	// .post_content
			
		if (!$post_data['post_protected'] && !micro_office_is_buddypress_page()) {
			get_template_part(micro_office_get_file_slug('templates/_parts/share.php'));
		}

		$sidebar_present = !micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main'));
		if (!$sidebar_present) micro_office_close_wrapper();	// .post_item
		if(!micro_office_is_buddypress_page())
			get_template_part(micro_office_get_file_slug('templates/_parts/related-posts.php'));
		if ($sidebar_present) micro_office_close_wrapper();		// .post_item

		if (!$post_data['post_protected']) get_template_part(micro_office_get_file_slug('templates/_parts/author-info.php'));

		// Show comments
		if ( !$post_data['post_protected'] && (comments_open() || get_comments_number() != 0) ) {
			comments_template();
		}

		// Manually pop args from storage
		// after all single footer templates
		micro_office_template_get_args('single-footer');
	}
}
?>