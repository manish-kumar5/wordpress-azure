<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_no_articles_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_no_articles_theme_setup', 1 );
	function micro_office_template_no_articles_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'no-articles',
			'mode'   => 'internal',
			'title'  => esc_html__('No articles found', 'micro-office')
		));
	}
}

// Template output
if ( !function_exists( 'micro_office_template_no_articles_output' ) ) {
	function micro_office_template_no_articles_output($post_options, $post_data) {
		?>
		<article class="post_item">
			<div class="post_content">
				<h2 class="post_title"><?php esc_html_e('No posts found', 'micro-office'); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search criteria.', 'micro-office' ); ?></p>
				<p><?php echo wp_kses_data( sprintf(__('Go back, or return to <a href="%s">%s</a> home page to choose a new page.', 'micro-office'), esc_url(home_url('/')), get_bloginfo()) ); ?>
				<br><?php esc_html_e('Please report any broken links to our team.', 'micro-office'); ?></p>
				<?php micro_office_show_layout(micro_office_sc_search(array('state'=>"fixed"))); ?>
			</div>	<!-- /.post_content -->
		</article>	<!-- /.post_item -->
		<?php
	}
}
?>