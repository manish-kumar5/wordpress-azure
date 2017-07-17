<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_courses_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_courses_theme_setup', 1 );
	function micro_office_template_courses_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'courses_2',
			'template' => 'courses',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Courses tile (different height) /2 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => null
		));
		micro_office_add_template(array(
			'layout' => 'courses_3',
			'template' => 'courses',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Courses tile /3 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => null
		));
		micro_office_add_template(array(
			'layout' => 'courses_4',
			'template' => 'courses',
			'mode'   => 'blog',
			'need_isotope' => true,
			'need_terms' => true,
			'title'  => esc_html__('Courses tile /4 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => null
		));
		// Add template specific scripts
		add_action('micro_office_action_blog_scripts', 'micro_office_template_courses_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('micro_office_template_courses_add_scripts')) {
	
	function micro_office_template_courses_add_scripts($style) {
		if (in_array(micro_office_substr($style, 0, 8), array('classic_', 'courses_'))) {
			wp_enqueue_script( 'isotope', micro_office_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'micro_office_template_courses_output' ) ) {
	function micro_office_template_courses_output($post_options, $post_data) {
		$show_title = !in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote'));
		$parts = explode('_', $post_options['layout']);
		$style = $parts[0];
		$columns = max(1, min(12, empty($post_options['columns_count']) 
									? (empty($parts[1]) ? 1 : (int) $parts[1])
									: $post_options['columns_count']
									));
		$tag = micro_office_in_shortcode_blogger(true) ? 'div' : 'article';
		?>
		<div class="isotope_item isotope_item_<?php echo esc_attr($style); ?> isotope_item_<?php echo esc_attr($post_options['layout']); ?> isotope_column_<?php echo esc_attr($columns); ?>
					<?php
					if ($post_options['filters'] != '') {
						if ($post_options['filters']=='categories' && !empty($post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy']]->terms_ids);
						else if ($post_options['filters']=='tags' && !empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids))
							echo ' flt_' . join(' flt_', $post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms_ids);
					}
					?>">
			<<?php micro_office_show_layout($tag); ?> class="post_item post_item_<?php echo esc_attr($style); ?> post_item_<?php echo esc_attr($post_options['layout']); ?>
				 <?php echo ' post_format_'.esc_attr($post_data['post_format']) 
					. ($post_options['number']%2==0 ? ' even' : ' odd') 
					. ($post_options['number']==0 ? ' first' : '') 
					. ($post_options['number']==$post_options['posts_on_page'] ? ' last' : '');
				?>">
				
				<div class="post_featured img">
					<?php 
					micro_office_show_layout($post_data['post_thumb']);
					?>
				</div>

				<div class="post_content isotope_item_content">
					
					<?php
					if ($show_title) {
						if (!isset($post_options['links']) || $post_options['links']) {
							?>
							<h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo ($post_data['post_title']); ?></a></h3>
							<?php
						} else {
							?>
							<h3 class="post_title"><?php echo ($post_data['post_title']); ?></h3>
							<?php
						}
					}?>
					<div class="post_categories">
						<?php
							$post_type = get_post_type();
							$post_tax = micro_office_get_taxonomy_categories_by_post_type($post_type);
							$post_terms = micro_office_get_terms_by_post_id(array('post_id'=>$post_data['post_id'], 'taxonomy'=>$post_tax));
							$category_color =  micro_office_get_custom_option('cat_color', '', $post_data['post_id'], $post_data['post_type']);
							if (!empty($post_terms[$post_tax])) {
								if (!empty($post_terms[$post_tax]->closest_parent)) {
									$post_category = $post_terms[$post_tax]->closest_parent->name;
									$post_category_link = $post_terms[$post_tax]->closest_parent->link;
									echo '<a href="'.esc_url($post_category_link).'" style="background-color: '.esc_attr($category_color).';">'.trim($post_category).'</a>';
								}
							}
							?>
					</div>

					<div class="post_footer">
						<?php
							$avg_author = 0;
							if(micro_office_get_theme_option('reviews_first')=='author')
								$avg_author = micro_office_reviews_marks_to_display(get_post_meta($post_data['post_id'], micro_office_storage_get('options_prefix').'_reviews_avg', true));
							else
								$avg_author  = micro_office_reviews_marks_to_display(get_post_meta($post_data['post_id'], micro_office_storage_get('options_prefix').'_reviews_avg2', true));
			
							if ($avg_author > 0)
								echo '<div class="post_review icon-star">'.($avg_author).'</div>';
						
							$price = $price_period = $product_link = '';
							if ($post_data['post_type']=='courses') {
								$price = micro_office_get_custom_option('price', '', $post_data['post_id'], $post_data['post_type']);					// !!!!!! Get option from specified post
								if ( empty($price) || micro_office_is_inherit_option($price) ) $price = __('Free!', 'micro-office');
								$price_period = micro_office_get_custom_option('price_period', '', $post_data['post_id'], $post_data['post_type']);		// !!!!!! Get option from specified post
								$product = micro_office_get_custom_option('product', '', $post_data['post_id'], $post_data['post_type']);				// !!!!!! Get option from specified post
								$product_link = $product ? get_permalink($product) : '';
								
								if (!empty($price)) {
									?>
									<div class="post_price"><span class="post_price_currency">$</span><span class="post_price_value"><?php echo ($price) . ($price_period ? '</span> / <span class="post_price_period">'.($price_period) : ''); ?></span></div>
									<?php
								}
							}?>
					</div>

				</div>				<!-- /.post_content -->
			</<?php micro_office_show_layout($tag); ?>>	<!-- /.post_item -->
		</div>						<!-- /.isotope_item -->
		<?php
	}
}
?>