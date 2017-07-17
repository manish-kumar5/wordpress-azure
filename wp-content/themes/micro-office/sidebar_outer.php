<?php
/**
 * The Sidebar Outer containing the outer (left or right) widget areas.
 */

$sidebar_show   = micro_office_get_custom_option('show_sidebar_outer');

if (!micro_office_param_is_off($sidebar_show) &&  function_exists('bp_is_active') && is_user_logged_in()) {
	?>
	<div class="sidebar_outer widget_area" role="complementary">
		<div class="sidebar_outer_inner widget_area_inner">
			<?php 
				$user_ID = get_current_user_id();
				$current_user = wp_get_current_user();
				$name_to_display = micro_office_get_name_to_display($user_ID);
				$profile = bp_loggedin_user_domain();
				?>
				<div id="user_header">
					<a href="<?php echo esc_url(bp_core_get_user_domain($user_ID)); ?>" class="clearfix">
						<div class="user_avatar"><?php echo get_avatar($user_ID, 125);?></div>
						<h2><?php echo  esc_html__('Welcome', 'micro-office').',<br/> '.esc_html($name_to_display); ?>!</h2>
					</a>
				</div>
				
				<ul id="user_menu">
				<?php
				if( bp_is_active( 'activity' ) ) { ?>
					
						<li class="menu-parent">
							<a href="<?php echo esc_url(bp_get_activity_directory_permalink()); ?>"><?php echo esc_html__('Activity', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('activity')); ?></a>
							<ul class="sub-menu">
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>activity/"><?php echo esc_html__('Activity', 'micro-office'); ?></a>
								</li>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>activity/mentions/"><?php echo esc_html__('Mentions', 'micro-office'); ?></a>
								</li>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>activity/favorites/"><?php echo esc_html__('Favorites', 'micro-office'); ?></a>
								</li>
							<?php
							if (bp_is_active('friends')) { ?>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>activity/friends/"><?php echo esc_html__('Friends', 'micro-office'); ?></a>
								</li> <?php
							}
							if (bp_is_active('groups')) { ?>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>activity/groups/"><?php echo esc_html__('Groups', 'micro-office'); ?></a>
								</li><?php
							} ?>
							</ul>
					</li><?php
				}

				if (bp_is_active('xprofile')) { ?>
					<li class="menu-parent">
							<a href="<?php echo esc_url($profile); ?>"><?php echo esc_html__('Profile', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('xprofile')); ?></a>
							<ul class="sub-menu">
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>profile/"><?php echo esc_html__('View', 'micro-office'); ?></a>
								</li>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>profile/edit/"><?php echo esc_html__('Edit', 'micro-office'); ?></a>
								</li>
							<?php
							if(bp_core_get_root_option( 'bp-disable-avatar-uploads' ) == 0){ ?>
								<li class="menu-child">
									<a href="<?php echo esc_url($profile); ?>profile/change-avatar/"><?php echo esc_html__('Change Profile Photo', 'micro-office'); ?></a>
								</li><?php
							}?>
						</ul>
					</li><?php
				}

				if (bp_is_active('notifications')) { ?>
					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>notifications/"><?php echo esc_html__('Notifications', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('notifications')); ?></a>
						<ul class="sub-menu">
							<li id="notifications-my-notifications-personal-li" class="menu-child">
								<a href="<?php echo esc_url($profile); ?>notifications/"><?php echo esc_html__('Unread', 'micro-office'); ?></a>
							</li>
							<li id="read-personal-li" class="menu-child">
								<a href="<?php echo esc_url($profile); ?>notifications/read/"><?php echo esc_html__('Read', 'micro-office'); ?></a>
							</li>
						</ul>
					</li><?php
				}

				if (bp_is_active('messages')) { ?>
					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>messages/"><?php echo esc_html__('Messages', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('messages')); ?></a>
						<ul class="sub-menu">
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>messages/"><?php echo esc_html__('Inbox', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>messages/starred/"><?php echo esc_html__('Starred', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>messages/sentbox/"><?php echo esc_html__('Sent', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>messages/compose/"><?php echo esc_html__('Compose', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>messages/notices/"><?php echo esc_html__('Notices', 'micro-office'); ?></a>
							</li>
						</ul>
					</li><?php
				}

				if (bp_is_active('friends')) { ?>
					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>friends/"><?php echo esc_html__('Friends', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('friends')); ?></a>
						<ul class="sub-menu">
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>friends/"><?php echo esc_html__('Friendships', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>friends/requests/"><?php echo esc_html__('Requests', 'micro-office'); ?></a>
							</li>
						</ul>
					</li><?php
				}

				if (bp_is_active('groups')) { ?>
					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>groups/"><?php echo esc_html__('Groups', 'micro-office'); ?> <?php micro_office_show_layout(micro_office_user_notifications('groups')); ?></a>
						<ul class="sub-menu">
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>groups/"><?php echo esc_html__('Memberships', 'micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>groups/invites/"><?php echo esc_html__('Invitations', 'micro-office'); ?></a>
							</li>
						</ul>
					</li> <?php
				}

				if( bp_is_active( 'settings' ) ) { ?>
					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>settings/"><?php echo esc_html__('Settings','micro-office').' '.trim(micro_office_user_notifications('settings')); ?></a>
						<ul class="sub-menu">
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>settings/"><?php echo esc_html__('General','micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>settings/notifications/"><?php echo esc_html__('Email','micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>settings/profile/"><?php echo esc_html__('Profile Visibility','micro-office'); ?></a>
							</li>
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>settings/capabilities/"><?php echo esc_html__('Capabilities','micro-office'); ?></a>
							</li>
						</ul>
					</li><?php
				}

				if (function_exists("buddypress_learndash")) { ?>

					<li class="menu-parent">
						<a href="<?php echo esc_url($profile); ?>courses/"><?php echo esc_html__('Courses','micro-office'); ?>'</a>
						<ul class="sub-menu">
							<li class="menu-child">
								<a href="<?php echo esc_url($profile); ?>courses/"><?php echo esc_html__('General','micro-office'); ?></a>
							</li>
						</ul>
					</li><?php
				}

				if ( has_nav_menu('micro_office_user') ) {
					wp_nav_menu(array('theme_location' => 'micro_office_user', 'menu_id'=>'dropdown-user-menu', 'container' => ''));
				}
				
				// Log out URL 
				?>
					<li id="logout-li"><a href="<?php echo esc_url(wp_logout_url()); ?>"><?php echo esc_html__('Log Out','micro-office'); ?></a></li>
				</ul>

		</div> <!-- /.sidebar_outer_inner -->
	</div> <!-- /.sidebar_outer -->
	<?php
}
?>