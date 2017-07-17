<?php
/**
 * Theme sprecific functions and definitions
 */

/* Theme setup section
------------------------------------------------------------------- */

// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) $content_width = 1170; /* pixels */


// Add theme specific actions and filters
// Attention! Function were add theme specific actions and filters handlers must have priority 1
if ( !function_exists( 'micro_office_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_theme_setup', 1 );
	function micro_office_theme_setup() {

		// Register theme menus
		add_filter( 'micro_office_filter_add_theme_menus',		'micro_office_add_theme_menus' );

		// Register theme sidebars
		add_filter( 'micro_office_filter_add_theme_sidebars',	'micro_office_add_theme_sidebars' );

		// Set options for importer
		add_filter( 'micro_office_filter_importer_options',		'micro_office_set_importer_options' );

		// Add theme required plugins
		add_filter( 'micro_office_filter_required_plugins',		'micro_office_add_required_plugins' );
		
		// Add preloader styles
		add_filter('micro_office_filter_add_styles_inline',		'micro_office_head_add_page_preloader_styles');

		// Init theme after WP is created
		add_action( 'wp',									'micro_office_core_init_theme' );

		// Add theme specified classes into the body
		add_filter( 'body_class', 							'micro_office_body_classes' );

		// Add data to the head and to the beginning of the body
		add_action('wp_head',								'micro_office_head_add_page_meta', 1);
		add_action('before',								'micro_office_body_add_gtm');
		add_action('before',								'micro_office_body_add_page_preloader');

		// Add data to the footer (priority 1, because priority 2 used for localize scripts)
		add_action('wp_footer',								'micro_office_footer_add_views_counter', 1);
		add_action('wp_footer',								'micro_office_footer_add_theme_customizer', 1);
		add_action('wp_footer',								'micro_office_footer_add_gtm2', 1);


		remove_action ('wp_head', 'rsd_link');
		
		// Set list of the theme required plugins
		micro_office_storage_set('required_plugins', array(
			'buddypress',		// Attention! This slug used to install both BuddyPress and bbPress
			'responsive_poll',
			'trx_utils',
			'visual_composer',
			'wp-pro-quiz',
			'content_timeline',
			)
		);

		// Set list of the theme required custom fonts from folder /css/font-faces
		// Attention! Font's folder must have name equal to the font's name
		micro_office_storage_set('required_custom_fonts', array(
			'Amadeus'
			)
		);
		
		micro_office_storage_set('demo_data_url',  esc_url(micro_office_get_protocol() . '://microoffice.themerex.net/demo/'));

	}
}


// Add/Remove theme nav menus
if ( !function_exists( 'micro_office_add_theme_menus' ) ) {
	function micro_office_add_theme_menus($menus) {
		return $menus;
	}
}


// Add theme specific widgetized areas
if ( !function_exists( 'micro_office_add_theme_sidebars' ) ) {
	function micro_office_add_theme_sidebars($sidebars=array()) {
		if (is_array($sidebars)) {
			$theme_sidebars = array(
				'sidebar_main'		=> esc_html__( 'Main Sidebar', 'micro-office' ),
				'sidebar_footer'	=> esc_html__( 'Footer Sidebar', 'micro-office' )
			);
			if (function_exists('micro_office_exists_woocommerce') && micro_office_exists_woocommerce()) {
				$theme_sidebars['sidebar_cart']  = esc_html__( 'WooCommerce Cart Sidebar', 'micro-office' );
			}
			$sidebars = array_merge($theme_sidebars, $sidebars);
		}
		return $sidebars;
	}
}


// Add theme required plugins
if ( !function_exists( 'micro_office_add_required_plugins' ) ) {
	function micro_office_add_required_plugins($plugins) {
		$plugins[] = array(
			'name' 		=> esc_html__('Micro Office Utilities', 'micro-office'),
			'version'	=> '3.0',					// Minimal required version
			'slug' 		=> 'trx_utils',
			'source'	=> micro_office_get_file_dir('plugins/install/trx_utils.zip'),
			'required' 	=> true
		);
		return $plugins;
	}
}


// One-click import support
//------------------------------------------------------------------------

// Set theme specific importer options
if ( !function_exists( 'micro_office_set_importer_options' ) ) {
	function micro_office_set_importer_options($options=array()) {
		if (is_array($options)) {
			// Default demo
			$options['demo_url'] = micro_office_storage_get('demo_data_url');
			$options['files']['default']['title'] = esc_html__('Micro Office Demo', 'micro-office');
			$options['files']['default']['domain_dev'] = '';    // Developers domain
			$options['files']['default']['domain_demo']= esc_url(micro_office_get_protocol().'://microoffice.themerex.net');        // Demo-site domain
		}
		return $options;
	}
}


// Add data to the head and to the beginning of the body
//------------------------------------------------------------------------

// Add theme specified classes to the body tag
if ( !function_exists('micro_office_body_classes') ) {
	function micro_office_body_classes( $classes ) {

		$classes[] = 'micro_office_body';
		$classes[] = 'body_style_' . trim('wide');
		$classes[] = 'body_type_' . trim(micro_office_get_custom_option('body_type'));
		$classes[] = 'body_' . (micro_office_get_custom_option('body_filled')=='yes' ? 'filled' : 'transparent');
		$classes[] = 'article_style_' . trim(micro_office_get_custom_option('article_style'));
		
		$blog_style = micro_office_get_custom_option(is_singular() && !micro_office_storage_get('blog_streampage') ? 'single_style' : 'blog_style');
		$classes[] = 'layout_' . trim($blog_style);
		$classes[] = 'template_' . trim(micro_office_get_template_name($blog_style));
		
		$body_scheme = micro_office_get_custom_option('body_scheme');
		if (empty($body_scheme)  || micro_office_is_inherit_option($body_scheme)) $body_scheme = 'original';
		$classes[] = 'scheme_' . $body_scheme;

		$top_panel_position = micro_office_get_custom_option('top_panel_position');
		if (!micro_office_param_is_off($top_panel_position)) {
			$classes[] = 'top_panel_show';
			$classes[] = 'top_panel_' . trim($top_panel_position);
		} else 
			$classes[] = 'top_panel_hide';
		$classes[] = micro_office_get_sidebar_class();

		if (micro_office_get_custom_option('show_video_bg')=='yes' && (micro_office_get_custom_option('video_bg_youtube_code')!='' || micro_office_get_custom_option('video_bg_url')!=''))
			$classes[] = 'video_bg_show';

		if (!micro_office_param_is_off(micro_office_get_theme_option('page_preloader')))
			$classes[] = 'preloader';

		return $classes;
	}
}


// Add page meta to the head
if (!function_exists('micro_office_head_add_page_meta')) {
	function micro_office_head_add_page_meta() {
		?>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1<?php if (micro_office_get_theme_option('responsive_layouts')=='yes') echo ', maximum-scale=1'; ?>">
		<meta name="format-detection" content="telephone=no">
	
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<?php
	}
}

// Add page preloader styles to the head
if (!function_exists('micro_office_head_add_page_preloader_styles')) {
	function micro_office_head_add_page_preloader_styles($css) {
		if (($preloader=micro_office_get_theme_option('page_preloader'))!='none') {
			$image = micro_office_get_theme_option('page_preloader_image');
			$bg_clr = micro_office_get_scheme_color('bg_color');
			$link_clr = micro_office_get_scheme_color('text_link');
			$css .= '
				#page_preloader {
					background-color: '. esc_attr($bg_clr) . ';'
					. ($preloader=='custom' && $image
						? 'background-image:url('.esc_url($image).');'
						: ''
						)
				    . '
				}
				.preloader_wrap > div {
					background-color: '.esc_attr($link_clr).';
				}';
		}
		return $css;
	}
}

// Add gtm code to the beginning of the body 
if (!function_exists('micro_office_body_add_gtm')) {
	function micro_office_body_add_gtm() {
		micro_office_show_layout(force_balance_tags(micro_office_get_custom_option('gtm_code')));
	}
}

// Add page preloader to the beginning of the body
if (!function_exists('micro_office_body_add_page_preloader')) {
	function micro_office_body_add_page_preloader() {
		if ( ($preloader=micro_office_get_theme_option('page_preloader')) != 'none' && ( $preloader != 'custom' || ($image=micro_office_get_theme_option('page_preloader_image')) != '')) {
			?><div id="page_preloader"><?php
				if ($preloader == 'circle') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_circ1"></div><div class="preloader_circ2"></div><div class="preloader_circ3"></div><div class="preloader_circ4"></div></div><?php
				} else if ($preloader == 'square') {
					?><div class="preloader_wrap preloader_<?php echo esc_attr($preloader); ?>"><div class="preloader_square1"></div><div class="preloader_square2"></div></div><?php
				}
			?></div><?php
		}
	}
}


// Add data to the footer
//------------------------------------------------------------------------

// Add post/page views counter
if (!function_exists('micro_office_footer_add_views_counter')) {
	function micro_office_footer_add_views_counter() {
		// Post/Page views counter
		get_template_part(micro_office_get_file_slug('templates/_parts/views-counter.php'));
	}
}


// Add theme customizer
if (!function_exists('micro_office_footer_add_theme_customizer')) {
	function micro_office_footer_add_theme_customizer() {
		// Front customizer
		if (micro_office_get_custom_option('show_theme_customizer')=='yes') {
			require_once MICRO_OFFICE_FW_PATH . 'core/core.customizer/front.customizer.php';
		}
	}
}

// Add gtm code
if (!function_exists('micro_office_footer_add_gtm2')) {
	function micro_office_footer_add_gtm2() {
		micro_office_show_layout(force_balance_tags(micro_office_get_custom_option('gtm_code2')));
	}
}

// Custom login
if ( !function_exists( 'micro_office_custom_login' ) ) {
	add_action('login_enqueue_scripts', 'micro_office_custom_login');
	function micro_office_custom_login() {
		// Include CSS 
		wp_enqueue_style ( 'custom-login', micro_office_get_file_url('css/style-login.css'), array(), null );
		wp_enqueue_script ( 'custom-login', micro_office_get_file_url('js/script-login.js'), array('jquery'), null, true );
	}
}

// Custom login
if ( !function_exists( 'micro_office_custom_form' ) ) {
	add_action('login_message', 'micro_office_custom_form');
	function micro_office_custom_form() {
		$login_descr = micro_office_get_custom_option('login_descr');
		if(!empty($login_descr)){
			micro_office_show_layout('<p class="login_descr">'.$login_descr.'</p>');
		}
	}
}

// Include framework core files
//-------------------------------------------------------------------
require_once trailingslashit( get_template_directory() ) . 'fw/loader.php';
?>