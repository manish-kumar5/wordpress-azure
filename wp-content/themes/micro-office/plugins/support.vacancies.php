<?php
/**
 * Micro Office Framework: Vacancies support
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Theme init
if (!function_exists('micro_office_vacancies_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_vacancies_theme_setup',1 );
	function micro_office_vacancies_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('micro_office_filter_get_blog_type',			'micro_office_vacancies_get_blog_type', 9, 2);
		add_filter('micro_office_filter_get_blog_title',		'micro_office_vacancies_get_blog_title', 9, 2);
		add_filter('micro_office_filter_get_current_taxonomy',	'micro_office_vacancies_get_current_taxonomy', 9, 2);
		add_filter('micro_office_filter_is_taxonomy',			'micro_office_vacancies_is_taxonomy', 9, 2);
		add_filter('micro_office_filter_get_stream_page_title',	'micro_office_vacancies_get_stream_page_title', 9, 2);
		add_filter('micro_office_filter_get_stream_page_link',	'micro_office_vacancies_get_stream_page_link', 9, 2);
		add_filter('micro_office_filter_get_stream_page_id',	'micro_office_vacancies_get_stream_page_id', 9, 2);
		add_filter('micro_office_filter_query_add_filters',		'micro_office_vacancies_query_add_filters', 9, 2);
		add_filter('micro_office_filter_detect_inheritance_key','micro_office_vacancies_detect_inheritance_key', 9, 1);

		// Extra column for vacancies lists
		if (micro_office_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-vacancies_columns',			'micro_office_post_add_options_column', 9);
			add_filter('manage_vacancies_posts_custom_column',	'micro_office_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_vacancies]
		add_action('micro_office_action_shortcodes_list',		'micro_office_vacancies_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_vacancies_reg_shortcodes_vc');
		
		// Add supported data types
		micro_office_theme_support_pt('vacancies');
		micro_office_theme_support_tx('vacancies_group');
	}
}

if ( !function_exists( 'micro_office_vacancies_settings_theme_setup2' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_vacancies_settings_theme_setup2', 3 );
	function micro_office_vacancies_settings_theme_setup2() {
		// Add post type 'vacancies' and taxonomy 'vacancies_group' into theme inheritance list
		micro_office_add_theme_inheritance( array('vacancies' => array(
			'stream_template' => 'blog-vacancies',
			'single_template' => 'single-vacancy',
			'taxonomy' => array('vacancies_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('vacancies'),
			'override' => 'custom'
			) )
		);
	}
}



if (!function_exists('micro_office_vacancies_after_theme_setup')) {
	add_action( 'micro_office_action_after_init_theme', 'micro_office_vacancies_after_theme_setup' );
	function micro_office_vacancies_after_theme_setup() {
		// Update fields in the meta box
		if (micro_office_storage_get_array('post_meta_box', 'page')=='vacancies') {
			// Meta box fields
			micro_office_storage_set_array('post_meta_box', 'title', esc_html__('Vacancy Options', 'micro-office'));
			micro_office_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_vacancy" => array(
					"title" => esc_html__('Vacancies', 'micro-office'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-doc-text-inv",
					"type" => "partition"),
				"mb_info_vacancy_1" => array(
					"title" => esc_html__('Vacancy details', 'micro-office'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => wp_kses_data( __('In this section you can put details for this vacancy', 'micro-office') ),
					"class" => "vacancy_meta",
					"type" => "info"),
				"vacancy_position" => array(
					"title" => __('Position',  'micro-office'),
					"desc" => __("Position of the vacancy item", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "vacancy_position",
					"std" => "",
					"type" => "text"),
				"vacancy_location" => array(
					"title" => __("Location",  'micro-office'),
					"desc" => __("Location of work (Ex: New York)", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "vacancy_location",
					"std" => "",
					"type" => "text"),
				"vacancy_employment" => array(
					"title" => __('Employment',  'micro-office'),
					"desc" => __("Choose one of options: Full time, Part time or Freelance", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "vacancy_employment",
					"std" => "above",
					"options" => array(
						'full'  => esc_html__('Full Time', 'micro-office'),
						'freelance' => esc_html__('Freelance', 'micro-office'),
						'part' => esc_html__('Part Time', 'micro-office')
					),
					"type" => "checklist"),
				"vacancy_salary" => array(
					"title" => __("Salary",  'micro-office'),
					"desc" => __("Write salary", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "vacancy_salary",
					"std" => "",
					"type" => "text")
				)
			);
		}
	}
}


// Return true, if current page is vacancies page
if ( !function_exists( 'micro_office_is_vacancies_page' ) ) {
	function micro_office_is_vacancies_page() {
		$is = in_array(micro_office_storage_get('page_template'), array('blog-vacancies', 'single-vacancy'));
		if (!$is) {
			if (!micro_office_storage_empty('pre_query'))
				$is = micro_office_storage_call_obj_method('pre_query', 'get', 'post_type')=='vacancies' 
						|| micro_office_storage_call_obj_method('pre_query', 'is_tax', 'vacancies_group') 
						|| (micro_office_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=micro_office_get_template_page_id('blog-vacancies')) > 0 
								&& $id==micro_office_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='vacancies' 
						|| is_tax('vacancies_group') 
						|| (is_page() && ($id=micro_office_get_template_page_id('blog-vacancies')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'micro_office_vacancies_detect_inheritance_key' ) ) {
	
	function micro_office_vacancies_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return micro_office_is_vacancies_page() ? 'vacancies' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'micro_office_vacancies_get_blog_type' ) ) {
	
	function micro_office_vacancies_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('vacancies_group') || is_tax('vacancies_group'))
			$page = 'vacancies_category';
		else if ($query && $query->get('post_type')=='vacancies' || get_query_var('post_type')=='vacancies')
			$page = $query && $query->is_single() || is_single() ? 'vacancies_item' : 'vacancies';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'micro_office_vacancies_get_blog_title' ) ) {
	
	function micro_office_vacancies_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( micro_office_strpos($page, 'vacancies')!==false ) {
			if ( $page == 'vacancies_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'vacancies_group' ), 'vacancies_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'vacancies_item' ) {
				$title = micro_office_get_post_title();
			} else {
				$title = esc_html__('All vacancies', 'micro-office');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'micro_office_vacancies_get_stream_page_title' ) ) {
	
	function micro_office_vacancies_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (micro_office_strpos($page, 'vacancies')!==false) {
			if (($page_id = micro_office_vacancies_get_stream_page_id(0, $page=='vacancies' ? 'blog-vacancies' : $page)) > 0)
				$title = micro_office_get_post_title($page_id);
			else
				$title = esc_html__('All vacancies', 'micro-office');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'micro_office_vacancies_get_stream_page_id' ) ) {
	
	function micro_office_vacancies_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (micro_office_strpos($page, 'vacancies')!==false) $id = micro_office_get_template_page_id('blog-vacancies');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'micro_office_vacancies_get_stream_page_link' ) ) {
	
	function micro_office_vacancies_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (micro_office_strpos($page, 'vacancies')!==false) {
			$id = micro_office_get_template_page_id('blog-vacancies');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'micro_office_vacancies_get_current_taxonomy' ) ) {
	
	function micro_office_vacancies_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( micro_office_strpos($page, 'vacancies')!==false ) {
			$tax = 'vacancies_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'micro_office_vacancies_is_taxonomy' ) ) {
	
	function micro_office_vacancies_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('vacancies_group')!='' || is_tax('vacancies_group') ? 'vacancies_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'micro_office_vacancies_query_add_filters' ) ) {
	
	function micro_office_vacancies_query_add_filters($args, $filter) {
		if ($filter == 'vacancies') {
			$args['post_type'] = 'vacancies';
		}
		return $args;
	}
}





// ---------------------------------- [trx_vacancies] ---------------------------------------


if ( !function_exists( 'micro_office_sc_vacancies' ) ) {
	function micro_office_sc_vacancies($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "vacancies-1",
			"ids" => "",
			"cat" => "",
			"count" => 10,
			"offset" => "",
			"align" => "",
			"orderby" => "date",
			"order" => "desc",
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		
		
		$vacancy_category = '';
		$vacancy_location = '';

		if (isset($_POST)) {
		  if (isset($_POST["category"])){
			$vacancy_category = $_POST['category'];
		  }
		   if (isset($_POST["location"])){
			$vacancy_location = $_POST['location'];
		  }
		}

		
		if (empty($id)) $id = "sc_vacancies_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$count = max(1, (int) $count);
		
		$options = '<option value="">Any Category</option>';
		$terms   = get_terms('vacancies_group', array('fields' => 'all'));
		foreach ($terms as $term) {
			$options .= '<option value="' . $term->name . '">' . $term->name . '</option>';
		}
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_vacancies_wrap'
						. ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<form method="POST" class="vacancies_search_form">'
						.'<input type="text" class="vacancies_search_field" placeholder="'. esc_html__("All locations", "micro-office") .'" value="" name="location">'
						.'<select class="vacancies_categories_filter" name="category">'
							.$options
						.'</select>'
						.'<input type="submit" value="'. esc_html__("Search", "micro-office") .'" class="vacancies_search_submit">'
					.'</form>'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_vacancies sc_table'
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '>'
					. '<table><thead><tr><td>'. esc_html__("Schedule", "micro-office") .'</td><td></td><td></td><td></td></tr></thead>'
					. '<tbody>'
					.'<tr><td>'. esc_html__("Position", "micro-office") .'</td><td>'. esc_html__("Location", "micro-office") .'</td><td>'. esc_html__("Deadline", "micro-office") .'</td><td>'. esc_html__("Salary", "micro-office") .'</td></tr>';
	

		global $post;

		if (!empty($ids)) {
			$posts = explode(',', $ids);
			$count = count($posts);
		}
		
		$args = array(
			'post_type' => 'vacancies',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc'
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}	
		
		$args = micro_office_query_add_sort_order($args, $orderby, $order);
		$args = micro_office_query_add_posts_and_cats($args, $ids, 'vacancies', ($vacancy_category != '' ? $vacancy_category : $cat), 'vacancies_group');
		
		$query = new WP_Query( $args );

		$post_number = 0;
			
		while ( $query->have_posts() ) { 
			$query->the_post();
			$post_number++;
			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => $post_number,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				"orderby" => $orderby,
				'content' => false,
				'terms_list' => false,
				'tag_id' => $id ? $id . '_' . $post_number : '',
				'tag_class' => '',
				'tag_animation' => '',
				'tag_css' => ''
			);
			$post_data = micro_office_get_post_data($args);
			$post_meta = get_post_meta($post_data['post_id'], micro_office_storage_get('options_prefix') . '_post_options', true);
			$args['vacancy_position'] = (!empty($post_meta['vacancy_position']) ? $post_meta['vacancy_position'] : '');
			$args['vacancy_location'] = (!empty($post_meta['vacancy_location']) ? $post_meta['vacancy_location'] : '');
			$args['vacancy_employment'] = (!empty($post_meta['vacancy_employment']) ? $post_meta['vacancy_employment'] : '');
			$args['vacancy_salary'] = (!empty($post_meta['vacancy_salary']) ? $post_meta['vacancy_salary'] : '');
			$args['vacancy_link'] = (!empty($post_meta['vacancy_link']) ? $post_meta['vacancy_link'] : $post_data['post_link']);
			if($vacancy_location == '' || $vacancy_location != '' && strtolower($vacancy_location) == strtolower($post_meta['vacancy_location'])) 
				$output .= micro_office_show_post_layout($args);
		}
		wp_reset_postdata();		

		$output .=  '</tbody></table></div><!-- /.sc_vacancies -->'
				. '</div><!-- /.sc_vacancies_wrap -->';
		
		return apply_filters('micro_office_shortcode_output', $output, 'trx_vacancies', $atts, $content);
	}
	micro_office_require_shortcode('trx_vacancies', 'micro_office_sc_vacancies');
}
// ---------------------------------- [/trx_vacancies] ---------------------------------------



// Add [trx_vacancies] in the shortcodes list
if (!function_exists('micro_office_vacancies_reg_shortcodes')) {
	function micro_office_vacancies_reg_shortcodes() {
		if (micro_office_storage_isset('shortcodes')) {

			$vacancies_groups = micro_office_get_list_terms(false, 'vacancies_group');

			micro_office_sc_map_after('trx_section', array(

				// Vacancies
				"trx_vacancies" => array(
					"title" => esc_html__("Vacancies", "micro-office"),
					"desc" => wp_kses_data( __("Insert vacancies list in your page (post)", "micro-office") ),
					"decorate" => true,
					"container" => false,
					"params" => array(
						"scheme" => array(
							"title" => esc_html__("Color scheme", "micro-office"),
							"desc" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
							"value" => "",
							"type" => "checklist",
							"options" => micro_office_get_sc_param('schemes')
						),
						"align" => array(
							"title" => esc_html__("Alignment", "micro-office"),
							"desc" => wp_kses_data( __("Alignment of the vacancies block", "micro-office") ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => micro_office_get_sc_param('align')
						),
						"cat" => array(
							"title" => esc_html__("Categories", "micro-office"),
							"desc" => wp_kses_data( __("Select categories (groups) to show vacancies list. If empty - select vacancies from any category (group) or from IDs list", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), $vacancies_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "micro-office"),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 10,
							"min" => 1,
							"max" => 100,
							"type" => "spinner"
						),
						"offset" => array(
							"title" => esc_html__("Offset before select posts", "micro-office"),
							"desc" => wp_kses_data( __("Skip posts before select next part.", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 0,
							"min" => 0,
							"type" => "spinner"
						),
						"orderby" => array(
							"title" => esc_html__("Post order by", "micro-office"),
							"desc" => wp_kses_data( __("Select desired posts sorting method", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "date",
							"type" => "select",
							"options" => micro_office_get_sc_param('sorting')
						),
						"order" => array(
							"title" => esc_html__("Post order", "micro-office"),
							"desc" => wp_kses_data( __("Select desired posts order", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "desc",
							"type" => "switch",
							"size" => "big",
							"options" => micro_office_get_sc_param('ordering')
						),
						"ids" => array(
							"title" => esc_html__("Post IDs list", "micro-office"),
							"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => "",
							"type" => "text"
						),
						"width" => micro_office_shortcodes_width(),
						"top" => micro_office_get_sc_param('top'),
						"bottom" => micro_office_get_sc_param('bottom'),
						"left" => micro_office_get_sc_param('left'),
						"right" => micro_office_get_sc_param('right'),
						"id" => micro_office_get_sc_param('id'),
						"class" => micro_office_get_sc_param('class'),
						"animation" => micro_office_get_sc_param('animation'),
						"css" => micro_office_get_sc_param('css')
					)
				)
			));
		}
	}
}


// Add [trx_vacancies] in the VC shortcodes list
if (!function_exists('micro_office_vacancies_reg_shortcodes_vc')) {
	function micro_office_vacancies_reg_shortcodes_vc() {

		$vacancies_groups = micro_office_get_list_terms(false, 'vacancies_group');

		// Vacancies
		vc_map( array(
				"base" => "trx_vacancies",
				"name" => esc_html__("Vacancies", "micro-office"),
				"description" => wp_kses_data( __("Insert vacancies list", "micro-office") ),
				"category" => esc_html__('Content', 'micro-office'),
				"icon" => 'icon_trx_vacancies',
				"class" => "trx_sc_columns trx_sc_vacancies",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "scheme",
						"heading" => esc_html__("Color scheme", "micro-office"),
						"description" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('schemes')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "align",
						"heading" => esc_html__("Alignment", "micro-office"),
						"description" => wp_kses_data( __("Alignment of the vacancies block", "micro-office") ),
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "micro-office"),
						"description" => wp_kses_data( __("Select category to show vacancies. If empty - select vacancies from any category (group) or from IDs list", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), $vacancies_groups)),
						"type" => "dropdown"
					),
					array(
						"param_name" => "count",
						"heading" => esc_html__("Number of posts", "micro-office"),
						"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "micro-office") ),
						"admin_label" => true,
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "10",
						"type" => "textfield"
					),
					array(
						"param_name" => "offset",
						"heading" => esc_html__("Offset before select posts", "micro-office"),
						"description" => wp_kses_data( __("Skip posts before select next part.", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "0",
						"type" => "textfield"
					),
					array(
						"param_name" => "orderby",
						"heading" => esc_html__("Post sorting", "micro-office"),
						"description" => wp_kses_data( __("Select desired posts sorting method", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "date",
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('sorting')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "order",
						"heading" => esc_html__("Post order", "micro-office"),
						"description" => wp_kses_data( __("Select desired posts order", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"std" => "desc",
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('ordering')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "ids",
						"heading" => esc_html__("Service's IDs list", "micro-office"),
						"description" => wp_kses_data( __("Comma separated list of vacancy's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					micro_office_vc_width(),
					micro_office_get_vc_param('margin_top'),
					micro_office_get_vc_param('margin_bottom'),
					micro_office_get_vc_param('margin_left'),
					micro_office_get_vc_param('margin_right'),
					micro_office_get_vc_param('id'),
					micro_office_get_vc_param('class'),
					micro_office_get_vc_param('animation'),
					micro_office_get_vc_param('css')
				)
			) );
			
			
			
		class WPBakeryShortCode_Trx_Vacancies extends MICRO_OFFICE_VC_ShortCodeSingle {}

	}
}
?>