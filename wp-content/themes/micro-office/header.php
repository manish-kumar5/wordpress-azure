<?php
/**
 * The Header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="<?php
		// Add class 'scheme_xxx' into <html> because it used as context for the body classes!
		$body_scheme = micro_office_get_custom_option('body_scheme');
		if (empty($body_scheme) || micro_office_is_inherit_option($body_scheme)) $body_scheme = 'original';
		echo 'scheme_' . esc_attr($body_scheme); 
		?>">

<head>
	<?php wp_head(); ?>
</head>

<body <?php body_class();?>>

	<?php do_action( 'before' ); ?>		
		
	<div class="body_wrap">
		<div class="page_wrap">

			<?php
			get_template_part(micro_office_get_file_slug('templates/headers/logo_panel.php'));
			?>

			<div class="page_content_wrap">
				<?php				
				get_template_part(micro_office_get_file_slug('sidebar_outer.php')); 
				get_template_part(micro_office_get_file_slug('templates/headers/_parts/menu_wrap.php'));
				
				$show_title = micro_office_get_custom_option('show_page_title')=='yes';
				$show_navi = apply_filters('micro_office_filter_show_post_navi', false);
				$show_breadcrumbs = micro_office_get_custom_option('show_breadcrumbs')=='yes';
					
				// Main content_wrap wrapper
				micro_office_open_wrapper('<div class="content_wrap '.($show_title || $show_breadcrumbs ? 'with_title' : '').'">');
					
					// Top of page section: page title and breadcrumbs
					if ($show_title || $show_breadcrumbs) {
						?>
						<div class="top_panel_title">
							<div class="top_panel_title_inner">
								<?php
								if ($show_title) {
									if ($show_navi) {
										?><div class="post_navi"><?php 
											previous_post_link( '<span class="post_navi_item post_navi_prev">%link</span>', '%title', true, '', 'product_cat' );
											next_post_link( '<span class="post_navi_item post_navi_next">%link</span>', '%title', true, '', 'product_cat' );
										?></div><?php
									} else if(!is_home() && !is_front_page()){
										?><h1 class="page_title"><?php echo strip_tags(micro_office_get_blog_title()); ?></h1><?php
									}
								}
								if ($show_breadcrumbs) {
									?><div class="breadcrumbs"><?php if (!is_404()) micro_office_show_breadcrumbs(); ?></div><?php
								}
								?>
							</div>
						</div>
						<?php
					}
				?>