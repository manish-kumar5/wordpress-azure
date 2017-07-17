<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>


	<header class="top_panel_wrap">
		<div class="top_panel_wrap_inner">
			<div class="menu_pushy"><span class="icon-menu">Menu</span></div>
			<div class="content_wrap">
				<div class="contact_logo">
					<?php micro_office_show_logo(true, true); ?>
				</div>
			</div>
			<div class="sidebar_wrap sidebar">
				<?php
				if ( is_user_logged_in() ) {
					$current_user = wp_get_current_user();
					if(micro_office_get_custom_option('show_sidebar_outer')=='show' && function_exists('bp_is_active') && is_user_logged_in()){
					?>
					<div class="top_panel_user">
						<a href="#"><?php
							$user_avatar = '';
							$mult = micro_office_get_retina_multiplier();
							if ($current_user->user_email) $user_avatar = get_avatar($current_user->user_email, 96*$mult);
							if ($user_avatar) {
								?><span class="user_avatar"><?php micro_office_show_layout($user_avatar); ?></span><?php
							}?><span class="user_name"><?php micro_office_show_layout($current_user->display_name); ?></span>
						</a>
					</div><?php 
					}
				}
				?>
				<div class="top_panel_controls">
					<?php
					$sidebar_show   = micro_office_get_custom_option('show_sidebar_main');
					$sidebar_name   = micro_office_get_custom_option('sidebar_main');
					if($sidebar_show != 'hide' && !empty($sidebar_name))
					{ ?>
						<div class="sidebar_pushy">
							<a href="#" class="pushy_button sc_button"><span class="icon-right-open"></span></a>
						</div>
					<?php
					}
					
					if (micro_office_get_custom_option('show_search')=='yes') 
						micro_office_show_layout(micro_office_sc_search(array('state'=>"closed", "style"=>'fullscreen')));
					?>
				</div>
			</div>
		</div>
	</header><!-- </.top_panel_wrap> -->