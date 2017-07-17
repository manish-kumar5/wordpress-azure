<?php
/**
 * The template for displaying the footer.
 */		
				//Scroll to top
				?>
				<a href="#" class="scroll_to_top icon-up" title="<?php esc_attr_e('Scroll to top', 'micro-office'); ?>"></a>
				<?php
				
				micro_office_close_wrapper();	// <!-- </.content_wrap> -->

				// Show main sidebar
				get_sidebar();

				?>
			
			</div>		<!-- </.page_content_wrap> -->
			
			<?php
			
			// Footer sidebar
			$footer_show  = micro_office_get_custom_option('show_sidebar_footer');
			$sidebar_name = micro_office_get_custom_option('sidebar_footer');
			if (!micro_office_param_is_off($footer_show) && is_active_sidebar($sidebar_name)) { 
				micro_office_storage_set('current_sidebar', 'footer');
				?>
				<footer class="footer_wrap widget_area scheme_<?php echo esc_attr(micro_office_get_custom_option('sidebar_footer_scheme')); ?>">
					<div class="footer_wrap_inner widget_area_inner">
						<div class="content_wrap">
							<div class="columns_wrap"><?php
							ob_start();
							do_action( 'before_sidebar' );
							if ( !dynamic_sidebar($sidebar_name) ) {
								// Put here html if user no set widgets in sidebar
							}
							do_action( 'after_sidebar' );
							$out = ob_get_contents();
							ob_end_clean();
							micro_office_show_layout(chop(preg_replace("/<\/aside>[\r\n\s]*<aside/", "</aside><aside", $out)));
							?></div>	<!-- /.columns_wrap -->
						</div>
					</div>	<!-- /.footer_wrap_inner -->
				</footer>	<!-- /.footer_wrap -->
				<?php
			}

			// Copyright area
			$copyright_style = micro_office_get_custom_option('show_copyright_in_footer');
			if (!micro_office_param_is_off($copyright_style)) {
				?> 
				<div class="copyright_wrap copyright_style_<?php echo esc_attr($copyright_style); ?>  scheme_<?php echo esc_attr(micro_office_get_custom_option('copyright_scheme')); ?>">
					<div class="copyright_wrap_inner">
						<div class="content_wrap">
							<?php
							if ($copyright_style == 'menu') {
								if (($menu = micro_office_get_nav_menu('menu_footer'))!='') {
									echo wp_kses_data($menu);
								}
							} else if ($copyright_style == 'socials') {
								echo wp_kses_data(micro_office_sc_socials(array('size'=>"tiny")));
							}
							?>
							<div class="copyright_text"><?php 
								$micro_office_copyright = micro_office_prepare_macros(micro_office_get_custom_option('footer_copyright'));
								if (!empty($micro_office_copyright)) {
									if (preg_match("/(\\{[\\w\\d\\\\\\-\\:]*\\})/", $micro_office_copyright, $micro_office_matches)) {
										$micro_office_copyright = str_replace($micro_office_matches[1], date(str_replace(array('{', '}'), '', $micro_office_matches[1])), $micro_office_copyright);
									}
									echo wp_kses_data(nl2br($micro_office_copyright));
								}
							?></div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
			
		</div><!-- /.page_wrap -->

	</div><!-- /.body_wrap -->

	<?php wp_footer(); ?>

</body>
</html>