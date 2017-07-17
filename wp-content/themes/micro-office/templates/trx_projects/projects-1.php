<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_projects_1_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_projects_1_theme_setup', 1 );
	function micro_office_template_projects_1_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'projects-1',
			'template' => 'projects-1',
			'mode'   => 'projects',
			'title'  => esc_html__('Projects /Style 1/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image (crop) projects', 'micro-office'),
			'w'		 => 231,
			'h'		 => 171
		));
	}
}

// Template output
if ( !function_exists( 'micro_office_template_projects_1_output' ) ) {
	function micro_office_template_projects_1_output($post_options, $post_data) {
	
		?>
			<div<?php echo !empty($post_options['tag_id']) ? ' id="'.esc_attr($post_options['tag_id']).'"' : ''; ?>
				class="sc_projects_item sc_projects_item_<?php echo esc_attr($post_options['number']) . ($post_options['number'] % 2 == 1 ? ' odd' : ' even') . ($post_options['number'] == 1 ? ' first' : '') . (!empty($post_options['tag_class']) ? ' '.esc_attr($post_options['tag_class']) : ''); ?>"
				<?php echo (!empty($post_options['tag_css']) ? ' style="'.esc_attr($post_options['tag_css']).'"' : '') 
					. (!micro_office_param_is_off($post_options['tag_animation']) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($post_options['tag_animation'])).'"' : ''); ?>>
		
					<div class="sc_projects_item_featured post_featured">
						<?php
						micro_office_template_set_args('post-featured', array(
							'post_options' => $post_options,
							'post_data' => $post_data
						));
						get_template_part(micro_office_get_file_slug('templates/_parts/post-featured.php'));
						?>
					</div>
					<?php
				
				?>
				<div class="sc_projects_item_content">
					<?php
						if ((!isset($post_options['links']) || $post_options['links']) && !empty($post_data['post_link'])) {
							?><h4 class="sc_projects_item_title"><a href="<?php echo esc_url($post_data['post_link']); ?>"><?php micro_office_show_layout($post_data['post_title']); ?></a></h4><?php
						} else {
							?><h4 class="sc_projects_item_title"><?php micro_office_show_layout($post_data['post_title']); ?></h4><?php
						}
					
					?>

					<div class="sc_projects_item_description">
						<?php
						if ($post_data['post_protected']) {
							micro_office_show_layout($post_data['post_excerpt']); 
						} else {
							if ($post_data['post_excerpt'] && $post_options['descr'] != 0) {
								echo '<p>'.trim(micro_office_strshort($post_data['post_excerpt'], $post_options['descr'])).'</p>';
							}
						}
						$post_meta = get_post_meta($post_data['post_id'], micro_office_storage_get('options_prefix') . '_post_options', true);
						$start_date = '';
						$finish_date = '';
						if($post_meta) {
							$start_date = (array_key_exists('start_date', $post_meta)  ? date_create($post_meta['start_date']) : '');
							$finish_date = (array_key_exists('finish_date', $post_meta)  ? date_create($post_meta['finish_date']) : '');
						}
						
						if(!empty($start_date) && !empty($finish_date) && $finish_date > $start_date){
							$today = date_create(date("Y-m-d")); 
							$percent = 0;
							
							$days = date_diff($start_date, $finish_date);
							$days = $days->format("%R%a") + 1;
							
							
							$left = date_diff($today, $finish_date);
							$left = $left->format("%R%a") + 1;
							
							if($today > $finish_date) 
								$percent = 100;
							else  if($today > $start_date && $today < $finish_date)
								$percent = round(100 - (100 * $left / $days));
													
							echo do_shortcode('[trx_skills][trx_skills_item value="'.$percent.'%"][/trx_skills]');
							echo '<div class="total">'.esc_html($percent.'%').'</div>';
						}
						?>
					</div>
				</div>
			</div>
		<?php
	}
}
?>