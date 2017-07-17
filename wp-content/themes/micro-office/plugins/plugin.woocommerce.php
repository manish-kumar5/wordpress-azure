<?php
/* Woocommerce support functions
------------------------------------------------------------------------------- */

// Theme init
if (!function_exists('micro_office_woocommerce_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_woocommerce_theme_setup', 1 );
	function micro_office_woocommerce_theme_setup() {

		if (micro_office_exists_woocommerce()) {
			add_action('micro_office_action_add_styles', 				'micro_office_woocommerce_frontend_scripts' );

			// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
			add_filter('micro_office_filter_get_blog_type',				'micro_office_woocommerce_get_blog_type', 9, 2);
			add_filter('micro_office_filter_get_blog_title',			'micro_office_woocommerce_get_blog_title', 9, 2);
			add_filter('micro_office_filter_get_current_taxonomy',		'micro_office_woocommerce_get_current_taxonomy', 9, 2);
			add_filter('micro_office_filter_is_taxonomy',				'micro_office_woocommerce_is_taxonomy', 9, 2);
			add_filter('micro_office_filter_get_stream_page_title',		'micro_office_woocommerce_get_stream_page_title', 9, 2);
			add_filter('micro_office_filter_get_stream_page_link',		'micro_office_woocommerce_get_stream_page_link', 9, 2);
			add_filter('micro_office_filter_get_stream_page_id',		'micro_office_woocommerce_get_stream_page_id', 9, 2);
			add_filter('micro_office_filter_detect_inheritance_key',	'micro_office_woocommerce_detect_inheritance_key', 9, 1);
			add_filter('micro_office_filter_detect_template_page_id',	'micro_office_woocommerce_detect_template_page_id', 9, 2);
			add_filter('micro_office_filter_orderby_need',				'micro_office_woocommerce_orderby_need', 9, 2);

			add_filter('micro_office_filter_show_post_navi', 			'micro_office_woocommerce_show_post_navi');
			add_filter('micro_office_filter_list_post_types', 			'micro_office_woocommerce_list_post_types');

			add_action('micro_office_action_shortcodes_list', 			'micro_office_woocommerce_reg_shortcodes', 20);
			if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
				add_action('micro_office_action_shortcodes_list_vc',	'micro_office_woocommerce_reg_shortcodes_vc', 20);

			if (is_admin()) {
				add_filter( 'micro_office_filter_importer_options',				'micro_office_woocommerce_importer_set_options' );
				add_action( 'micro_office_action_importer_after_import_posts',	'micro_office_woocommerce_importer_after_import_posts', 10, 1 );
				add_action( 'micro_office_action_importer_params',				'micro_office_woocommerce_importer_show_params', 10, 1 );
				add_action( 'micro_office_action_importer_import',				'micro_office_woocommerce_importer_import', 10, 2 );
				add_action( 'micro_office_action_importer_import_fields',		'micro_office_woocommerce_importer_import_fields', 10, 1 );
				add_action( 'micro_office_action_importer_export',				'micro_office_woocommerce_importer_export', 10, 1 );
				add_action( 'micro_office_action_importer_export_fields',		'micro_office_woocommerce_importer_export_fields', 10, 1 );
			}
		}

		if (is_admin()) {
			add_filter( 'micro_office_filter_importer_required_plugins',		'micro_office_woocommerce_importer_required_plugins', 10, 2 );
			add_filter( 'micro_office_filter_required_plugins',					'micro_office_woocommerce_required_plugins' );
		}
	}
}

if ( !function_exists( 'micro_office_woocommerce_settings_theme_setup2' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_woocommerce_settings_theme_setup2', 3 );
	function micro_office_woocommerce_settings_theme_setup2() {
		if (micro_office_exists_woocommerce()) {
			// Add WooCommerce pages in the Theme inheritance system
			micro_office_add_theme_inheritance( array( 'woocommerce' => array(
				'stream_template' => 'blog-woocommerce',		// This params must be empty
				'single_template' => 'single-woocommerce',		// They are specified to enable separate settings for blog and single wooc
				'taxonomy' => array('product_cat'),
				'taxonomy_tags' => array('product_tag'),
				'post_type' => array('product'),
				'override' => 'custom'
				) )
			);

			// Add WooCommerce specific options in the Theme Options

			micro_office_storage_set_array_before('options', 'partition_service', array(
				
				"partition_woocommerce" => array(
					"title" => esc_html__('WooCommerce', 'micro-office'),
					"icon" => "iconadmin-basket",
					"type" => "partition"),

				"info_wooc_1" => array(
					"title" => esc_html__('WooCommerce products list parameters', 'micro-office'),
					"desc" => esc_html__("Select WooCommerce products list's style and crop parameters", 'micro-office'),
					"type" => "info"),
		
				"shop_mode" => array(
					"title" => esc_html__('Shop list style',  'micro-office'),
					"desc" => esc_html__("WooCommerce products list's style: thumbs or list with description", 'micro-office'),
					"std" => "thumbs",
					"divider" => false,
					"options" => array(
						'thumbs' => esc_html__('Thumbs', 'micro-office'),
						'list' => esc_html__('List', 'micro-office')
					),
					"type" => "checklist"),
		
				"show_mode_buttons" => array(
					"title" => esc_html__('Show style buttons',  'micro-office'),
					"desc" => esc_html__("Show buttons to allow visitors change list style", 'micro-office'),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),

				"shop_loop_columns" => array(
					"title" => esc_html__('Shop columns',  'micro-office'),
					"desc" => esc_html__("How many columns used to show products on shop page", 'micro-office'),
					"std" => "3",
					"step" => 1,
					"min" => 1,
					"max" => 6,
					"type" => "spinner"),

				"show_currency" => array(
					"title" => esc_html__('Show currency selector', 'micro-office'),
					"desc" => esc_html__('Show currency selector in the user menu', 'micro-office'),
					"std" => "yes",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch"),
		
				"show_cart" => array(
					"title" => esc_html__('Show cart button', 'micro-office'),
					"desc" => esc_html__('Show cart button in the user menu', 'micro-office'),
					"std" => "shop",
					"options" => array(
						'hide'   => esc_html__('Hide', 'micro-office'),
						'always' => esc_html__('Always', 'micro-office'),
						'shop'   => esc_html__('Only on shop pages', 'micro-office')
					),
					"type" => "checklist"),

				"crop_product_thumb" => array(
					"title" => esc_html__("Crop product's thumbnail",  'micro-office'),
					"desc" => esc_html__("Crop product's thumbnails on search results page or scale it", 'micro-office'),
					"std" => "no",
					"options" => micro_office_get_options_param('list_yes_no'),
					"type" => "switch")
				
				)
			);

		}
	}
}

// WooCommerce hooks
if (!function_exists('micro_office_woocommerce_theme_setup3')) {
	add_action( 'micro_office_action_after_init_theme', 'micro_office_woocommerce_theme_setup3' );
	function micro_office_woocommerce_theme_setup3() {

		if (micro_office_exists_woocommerce()) {

			add_action(    'woocommerce_before_subcategory_title',		'micro_office_woocommerce_open_thumb_wrapper', 9 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'micro_office_woocommerce_open_thumb_wrapper', 9 );

			add_action(    'woocommerce_before_subcategory_title',		'micro_office_woocommerce_open_item_wrapper', 20 );
			add_action(    'woocommerce_before_shop_loop_item_title',	'micro_office_woocommerce_open_item_wrapper', 20 );

			add_action(    'woocommerce_after_subcategory',				'micro_office_woocommerce_close_item_wrapper', 20 );
			add_action(    'woocommerce_after_shop_loop_item',			'micro_office_woocommerce_close_item_wrapper', 20 );

			add_action(    'woocommerce_after_shop_loop_item_title',	'micro_office_woocommerce_after_shop_loop_item_title', 7);

			add_action(    'woocommerce_after_subcategory_title',		'micro_office_woocommerce_after_subcategory_title', 10 );

			// Remove link around product item
			remove_action('woocommerce_before_shop_loop_item',			'woocommerce_template_loop_product_link_open', 10);
			remove_action('woocommerce_after_shop_loop_item',			'woocommerce_template_loop_product_link_close', 5);
			// Remove link around product category
			remove_action('woocommerce_before_subcategory',				'woocommerce_template_loop_category_link_open', 10);
			remove_action('woocommerce_after_subcategory',				'woocommerce_template_loop_category_link_close', 10);

		}

		if (micro_office_is_woocommerce_page()) {
			
			remove_action( 'woocommerce_sidebar', 						'woocommerce_get_sidebar', 10 );					// Remove WOOC sidebar
			
			remove_action( 'woocommerce_before_main_content',			'woocommerce_output_content_wrapper', 10);
			add_action(    'woocommerce_before_main_content',			'micro_office_woocommerce_wrapper_start', 10);
			
			remove_action( 'woocommerce_after_main_content',			'woocommerce_output_content_wrapper_end', 10);		
			add_action(    'woocommerce_after_main_content',			'micro_office_woocommerce_wrapper_end', 10);

			add_action(    'woocommerce_show_page_title',				'micro_office_woocommerce_show_page_title', 10);

			remove_action( 'woocommerce_single_product_summary',		'woocommerce_template_single_title', 5);		
			add_action(    'woocommerce_single_product_summary',		'micro_office_woocommerce_show_product_title', 5 );

			add_action(    'woocommerce_before_shop_loop', 				'micro_office_woocommerce_before_shop_loop', 10 );

			remove_action( 'woocommerce_after_shop_loop',				'woocommerce_pagination', 10 );
			add_action(    'woocommerce_after_shop_loop',				'micro_office_woocommerce_pagination', 10 );

			add_action(    'woocommerce_product_meta_end',				'micro_office_woocommerce_show_product_id', 10);

			add_filter(    'woocommerce_output_related_products_args',	'micro_office_woocommerce_output_related_products_args' );
			add_filter(    'woocommerce_related_products_args',			'micro_office_woocommerce_related_products_args' );

			add_filter(    'woocommerce_product_thumbnails_columns',	'micro_office_woocommerce_product_thumbnails_columns' );

			add_filter(    'loop_shop_columns',							'micro_office_woocommerce_loop_shop_columns' );

			add_filter(    'get_product_search_form',					'micro_office_woocommerce_get_product_search_form' );

			add_filter(    'post_class',								'micro_office_woocommerce_loop_shop_columns_class' );
			add_action(    'the_title',									'micro_office_woocommerce_the_title');
			
			micro_office_enqueue_popup();
		}
	}
}



// Check if WooCommerce installed and activated
if ( !function_exists( 'micro_office_exists_woocommerce' ) ) {
	function micro_office_exists_woocommerce() {
		return class_exists('Woocommerce');
	}
}

// Return true, if current page is any woocommerce page
if ( !function_exists( 'micro_office_is_woocommerce_page' ) ) {
	function micro_office_is_woocommerce_page() {
		$rez = false;
		if (micro_office_exists_woocommerce()) {
			if (!micro_office_storage_empty('pre_query')) {
				$id = micro_office_storage_get_obj_property('pre_query', 'queried_object_id', 0);
				$rez = micro_office_storage_call_obj_method('pre_query', 'get', 'post_type')=='product' 
						|| $id==wc_get_page_id('shop')
						|| $id==wc_get_page_id('cart')
						|| $id==wc_get_page_id('checkout')
						|| $id==wc_get_page_id('myaccount')
						|| micro_office_storage_call_obj_method('pre_query', 'is_tax', 'product_cat')
						|| micro_office_storage_call_obj_method('pre_query', 'is_tax', 'product_tag')
						|| micro_office_storage_call_obj_method('pre_query', 'is_tax', get_object_taxonomies('product'));
						
			} else
				$rez = is_shop() || is_product() || is_product_category() || is_product_tag() || is_product_taxonomy() || is_cart() || is_checkout() || is_account_page();
		}
		return $rez;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'micro_office_woocommerce_detect_inheritance_key' ) ) {
	
	function micro_office_woocommerce_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return micro_office_is_woocommerce_page() ? 'woocommerce' : '';
	}
}

// Filter to detect current template page id
if ( !function_exists( 'micro_office_woocommerce_detect_template_page_id' ) ) {
	
	function micro_office_woocommerce_detect_template_page_id($id, $key) {
		if (!empty($id)) return $id;
		if ($key == 'woocommerce_cart')				$id = get_option('woocommerce_cart_page_id');
		else if ($key == 'woocommerce_checkout')	$id = get_option('woocommerce_checkout_page_id');
		else if ($key == 'woocommerce_account')		$id = get_option('woocommerce_account_page_id');
		else if ($key == 'woocommerce')				$id = get_option('woocommerce_shop_page_id');
		return $id;
	}
}

// Filter to detect current page type (slug)
if ( !function_exists( 'micro_office_woocommerce_get_blog_type' ) ) {
	
	function micro_office_woocommerce_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		
		if (is_shop()) 					$page = 'woocommerce_shop';
		else if ($query && $query->get('post_type')=='product' || is_product())		$page = 'woocommerce_product';
		else if ($query && $query->get('product_tag')!='' || is_product_tag())		$page = 'woocommerce_tag';
		else if ($query && $query->get('product_cat')!='' || is_product_category())	$page = 'woocommerce_category';
		else if (is_cart())				$page = 'woocommerce_cart';
		else if (is_checkout())			$page = 'woocommerce_checkout';
		else if (is_account_page())		$page = 'woocommerce_account';
		else if (is_woocommerce())		$page = 'woocommerce';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'micro_office_woocommerce_get_blog_title' ) ) {
	
	function micro_office_woocommerce_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		
		if ( micro_office_strpos($page, 'woocommerce')!==false ) {
			if ( $page == 'woocommerce_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_cat' ), 'product_cat', OBJECT);
				$title = $term->name;
			} else if ( $page == 'woocommerce_tag' ) {
				$term = get_term_by( 'slug', get_query_var( 'product_tag' ), 'product_tag', OBJECT);
				$title = esc_html__('Tag:', 'micro-office') . ' ' . esc_html($term->name);
			} else if ( $page == 'woocommerce_cart' ) {
				$title = esc_html__( 'Your cart', 'micro-office' );
			} else if ( $page == 'woocommerce_checkout' ) {
				$title = esc_html__( 'Checkout', 'micro-office' );
			} else if ( $page == 'woocommerce_account' ) {
				$title = esc_html__( 'Account', 'micro-office' );
			} else if ( $page == 'woocommerce_product' ) {
				$title = micro_office_get_post_title();
			} else if (($page_id=get_option('woocommerce_shop_page_id')) > 0) {
				$title = micro_office_get_post_title($page_id);
			} else {
				$title = esc_html__( 'Shop', 'micro-office' );
			}
		}
		
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'micro_office_woocommerce_get_stream_page_title' ) ) {
	
	function micro_office_woocommerce_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (micro_office_strpos($page, 'woocommerce')!==false) {
			if (($page_id = micro_office_woocommerce_get_stream_page_id(0, $page)) > 0)
				$title = micro_office_get_post_title($page_id);
			else
				$title = esc_html__('Shop', 'micro-office');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'micro_office_woocommerce_get_stream_page_id' ) ) {
	
	function micro_office_woocommerce_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (micro_office_strpos($page, 'woocommerce')!==false) {
			$id = get_option('woocommerce_shop_page_id');
		}
		return $id;
	}
}

// Filter to detect stream page link
if ( !function_exists( 'micro_office_woocommerce_get_stream_page_link' ) ) {
	
	function micro_office_woocommerce_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (micro_office_strpos($page, 'woocommerce')!==false) {
			$id = micro_office_woocommerce_get_stream_page_id(0, $page);
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'micro_office_woocommerce_get_current_taxonomy' ) ) {
	
	function micro_office_woocommerce_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( micro_office_strpos($page, 'woocommerce')!==false ) {
			$tax = 'product_cat';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'micro_office_woocommerce_is_taxonomy' ) ) {
	
	function micro_office_woocommerce_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query!==null && $query->get('product_cat')!='' || is_product_category() ? 'product_cat' : '';
	}
}

// Return false if current plugin not need theme orderby setting
if ( !function_exists( 'micro_office_woocommerce_orderby_need' ) ) {
	
	function micro_office_woocommerce_orderby_need($need) {
		if ($need == false || micro_office_storage_empty('pre_query'))
			return $need;
		else {
			return micro_office_storage_call_obj_method('pre_query', 'get', 'post_type')!='product' 
					&& micro_office_storage_call_obj_method('pre_query', 'get', 'product_cat')==''
					&& micro_office_storage_call_obj_method('pre_query', 'get', 'product_tag')=='';
		}
	}
}

// Add custom post type into list
if ( !function_exists( 'micro_office_woocommerce_list_post_types' ) ) {
	
	function micro_office_woocommerce_list_post_types($list) {
		$list['product'] = esc_html__('Products', 'micro-office');
		return $list;
	}
}


	
// Enqueue WooCommerce custom styles
if ( !function_exists( 'micro_office_woocommerce_frontend_scripts' ) ) {
	
	function micro_office_woocommerce_frontend_scripts() {
		if (micro_office_is_woocommerce_page() || micro_office_get_custom_option('show_cart')=='always')
			if (file_exists(micro_office_get_file_dir('css/plugin.woocommerce.css')))
				wp_enqueue_style( 'micro_office-plugin.woocommerce-style',  micro_office_get_file_url('css/plugin.woocommerce.css'), array(), null );
	}
}

// Before main content
if ( !function_exists( 'micro_office_woocommerce_wrapper_start' ) ) {
	
	function micro_office_woocommerce_wrapper_start() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			<article class="post_item post_item_single post_item_product">
			<?php
		} else {
			?>
			<div class="list_products shop_mode_<?php echo !micro_office_storage_empty('shop_mode') ? micro_office_storage_get('shop_mode') : 'thumbs'; ?>">
			<?php
		}
	}
}

// After main content
if ( !function_exists( 'micro_office_woocommerce_wrapper_end' ) ) {
	
	function micro_office_woocommerce_wrapper_end() {
		if (is_product() || is_cart() || is_checkout() || is_account_page()) {
			?>
			</article>	<!-- .post_item -->
			<?php
		} else {
			?>
			</div>	<!-- .list_products -->
			<?php
		}
	}
}

// Check to show page title
if ( !function_exists( 'micro_office_woocommerce_show_page_title' ) ) {
	
	function micro_office_woocommerce_show_page_title($defa=true) {
		return micro_office_get_custom_option('show_page_title')=='no';
	}
}

// Check to show product title
if ( !function_exists( 'micro_office_woocommerce_show_product_title' ) ) {
	
	function micro_office_woocommerce_show_product_title() {
		if (micro_office_get_custom_option('show_post_title')=='yes' || micro_office_get_custom_option('show_page_title')=='no') {
			wc_get_template( 'single-product/title.php' );
		}
	}
}

// Add list mode buttons
if ( !function_exists( 'micro_office_woocommerce_before_shop_loop' ) ) {
	
	function micro_office_woocommerce_before_shop_loop() {
		if (micro_office_get_custom_option('show_mode_buttons')=='yes') {
			echo '<div class="mode_buttons"><form action="' . esc_url(micro_office_get_current_url()) . '" method="post">'
				. '<input type="hidden" name="micro_office_shop_mode" value="'.esc_attr(micro_office_storage_get('shop_mode')).'" />'
				. '<a href="#" class="woocommerce_thumbs icon-th" title="'.esc_attr__('Show products as thumbs', 'micro-office').'"></a>'
				. '<a href="#" class="woocommerce_list icon-th-list" title="'.esc_attr__('Show products as list', 'micro-office').'"></a>'
				. '</form></div>';
		}
	}
}


// Open thumbs wrapper for categories and products
if ( !function_exists( 'micro_office_woocommerce_open_thumb_wrapper' ) ) {
	
	
	function micro_office_woocommerce_open_thumb_wrapper($cat='') {
		micro_office_storage_set('in_product_item', true);
		?>
		<div class="post_item_wrap">
			<div class="post_featured">
				<div class="post_thumb">
					<a class="hover_icon hover_icon_link" href="<?php echo esc_url(is_object($cat) ? get_term_link($cat->slug, 'product_cat') : get_permalink()); ?>">
		<?php
	}
}

// Open item wrapper for categories and products
if ( !function_exists( 'micro_office_woocommerce_open_item_wrapper' ) ) {
	
	
	function micro_office_woocommerce_open_item_wrapper($cat='') {
		?>
				</a>
			</div>
		</div>
		<div class="post_content">
		<?php
	}
}

// Close item wrapper for categories and products
if ( !function_exists( 'micro_office_woocommerce_close_item_wrapper' ) ) {
	
	
	function micro_office_woocommerce_close_item_wrapper($cat='') {
		?>
			</div>
		</div>
		<?php
		micro_office_storage_set('in_product_item', false);
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'micro_office_woocommerce_after_shop_loop_item_title' ) ) {
	
	function micro_office_woocommerce_after_shop_loop_item_title() {
		if (micro_office_storage_get('shop_mode') == 'list') {
		    $excerpt = apply_filters('the_excerpt', get_the_excerpt());
			echo '<div class="description">'.trim($excerpt).'</div>';
		}
	}
}

// Add excerpt in output for the product in the list mode
if ( !function_exists( 'micro_office_woocommerce_after_subcategory_title' ) ) {
	
	function micro_office_woocommerce_after_subcategory_title($category) {
		if (micro_office_storage_get('shop_mode') == 'list')
			echo '<div class="description">' . trim($category->description) . '</div>';
	}
}

// Add Product ID for single product
if ( !function_exists( 'micro_office_woocommerce_show_product_id' ) ) {
	
	function micro_office_woocommerce_show_product_id() {
		global $post, $product;
		echo '<span class="product_id">'.esc_html__('Product ID: ', 'micro-office') . '<span>' . ($post->ID) . '</span></span>';
	}
}

// Redefine number of related products
if ( !function_exists( 'micro_office_woocommerce_output_related_products_args' ) ) {
	
	function micro_office_woocommerce_output_related_products_args($args) {
		$ppp = $ccc = 0;
		if (micro_office_param_is_on(micro_office_get_custom_option('show_post_related'))) {
			$ccc_add = in_array('wide', array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  micro_office_get_custom_option('post_related_columns');
			$ccc = $ccc > 0 ? $ccc : (micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$ppp = micro_office_get_custom_option('post_related_count');
			$ppp = $ppp > 0 ? $ppp : $ccc;
		}
		$args['posts_per_page'] = $ppp;
		$args['columns'] = $ccc;
		return $args;
	}
}

// Redefine post_type if number of related products == 0
if ( !function_exists( 'micro_office_woocommerce_related_products_args' ) ) {
	
	function micro_office_woocommerce_related_products_args($args) {
		if ($args['posts_per_page'] == 0)
			$args['post_type'] .= '_';
		return $args;
	}
}

// Number columns for product thumbnails
if ( !function_exists( 'micro_office_woocommerce_product_thumbnails_columns' ) ) {
	
	function micro_office_woocommerce_product_thumbnails_columns($cols) {
		return 4;
	}
}

// Add column class into product item in shop streampage
if ( !function_exists( 'micro_office_woocommerce_loop_shop_columns_class' ) ) {
	
	function micro_office_woocommerce_loop_shop_columns_class($class) {
		global $woocommerce_loop;
		if (is_product()) {
			if (!empty($woocommerce_loop['columns']))
			$class[] = ' column-1_'.esc_attr($woocommerce_loop['columns']);
		} else if (!is_product() && !is_cart() && !is_checkout() && !is_account_page()) {
			$ccc_add = in_array('wide', array('fullwide', 'fullscreen')) ? 1 : 0;
			$ccc =  micro_office_get_custom_option('shop_loop_columns');
			$ccc = $ccc > 0 ? $ccc : (micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
			$class[] = ' column-1_'.esc_attr($ccc);
		}
		return $class;
	}
}

// Number columns for shop streampage
if ( !function_exists( 'micro_office_woocommerce_loop_shop_columns' ) ) {
	
	function micro_office_woocommerce_loop_shop_columns($cols) {
		$ccc_add = in_array('wide', array('fullwide', 'fullscreen')) ? 1 : 0;
		$ccc =  micro_office_get_custom_option('shop_loop_columns');
		$ccc = $ccc > 0 ? $ccc : (micro_office_param_is_off(micro_office_get_custom_option('show_sidebar_main')) ? 3+$ccc_add : 2+$ccc_add);
		return $ccc;
	}
}

// Search form
if ( !function_exists( 'micro_office_woocommerce_get_product_search_form' ) ) {
	
	function micro_office_woocommerce_get_product_search_form($form) {
		return '
		<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
			<input type="text" class="search_field" placeholder="' . esc_attr__('Search for products &hellip;', 'micro-office') . '" value="' . get_search_query() . '" name="s" title="' . esc_attr__('Search for products:', 'micro-office') . '" /><button class="search_button icon-search-1" type="submit"></button>
			<input type="hidden" name="post_type" value="product" />
		</form>
		';
	}
}

// Wrap product title into link
if ( !function_exists( 'micro_office_woocommerce_the_title' ) ) {
	
	function micro_office_woocommerce_the_title($title) {
		if (micro_office_storage_get('in_product_item') && get_post_type()=='product') {
			$title = '<a href="'.get_permalink().'">'.($title).'</a>';
		}
		return $title;
	}
}

// Show pagination links
if ( !function_exists( 'micro_office_woocommerce_pagination' ) ) {
	
	function micro_office_woocommerce_pagination() {
		$style = micro_office_get_custom_option('blog_pagination');
		micro_office_show_pagination(array(
			'class' => 'pagination_wrap pagination_' . esc_attr($style),
			'style' => $style,
			'button_class' => '',
			'first_text'=> '',
			'last_text' => '',
			'prev_text' => '',
			'next_text' => '',
			'pages_in_group' => $style=='pages' ? 10 : 20
			)
		);
	}
}

// Filter to add in the required plugins list
if ( !function_exists( 'micro_office_woocommerce_required_plugins' ) ) {
	
	function micro_office_woocommerce_required_plugins($list=array()) {
		if (in_array('woocommerce', micro_office_storage_get('required_plugins')))
			$list[] = array(
					'name' 		=> 'WooCommerce',
					'slug' 		=> 'woocommerce',
					'required' 	=> false
				);

		return $list;
	}
}

// Show products navigation
if ( !function_exists( 'micro_office_woocommerce_show_post_navi' ) ) {
	
	function micro_office_woocommerce_show_post_navi($show=false) {
		return $show || (micro_office_get_custom_option('show_page_title')=='yes' && is_single() && micro_office_is_woocommerce_page());
	}
}



// One-click import support
//------------------------------------------------------------------------

// Check WooC in the required plugins
if ( !function_exists( 'micro_office_woocommerce_importer_required_plugins' ) ) {
	
	function micro_office_woocommerce_importer_required_plugins($not_installed='', $list='') {
		if (micro_office_strpos($list, 'woocommerce')!==false && !micro_office_exists_woocommerce() )
			$not_installed .= '<br>' . esc_html__('WooCommerce', 'micro-office');
		return $not_installed;
	}
}

// Set options for one-click importer
if ( !function_exists( 'micro_office_woocommerce_importer_set_options' ) ) {
	
	function micro_office_woocommerce_importer_set_options($options=array()) {
		if ( in_array('woocommerce', micro_office_storage_get('required_plugins')) && micro_office_exists_woocommerce() ) {
			if (is_array($options['files']) && count($options['files']) > 0) {
				foreach ($options['files'] as $k => $v) {
					$options['files'][$k]['file_with_woocommerce'] = str_replace('name.ext', 'woocommerce.txt', $v['file_with_']);
				}
			}
			// Add slugs to export options for this plugin
			$options['additional_options'][]	= 'shop_%';
			$options['additional_options'][]	= 'woocommerce_%';
		}
		return $options;
	}
}

// Setup WooC pages after import posts complete
if ( !function_exists( 'micro_office_woocommerce_importer_after_import_posts' ) ) {
	
	function micro_office_woocommerce_importer_after_import_posts($importer) {
		$wooc_pages = array(						// Options slugs and pages titles for WooCommerce pages
			'woocommerce_shop_page_id' 				=> 'Shop',
			'woocommerce_cart_page_id' 				=> 'Cart',
			'woocommerce_checkout_page_id' 			=> 'Checkout',
			'woocommerce_pay_page_id' 				=> 'Checkout &#8594; Pay',
			'woocommerce_thanks_page_id' 			=> 'Order Received',
			'woocommerce_myaccount_page_id' 		=> 'My Account',
			'woocommerce_edit_address_page_id'		=> 'Edit My Address',
			'woocommerce_view_order_page_id'		=> 'View Order',
			'woocommerce_change_password_page_id'	=> 'Change Password',
			'woocommerce_logout_page_id'			=> 'Logout',
			'woocommerce_lost_password_page_id'		=> 'Lost Password'
		);
		foreach ($wooc_pages as $woo_page_name => $woo_page_title) {
			$woopage = get_page_by_title( $woo_page_title );
			if ($woopage->ID) {
				update_option($woo_page_name, $woopage->ID);
			}
		}
		// We no longer need to install pages
		delete_option( '_wc_needs_pages' );
		delete_transient( '_wc_activation_redirect' );
	}
}

// Add checkbox to the one-click importer
if ( !function_exists( 'micro_office_woocommerce_importer_show_params' ) ) {
	
	function micro_office_woocommerce_importer_show_params($importer) {
		$importer->show_importer_params(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('Import WooCommerce', 'micro-office'),
			'part' => 0
			));
	}
}

// Import posts
if ( !function_exists( 'micro_office_woocommerce_importer_import' ) ) {
	
	function micro_office_woocommerce_importer_import($importer, $action) {
		if ( $action == 'import_woocommerce' ) {
			$importer->response['result'] = $importer->import_dump('woocommerce', esc_html__('WooCommerce meta', 'micro-office'));
			delete_transient( 'wc_attribute_taxonomies' );
		}
	}
}

// Display import progress
if ( !function_exists( 'micro_office_woocommerce_importer_import_fields' ) ) {
	
	function micro_office_woocommerce_importer_import_fields($importer) {
		$importer->show_importer_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce meta', 'micro-office')
			));
	}
}

// Export posts
if ( !function_exists( 'micro_office_woocommerce_importer_export' ) ) {
	
	function micro_office_woocommerce_importer_export($importer) {
		micro_office_fpc(micro_office_get_file_dir('core/core.importer/export/woocommerce.txt'), serialize( array(
			"woocommerce_attribute_taxonomies"				=> $importer->export_dump("woocommerce_attribute_taxonomies"),
			"woocommerce_downloadable_product_permissions"	=> $importer->export_dump("woocommerce_downloadable_product_permissions"),
            "woocommerce_order_itemmeta"					=> $importer->export_dump("woocommerce_order_itemmeta"),
            "woocommerce_order_items"						=> $importer->export_dump("woocommerce_order_items"),
            "woocommerce_termmeta"							=> $importer->export_dump("woocommerce_termmeta")
            ) )
        );
	}
}

// Display exported data in the fields
if ( !function_exists( 'micro_office_woocommerce_importer_export_fields' ) ) {
	
	function micro_office_woocommerce_importer_export_fields($importer) {
		$importer->show_exporter_fields(array(
			'slug' => 'woocommerce',
			'title' => esc_html__('WooCommerce', 'micro-office')
			));
	}
}



// Register shortcodes to the internal builder
//------------------------------------------------------------------------
if ( !function_exists( 'micro_office_woocommerce_reg_shortcodes' ) ) {
	
	function micro_office_woocommerce_reg_shortcodes() {

		// WooCommerce - Cart
		micro_office_sc_map("woocommerce_cart", array(
			"title" => esc_html__("Woocommerce: Cart", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Cart page", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Checkout
		micro_office_sc_map("woocommerce_checkout", array(
			"title" => esc_html__("Woocommerce: Checkout", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Checkout page", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - My Account
		micro_office_sc_map("woocommerce_my_account", array(
			"title" => esc_html__("Woocommerce: My Account", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show My Account page", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Order Tracking
		micro_office_sc_map("woocommerce_order_tracking", array(
			"title" => esc_html__("Woocommerce: Order Tracking", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show Order Tracking page", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Shop Messages
		micro_office_sc_map("shop_messages", array(
			"title" => esc_html__("Woocommerce: Shop Messages", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show shop messages", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array()
			)
		);
		
		// WooCommerce - Product Page
		micro_office_sc_map("product_page", array(
			"title" => esc_html__("Woocommerce: Product Page", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display single product page", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", "micro-office"),
					"desc" => wp_kses_data( __("SKU code of displayed product", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", "micro-office"),
					"desc" => wp_kses_data( __("ID of displayed product", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"posts_per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => "1",
					"min" => 1,
					"type" => "spinner"
				),
				"post_type" => array(
					"title" => esc_html__("Post type", "micro-office"),
					"desc" => wp_kses_data( __("Post type for the WP query (leave 'product')", "micro-office") ),
					"value" => "product",
					"type" => "text"
				),
				"post_status" => array(
					"title" => esc_html__("Post status", "micro-office"),
					"desc" => wp_kses_data( __("Display posts only with this status", "micro-office") ),
					"value" => "publish",
					"type" => "select",
					"options" => array(
						"publish" => esc_html__('Publish', 'micro-office'),
						"protected" => esc_html__('Protected', 'micro-office'),
						"private" => esc_html__('Private', 'micro-office'),
						"pending" => esc_html__('Pending', 'micro-office'),
						"draft" => esc_html__('Draft', 'micro-office')
						)
					)
				)
			)
		);
		
		// WooCommerce - Product
		micro_office_sc_map("product", array(
			"title" => esc_html__("Woocommerce: Product", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: display one product", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"sku" => array(
					"title" => esc_html__("SKU", "micro-office"),
					"desc" => wp_kses_data( __("SKU code of displayed product", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"id" => array(
					"title" => esc_html__("ID", "micro-office"),
					"desc" => wp_kses_data( __("ID of displayed product", "micro-office") ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Best Selling Products
		micro_office_sc_map("best_selling_products", array(
			"title" => esc_html__("Woocommerce: Best Selling Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show best selling products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
					)
				)
			)
		);
		
		// WooCommerce - Recent Products
		micro_office_sc_map("recent_products", array(
			"title" => esc_html__("Woocommerce: Recent Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show recent products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Related Products
		micro_office_sc_map("related_products", array(
			"title" => esc_html__("Woocommerce: Related Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show related products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"posts_per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
						)
					)
				)
			)
		);
		
		// WooCommerce - Featured Products
		micro_office_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Featured Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show featured products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Top Rated Products
		micro_office_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Top Rated Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show top rated products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Sale Products
		micro_office_sc_map("featured_products", array(
			"title" => esc_html__("Woocommerce: Sale Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products on sale", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product Category
		micro_office_sc_map("product_category", array(
			"title" => esc_html__("Woocommerce: Products from category", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
				),
				"category" => array(
					"title" => esc_html__("Categories", "micro-office"),
					"desc" => wp_kses_data( __("Comma separated category slugs", "micro-office") ),
					"value" => '',
					"type" => "text"
				),
				"operator" => array(
					"title" => esc_html__("Operator", "micro-office"),
					"desc" => wp_kses_data( __("Categories operator", "micro-office") ),
					"value" => "IN",
					"type" => "checklist",
					"size" => "medium",
					"options" => array(
						"IN" => esc_html__('IN', 'micro-office'),
						"NOT IN" => esc_html__('NOT IN', 'micro-office'),
						"AND" => esc_html__('AND', 'micro-office')
						)
					)
				)
			)
		);
		
		// WooCommerce - Products
		micro_office_sc_map("products", array(
			"title" => esc_html__("Woocommerce: Products", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: list all products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"skus" => array(
					"title" => esc_html__("SKUs", "micro-office"),
					"desc" => wp_kses_data( __("Comma separated SKU codes of products", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", "micro-office"),
					"desc" => wp_kses_data( __("Comma separated ID of products", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
					)
				)
			)
		);
		
		// WooCommerce - Product attribute
		micro_office_sc_map("product_attribute", array(
			"title" => esc_html__("Woocommerce: Products by Attribute", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"per_page" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many products showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
				),
				"attribute" => array(
					"title" => esc_html__("Attribute", "micro-office"),
					"desc" => wp_kses_data( __("Attribute name", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"filter" => array(
					"title" => esc_html__("Filter", "micro-office"),
					"desc" => wp_kses_data( __("Attribute value", "micro-office") ),
					"value" => "",
					"type" => "text"
					)
				)
			)
		);
		
		// WooCommerce - Products Categories
		micro_office_sc_map("product_categories", array(
			"title" => esc_html__("Woocommerce: Product Categories", "micro-office"),
			"desc" => wp_kses_data( __("WooCommerce shortcode: show categories with products", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"number" => array(
					"title" => esc_html__("Number", "micro-office"),
					"desc" => wp_kses_data( __("How many categories showed", "micro-office") ),
					"value" => 4,
					"min" => 1,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns", "micro-office"),
					"desc" => wp_kses_data( __("How many columns per row use for categories output", "micro-office") ),
					"value" => 4,
					"min" => 2,
					"max" => 4,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Order by", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => array(
						"date" => esc_html__('Date', 'micro-office'),
						"title" => esc_html__('Title', 'micro-office')
					)
				),
				"order" => array(
					"title" => esc_html__("Order", "micro-office"),
					"desc" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
					"value" => "desc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
				),
				"parent" => array(
					"title" => esc_html__("Parent", "micro-office"),
					"desc" => wp_kses_data( __("Parent category slug", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"ids" => array(
					"title" => esc_html__("IDs", "micro-office"),
					"desc" => wp_kses_data( __("Comma separated ID of products", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"hide_empty" => array(
					"title" => esc_html__("Hide empty", "micro-office"),
					"desc" => wp_kses_data( __("Hide empty categories", "micro-office") ),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
					)
				)
			)
		);
	}
}



// Register shortcodes to the VC builder
//------------------------------------------------------------------------
if ( !function_exists( 'micro_office_woocommerce_reg_shortcodes_vc' ) ) {
	
	function micro_office_woocommerce_reg_shortcodes_vc() {
	
		if (false && function_exists('micro_office_exists_woocommerce') && micro_office_exists_woocommerce()) {
		
			// WooCommerce - Cart
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_cart",
				"name" => esc_html__("Cart", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show cart page", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_wooc_cart',
				"class" => "trx_sc_alone trx_sc_woocommerce_cart",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "micro-office"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Cart extends MICRO_OFFICE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Checkout
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_checkout",
				"name" => esc_html__("Checkout", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show checkout page", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_wooc_checkout',
				"class" => "trx_sc_alone trx_sc_woocommerce_checkout",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "micro-office"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Checkout extends MICRO_OFFICE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - My Account
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_my_account",
				"name" => esc_html__("My Account", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show my account page", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_wooc_my_account',
				"class" => "trx_sc_alone trx_sc_woocommerce_my_account",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "micro-office"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_My_Account extends MICRO_OFFICE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Order Tracking
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "woocommerce_order_tracking",
				"name" => esc_html__("Order Tracking", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show order tracking page", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_wooc_order_tracking',
				"class" => "trx_sc_alone trx_sc_woocommerce_order_tracking",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "micro-office"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Woocommerce_Order_Tracking extends MICRO_OFFICE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Shop Messages
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "shop_messages",
				"name" => esc_html__("Shop Messages", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show shop messages", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_wooc_shop_messages',
				"class" => "trx_sc_alone trx_sc_shop_messages",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => false,
				"params" => array(
					array(
						"param_name" => "dummy",
						"heading" => esc_html__("Dummy data", "micro-office"),
						"description" => wp_kses_data( __("Dummy data - not used in shortcodes", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Shop_Messages extends MICRO_OFFICE_VC_ShortCodeAlone {}
		
		
			// WooCommerce - Product Page
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_page",
				"name" => esc_html__("Product Page", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: display single product page", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_product_page',
				"class" => "trx_sc_single trx_sc_product_page",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", "micro-office"),
						"description" => wp_kses_data( __("SKU code of displayed product", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", "micro-office"),
						"description" => wp_kses_data( __("ID of displayed product", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_type",
						"heading" => esc_html__("Post type", "micro-office"),
						"description" => wp_kses_data( __("Post type for the WP query (leave 'product')", "micro-office") ),
						"class" => "",
						"value" => "product",
						"type" => "textfield"
					),
					array(
						"param_name" => "post_status",
						"heading" => esc_html__("Post status", "micro-office"),
						"description" => wp_kses_data( __("Display posts only with this status", "micro-office") ),
						"class" => "",
						"value" => array(
							esc_html__('Publish', 'micro-office') => 'publish',
							esc_html__('Protected', 'micro-office') => 'protected',
							esc_html__('Private', 'micro-office') => 'private',
							esc_html__('Pending', 'micro-office') => 'pending',
							esc_html__('Draft', 'micro-office') => 'draft'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Page extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product",
				"name" => esc_html__("Product", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: display one product", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_product',
				"class" => "trx_sc_single trx_sc_product",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "sku",
						"heading" => esc_html__("SKU", "micro-office"),
						"description" => wp_kses_data( __("Product's SKU code", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "id",
						"heading" => esc_html__("ID", "micro-office"),
						"description" => wp_kses_data( __("Product's ID", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
			// WooCommerce - Best Selling Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "best_selling_products",
				"name" => esc_html__("Best Selling Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show best selling products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_best_selling_products',
				"class" => "trx_sc_single trx_sc_best_selling_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Best_Selling_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Recent Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "recent_products",
				"name" => esc_html__("Recent Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show recent products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_recent_products',
				"class" => "trx_sc_single trx_sc_recent_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"

					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Recent_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Related Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "related_products",
				"name" => esc_html__("Related Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show related products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_related_products',
				"class" => "trx_sc_single trx_sc_related_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "posts_per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Related_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Featured Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "featured_products",
				"name" => esc_html__("Featured Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show featured products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_featured_products',
				"class" => "trx_sc_single trx_sc_featured_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Featured_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Top Rated Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "top_rated_products",
				"name" => esc_html__("Top Rated Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show top rated products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_top_rated_products',
				"class" => "trx_sc_single trx_sc_top_rated_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Top_Rated_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Sale Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "sale_products",
				"name" => esc_html__("Sale Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products on sale", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_sale_products',
				"class" => "trx_sc_single trx_sc_sale_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Sale_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Product Category
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_category",
				"name" => esc_html__("Products from category", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list products in specified category(-ies)", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_product_category',
				"class" => "trx_sc_single trx_sc_product_category",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "category",
						"heading" => esc_html__("Categories", "micro-office"),
						"description" => wp_kses_data( __("Comma separated category slugs", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "operator",
						"heading" => esc_html__("Operator", "micro-office"),
						"description" => wp_kses_data( __("Categories operator", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('IN', 'micro-office') => 'IN',
							esc_html__('NOT IN', 'micro-office') => 'NOT IN',
							esc_html__('AND', 'micro-office') => 'AND'
						),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Category extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "products",
				"name" => esc_html__("Products", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: list all products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_products',
				"class" => "trx_sc_single trx_sc_products",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "skus",
						"heading" => esc_html__("SKUs", "micro-office"),
						"description" => wp_kses_data( __("Comma separated SKU codes of products", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", "micro-office"),
						"description" => wp_kses_data( __("Comma separated ID of products", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					)
				)
			) );
			
			class WPBakeryShortCode_Products extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
		
			// WooCommerce - Product Attribute
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_attribute",
				"name" => esc_html__("Products by Attribute", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show products with specified attribute", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_product_attribute',
				"class" => "trx_sc_single trx_sc_product_attribute",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "per_page",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many products showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "attribute",
						"heading" => esc_html__("Attribute", "micro-office"),
						"description" => wp_kses_data( __("Attribute name", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "filter",
						"heading" => esc_html__("Filter", "micro-office"),
						"description" => wp_kses_data( __("Attribute value", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					)
				)
			) );
			
			class WPBakeryShortCode_Product_Attribute extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		
		
			// WooCommerce - Products Categories
			//-------------------------------------------------------------------------------------
			
			vc_map( array(
				"base" => "product_categories",
				"name" => esc_html__("Product Categories", "micro-office"),
				"description" => wp_kses_data( __("WooCommerce shortcode: show categories with products", "micro-office") ),
				"category" => esc_html__('WooCommerce', 'micro-office'),
				'icon' => 'icon_trx_product_categories',
				"class" => "trx_sc_single trx_sc_product_categories",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number", "micro-office"),
						"description" => wp_kses_data( __("How many categories showed", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "columns",
						"heading" => esc_html__("Columns", "micro-office"),
						"description" => wp_kses_data( __("How many columns per row use for categories output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "1",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Order by", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array(
							esc_html__('Date', 'micro-office') => 'date',
							esc_html__('Title', 'micro-office') => 'title'
						),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Order", "micro-office"),
						"description" => wp_kses_data( __("Sorting order for products output", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "parent",
						"heading" => esc_html__("Parent", "micro-office"),
						"description" => wp_kses_data( __("Parent category slug", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "date",
						"type" => "textfield"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("IDs", "micro-office"),
						"description" => wp_kses_data( __("Comma separated ID of products", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "hide_empty",
						"heading" => esc_html__("Hide empty", "micro-office"),
						"description" => wp_kses_data( __("Hide empty categories", "micro-office") ),
						"class" => "",
						"value" => array("Hide empty" => "1" ),
						"type" => "checkbox"
					)
				)
			) );
			
			class WPBakeryShortCode_Products_Categories extends MICRO_OFFICE_VC_ShortCodeSingle {}
		
		}
	}
}
?>