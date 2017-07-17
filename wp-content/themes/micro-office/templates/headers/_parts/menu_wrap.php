<div class="menu_main_wrap">
	<nav class="menu_main_nav_area">
		<?php
		$menu_main = micro_office_get_nav_menu('menu_main');
		if (empty($menu_main)) $menu_main = micro_office_get_nav_menu();
		micro_office_show_layout($menu_main);
		?>
	</nav>
</div>