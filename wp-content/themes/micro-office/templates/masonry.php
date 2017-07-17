<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_masonry_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_masonry_theme_setup', 1 );
	function micro_office_template_masonry_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'masonry_2',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile (different height) /2 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => 450
		));
		micro_office_add_template(array(
			'layout' => 'masonry_3',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /3 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => 450
		));
		micro_office_add_template(array(
			'layout' => 'masonry_4',
			'template' => 'masonry',
			'mode'   => 'blog',
			'need_isotope' => true,
			'title'  => esc_html__('Masonry tile /4 columns/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image - masonry', 'micro-office'),
			'w'		 => 510,
			'h_crop' => 450,
			'h'      => 450
		));
		// Add template specific scripts
		add_action('micro_office_action_blog_scripts', 'micro_office_template_masonry_add_scripts');
	}
}

// Add template specific scripts
if (!function_exists('micro_office_template_masonry_add_scripts')) {
	
	function micro_office_template_masonry_add_scripts($style) {
		if (in_array(micro_office_substr($style, 0, 8), array('classic_', 'masonry_'))) {
			wp_enqueue_script( 'isotope', micro_office_get_file_url('js/jquery.isotope.min.js'), array(), null, true );
		}
	}
}

// Template output
if ( !function_exists( 'micro_office_template_masonry_output' ) ) {
	function micro_office_template_masonry_output($post_options, $post_data) {
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
				
				<?php if ($post_data['post_thumb'] && !$post_data['post_audio'] && !$post_data['post_video'] && !$post_data['post_gallery']) { ?>
					<div class="post_featured">
						<?php
						micro_office_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(micro_office_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
				<?php } 

				if($post_data['post_audio'] || $post_data['post_video'] || $post_data['post_gallery'])
				{ ?>
					<div class="post_media"<?php echo ($post_data['post_audio'] && $post_data['post_attachment'] ? ' style="background-image:url('.($post_data['post_attachment']).');"' : ''); ?>>
						<?php
						if($post_data['post_audio']){ ?>
							<div class="post_audio">
								<h3 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php micro_office_show_layout($post_data['post_title']); ?></a></h3><?php
						}
						
						micro_office_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(micro_office_get_file_slug('templates/_parts/post-featured.php'));
						
						if($post_data['post_audio']){ ?>
							</div><?php
						}
						
						if (!$post_data['post_protected'] && $post_options['info']) {
							$post_options['info_parts'] = array('counters'=>true, 'terms'=>false, 'author'=>false, 'date' => false);
							micro_office_template_set_args('post-info', array(
								'post_options' => $post_options,
								'post_data' => $post_data
							));
							get_template_part(micro_office_get_file_slug('templates/_parts/post-info.php'));
						}?>
					</div>
				<?php
				}
				else{ ?>
					<div class="post_content isotope_item_content">
						<?php
						if ($show_title) {
							if (!isset($post_options['links']) || $post_options['links']) {
								?>
								<h4 class="post_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php micro_office_show_layout($post_data['post_title']); ?></a></h4>
								<?php
							} else {
								?>
								<h4 class="post_title"><?php micro_office_show_layout($post_data['post_title']); ?></h4>
								<?php
							}
						}
						if(in_array($post_data['post_format'], array('aside', 'chat', 'status', 'link', 'quote')))
						{
							echo in_array($post_data['post_format'], array('quote', 'link', 'chat', 'aside', 'status')) ? $post_data['post_excerpt'] : '<p>'.trim(micro_office_strshort($post_data['post_excerpt'], isset($post_options['descr']) ? $post_options['descr'] : micro_office_get_custom_option('post_excerpt_maxlength_masonry'))).'</p>';
								
						}
						
						if (!$post_data['post_protected'] && $post_options['info']) {
							$post_options['info_parts'] = array('counters'=>true, 'terms'=>false, 'author'=>true);
							micro_office_template_set_args('post-info', array(
								'post_options' => $post_options,
								'post_data' => $post_data
							));
							get_template_part(micro_office_get_file_slug('templates/_parts/post-info.php'));
						}
						?>
					</div><!-- /.post_content -->
				<?php
				}?>
			</<?php micro_office_show_layout($tag); ?>>	<!-- /.post_item -->
		</div>	<!-- /.isotope_item -->
		<?php
	}
}
?>