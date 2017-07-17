<?php
/**
 * Micro Office Framework: Projects support
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Theme init
if (!function_exists('micro_office_projects_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_projects_theme_setup',1 );
	function micro_office_projects_theme_setup() {
		
		// Detect current page type, taxonomy and title (for custom post_types use priority < 10 to fire it handles early, than for standard post types)
		add_filter('micro_office_filter_get_blog_type',			'micro_office_projects_get_blog_type', 9, 2);
		add_filter('micro_office_filter_get_blog_title',		'micro_office_projects_get_blog_title', 9, 2);
		add_filter('micro_office_filter_get_current_taxonomy',	'micro_office_projects_get_current_taxonomy', 9, 2);
		add_filter('micro_office_filter_is_taxonomy',			'micro_office_projects_is_taxonomy', 9, 2);
		add_filter('micro_office_filter_get_stream_page_title',	'micro_office_projects_get_stream_page_title', 9, 2);
		add_filter('micro_office_filter_get_stream_page_link',	'micro_office_projects_get_stream_page_link', 9, 2);
		add_filter('micro_office_filter_get_stream_page_id',	'micro_office_projects_get_stream_page_id', 9, 2);
		add_filter('micro_office_filter_query_add_filters',		'micro_office_projects_query_add_filters', 9, 2);
		add_filter('micro_office_filter_detect_inheritance_key','micro_office_projects_detect_inheritance_key', 9, 1);

		// Extra column for projects lists
		if (micro_office_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-projects_columns',			'micro_office_post_add_options_column', 9);
			add_filter('manage_projects_posts_custom_column',	'micro_office_post_fill_options_column', 9, 2);
		}

		// Register shortcodes [trx_projects] and [trx_projects_item]
		add_action('micro_office_action_shortcodes_list',		'micro_office_projects_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_projects_reg_shortcodes_vc');
		
		// Add supported data types
		micro_office_theme_support_pt('projects');
		micro_office_theme_support_tx('projects_group');
	}
}

if ( !function_exists( 'micro_office_projects_settings_theme_setup2' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_projects_settings_theme_setup2', 3 );
	function micro_office_projects_settings_theme_setup2() {
		// Add post type 'projects' and taxonomy 'projects_group' into theme inheritance list
		micro_office_add_theme_inheritance( array('projects' => array(
			'stream_template' => 'blog-projects',
			'single_template' => 'single-project',
			'taxonomy' => array('projects_group'),
			'taxonomy_tags' => array(),
			'post_type' => array('projects'),
			'override' => 'custom'
			) )
		);
	}
}



if (!function_exists('micro_office_projects_after_theme_setup')) {
	add_action( 'micro_office_action_after_init_theme', 'micro_office_projects_after_theme_setup' );
	function micro_office_projects_after_theme_setup() {
		// Update fields in the meta box
		if (micro_office_storage_get_array('post_meta_box', 'page')=='projects') {
			// Meta box fields
			micro_office_storage_set_array('post_meta_box', 'title', esc_html__('Project Options', 'micro-office'));
			micro_office_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_projects" => array(
					"title" => esc_html__('Projects', 'micro-office'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-doc-text-inv",
					"type" => "partition"),
				"start_date" => array(
					"title" => __('Start date',  'micro-office'),
					"desc" => __("Project start date", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "project_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"finish_date" => array(
					"title" => __('Finish date',  'micro-office'),
					"desc" => __("Project finish date", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "project_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date")
				)
			);
		}
	}
}


// Return true, if current page is projects page
if ( !function_exists( 'micro_office_is_projects_page' ) ) {
	function micro_office_is_projects_page() {
		$is = in_array(micro_office_storage_get('page_template'), array('blog-projects', 'single-project'));
		if (!$is) {
			if (!micro_office_storage_empty('pre_query'))
				$is = micro_office_storage_call_obj_method('pre_query', 'get', 'post_type')=='projects' 
						|| micro_office_storage_call_obj_method('pre_query', 'is_tax', 'projects_group') 
						|| (micro_office_storage_call_obj_method('pre_query', 'is_page') 
								&& ($id=micro_office_get_template_page_id('blog-projects')) > 0 
								&& $id==micro_office_storage_get_obj_property('pre_query', 'queried_object_id', 0) 
							);
			else
				$is = get_query_var('post_type')=='projects' 
						|| is_tax('projects_group') 
						|| (is_page() && ($id=micro_office_get_template_page_id('blog-projects')) > 0 && $id==get_the_ID());
		}
		return $is;
	}
}

// Filter to detect current page inheritance key
if ( !function_exists( 'micro_office_projects_detect_inheritance_key' ) ) {
	
	function micro_office_projects_detect_inheritance_key($key) {
		if (!empty($key)) return $key;
		return micro_office_is_projects_page() ? 'projects' : '';
	}
}

// Filter to detect current page slug
if ( !function_exists( 'micro_office_projects_get_blog_type' ) ) {
	
	function micro_office_projects_get_blog_type($page, $query=null) {
		if (!empty($page)) return $page;
		if ($query && $query->is_tax('projects_group') || is_tax('projects_group'))
			$page = 'projects_category';
		else if ($query && $query->get('post_type')=='projects' || get_query_var('post_type')=='projects')
			$page = $query && $query->is_single() || is_single() ? 'projects_item' : 'projects';
		return $page;
	}
}

// Filter to detect current page title
if ( !function_exists( 'micro_office_projects_get_blog_title' ) ) {
	
	function micro_office_projects_get_blog_title($title, $page) {
		if (!empty($title)) return $title;
		if ( micro_office_strpos($page, 'projects')!==false ) {
			if ( $page == 'projects_category' ) {
				$term = get_term_by( 'slug', get_query_var( 'projects_group' ), 'projects_group', OBJECT);
				$title = $term->name;
			} else if ( $page == 'projects_item' ) {
				$title = micro_office_get_post_title();
			} else {
				$title = esc_html__('All projects', 'micro-office');
			}
		}
		return $title;
	}
}

// Filter to detect stream page title
if ( !function_exists( 'micro_office_projects_get_stream_page_title' ) ) {
	
	function micro_office_projects_get_stream_page_title($title, $page) {
		if (!empty($title)) return $title;
		if (micro_office_strpos($page, 'projects')!==false) {
			if (($page_id = micro_office_projects_get_stream_page_id(0, $page=='projects' ? 'blog-projects' : $page)) > 0)
				$title = micro_office_get_post_title($page_id);
			else
				$title = esc_html__('All projects', 'micro-office');				
		}
		return $title;
	}
}

// Filter to detect stream page ID
if ( !function_exists( 'micro_office_projects_get_stream_page_id' ) ) {
	
	function micro_office_projects_get_stream_page_id($id, $page) {
		if (!empty($id)) return $id;
		if (micro_office_strpos($page, 'projects')!==false) $id = micro_office_get_template_page_id('blog-projects');
		return $id;
	}
}

// Filter to detect stream page URL
if ( !function_exists( 'micro_office_projects_get_stream_page_link' ) ) {
	
	function micro_office_projects_get_stream_page_link($url, $page) {
		if (!empty($url)) return $url;
		if (micro_office_strpos($page, 'projects')!==false) {
			$id = micro_office_get_template_page_id('blog-projects');
			if ($id) $url = get_permalink($id);
		}
		return $url;
	}
}

// Filter to detect current taxonomy
if ( !function_exists( 'micro_office_projects_get_current_taxonomy' ) ) {
	
	function micro_office_projects_get_current_taxonomy($tax, $page) {
		if (!empty($tax)) return $tax;
		if ( micro_office_strpos($page, 'projects')!==false ) {
			$tax = 'projects_group';
		}
		return $tax;
	}
}

// Return taxonomy name (slug) if current page is this taxonomy page
if ( !function_exists( 'micro_office_projects_is_taxonomy' ) ) {
	
	function micro_office_projects_is_taxonomy($tax, $query=null) {
		if (!empty($tax))
			return $tax;
		else 
			return $query && $query->get('projects_group')!='' || is_tax('projects_group') ? 'projects_group' : '';
	}
}

// Add custom post type and/or taxonomies arguments to the query
if ( !function_exists( 'micro_office_projects_query_add_filters' ) ) {
	
	function micro_office_projects_query_add_filters($args, $filter) {
		if ($filter == 'projects') {
			$args['post_type'] = 'projects';
		}
		return $args;
	}
}





// ---------------------------------- [trx_projects] ---------------------------------------


if ( !function_exists( 'micro_office_sc_projects' ) ) {
	function micro_office_sc_projects($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "projects-1",
			"ids" => "",
			"cat" => "",
			"count" => 4,
			"offset" => "",
			"align" => "",
			"orderby" => "date",
			"order" => "desc",
			"descr" => "",
			"scheme" => '',
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));

		if (empty($id)) $id = "sc_projects_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$count = max(1, (int) $count);


		micro_office_storage_set('sc_projects_data', array(
			'id' => $id,
			'style' => $style,
            )
        );
		
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'_wrap"' : '') 
						. ' class="sc_projects_wrap'
						. ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
						.'">'
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_projects'
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. ($align!='' && $align!='none' ? ' align'.esc_attr($align) : '')
							. '"'
						. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
						. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '>';
	

		global $post;

		if (!empty($ids)) {
			$posts = explode(',', $ids);
			$count = count($posts);
		}
		
		$args = array(
			'post_type' => 'projects',
			'post_status' => 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc'
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}
	
		$args = micro_office_query_add_sort_order($args, $orderby, $order);
		$args = micro_office_query_add_posts_and_cats($args, $ids, 'projects', $cat, 'projects_group');
		
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
				"descr" => (!empty($descr) ? $descr : micro_office_get_custom_option('post_excerpt_maxlength')),
				"orderby" => $orderby,
				'content' => false,
				'terms_list' => false,
				'tag_id' => $id ? $id . '_' . $post_number : '',
				'tag_class' => '',
				'tag_animation' => '',
				'tag_css' => ''
			);
			$output .= micro_office_show_post_layout($args);
		}
		wp_reset_postdata();		

		$output .=  '</div><!-- /.sc_projects -->'
				. '</div><!-- /.sc_projects_wrap -->';
		
		return apply_filters('micro_office_shortcode_output', $output, 'trx_projects', $atts, $content);
	}
	micro_office_require_shortcode('trx_projects', 'micro_office_sc_projects');
}
// ---------------------------------- [/trx_projects] ---------------------------------------



// Add [trx_projects] in the shortcodes list
if (!function_exists('micro_office_projects_reg_shortcodes')) {
	function micro_office_projects_reg_shortcodes() {
		if (micro_office_storage_isset('shortcodes')) {

			$projects_groups = micro_office_get_list_terms(false, 'projects_group');

			micro_office_sc_map_after('trx_section', array(

				// Projects
				"trx_projects" => array(
					"title" => esc_html__("Projects", "micro-office"),
					"desc" => wp_kses_data( __("Insert projects list in your page (post)", "micro-office") ),
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
							"desc" => wp_kses_data( __("Alignment of the projects block", "micro-office") ),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => micro_office_get_sc_param('align')
						),
						"descr" => array(
							"title" => esc_html__("Description length", "micro-office"),
							"desc" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", "micro-office") ),
							"value" => 0,
							"min" => 0,
							"step" => 10,
							"type" => "spinner"
						),
						"cat" => array(
							"title" => esc_html__("Categories", "micro-office"),
							"desc" => wp_kses_data( __("Select categories (groups) to show projects list. If empty - select projects from any category (group) or from IDs list", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"divider" => true,
							"value" => "",
							"type" => "select",
							"style" => "list",
							"multiple" => true,
							"options" => micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), $projects_groups)
						),
						"count" => array(
							"title" => esc_html__("Number of posts", "micro-office"),
							"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "micro-office") ),
							"dependency" => array(
								'custom' => array('no')
							),
							"value" => 4,
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
						"height" => micro_office_shortcodes_height(),
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


// Add [trx_projects] in the VC shortcodes list
if (!function_exists('micro_office_projects_reg_shortcodes_vc')) {
	function micro_office_projects_reg_shortcodes_vc() {

		$projects_groups = micro_office_get_list_terms(false, 'projects_group');

		// Projects
		vc_map( array(
				"base" => "trx_projects",
				"name" => esc_html__("Projects", "micro-office"),
				"description" => wp_kses_data( __("Insert projects list", "micro-office") ),
				"category" => esc_html__('Content', 'micro-office'),
				"icon" => 'icon_trx_projects',
				"class" => "trx_sc_columns trx_sc_projects",
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
						"description" => wp_kses_data( __("Alignment of the projects block", "micro-office") ),
						"class" => "",
						"value" => array_flip(micro_office_get_sc_param('align')),
						"type" => "dropdown"
					),
					array(
						"param_name" => "descr",
						"heading" => esc_html__("Description length", "micro-office"),
						"description" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", "micro-office") ),
						"class" => "",
						"value" => 0,
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Categories", "micro-office"),
						"description" => wp_kses_data( __("Select category to show projects. If empty - select projects from any category (group) or from IDs list", "micro-office") ),
						"group" => esc_html__('Query', 'micro-office'),
						'dependency' => array(
							'element' => 'custom',
							'is_empty' => true
						),
						"class" => "",
						"value" => array_flip(micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), $projects_groups)),
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
						"value" => "4",
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
						"description" => wp_kses_data( __("Comma separated list of project's ID. If set - parameters above (category, count, order, etc.)  are ignored!", "micro-office") ),
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
					micro_office_vc_height(),
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
			
			
			
		class WPBakeryShortCode_Trx_Projects extends MICRO_OFFICE_VC_ShortCodeSingle {}

	}
}
?>