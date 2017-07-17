<?php
$social_login = do_shortcode(apply_filters('micro_office_filter_social_login', micro_office_get_theme_option('social_login')));
?>
<div id="popup_login" class="popup_wrap popup_login bg_tint_light<?php if (empty($social_login)) echo ' popup_half'; ?>">
	<a href="#" class="popup_close"></a>
	<div class="form_wrap">
		<div<?php if (!empty($social_login)) echo ' class="form_left"'; ?>>
			<form action="<?php echo wp_login_url(); ?>" method="post" name="login_form" class="popup_form login_form">
				<input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/')); ?>">
				<div class="popup_form_field login_field iconed_field icon-user"><input type="text" id="log" name="log" value="" placeholder="<?php esc_attr_e('Login or Email', 'micro-office'); ?>"></div>
				<div class="popup_form_field password_field iconed_field icon-lock"><input type="password" id="password" name="pwd" value="" placeholder="<?php esc_attr_e('Password', 'micro-office'); ?>"></div>
				<div class="popup_form_field remember_field">
					<a href="<?php echo esc_url(wp_lostpassword_url(get_permalink())); ?>" class="forgot_password"><?php esc_html_e('Forgot password?', 'micro-office'); ?></a>
					<input type="checkbox" value="forever" id="rememberme" name="rememberme">
					<label for="rememberme"><?php esc_html_e('Remember me', 'micro-office'); ?></label>
				</div>
				<div class="popup_form_field submit_field"><input type="submit" class="submit_button" value="<?php esc_attr_e('Login', 'micro-office'); ?>"></div>
			</form>
		</div>
		<?php if (!empty($social_login))  { ?>
			<div class="form_right">
				<div class="login_socials_title"><?php esc_html_e('You can login using your social profile', 'micro-office'); ?></div>
				<div class="login_socials_list">
					<?php micro_office_show_layout($social_login); ?>
				</div>
			</div>
		<?php } ?>
	</div>	<!-- /.login_wrap -->
</div>		<!-- /.popup_login -->
