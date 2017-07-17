<?php
/**
 * Micro Office Framework: Lesson support
 *
 * @package	micro_office
 * @since	micro_office 1.0
 */

// Theme init
if (!function_exists('micro_office_lesson_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_lesson_theme_setup', 1 );
	function micro_office_lesson_theme_setup() {

		// Add categories (taxonomies) filter for custom posts types
		add_action( 'restrict_manage_posts','micro_office_lesson_show_courses_combo' );
		add_filter( 'pre_get_posts', 		'micro_office_lesson_add_parent_course_in_query' );

		// Extra column for lessons lists with overriden Theme Options
		if (micro_office_get_theme_option('show_overriden_posts')=='yes') {
			add_filter('manage_edit-lesson_columns',		'micro_office_post_add_options_column', 9);
			add_filter('manage_lesson_posts_custom_column',	'micro_office_post_fill_options_column', 9, 2);
		}
		// Extra column for lessons lists with parent course name
		add_filter('manage_edit-lesson_columns',		'micro_office_lesson_add_options_column', 9);
		add_filter('manage_lesson_posts_custom_column',	'micro_office_lesson_fill_options_column', 9, 2);

		// Register shortcode [trx_lessons] in the shortcodes list
		add_action('micro_office_action_shortcodes_list',		'micro_office_lesson_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_lesson_reg_shortcodes_vc');
		
		// Add supported data types
		micro_office_theme_support_pt('lesson');
	}
}

if (!function_exists('micro_office_lesson_theme_setup2')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_lesson_theme_setup2' );
	function micro_office_lesson_theme_setup2() {

		// Add post specific actions and filters
		if (micro_office_storage_get_array('post_meta_box', 'page')=='lesson') {
			add_filter('micro_office_filter_post_save_custom_options',		'micro_office_lesson_save_custom_options', 10, 3);
		}
	}
}



/* Extra column for lessons list
-------------------------------------------------------------------------------------------- */

// Create additional column
if (!function_exists('micro_office_lesson_add_options_column')) {
	
	function micro_office_lesson_add_options_column( $columns ) {
		micro_office_array_insert_after( $columns, 'title', array('course_title' => __('Course', 'micro-office')) );
		return $columns;
	}
}

// Fill column with data
if (!function_exists('micro_office_lesson_fill_options_column')) {
	
	function micro_office_lesson_fill_options_column($column_name='', $post_id=0) {
		if ($column_name != 'course_title') return;
		if (($parent_id = get_post_meta($post_id, micro_office_storage_get('options_prefix').'_parent_course', true)) > 0) {
			$parent_title = get_the_title($parent_id);
			echo '<a href="#" class="micro_office_course_selector" data-parent_id="'.intval($parent_id).'" title="'.esc_attr(__('Leave only lessons of this course', 'micro-office')).'">' . strip_tags($parent_title) . '</a>';
		}
	}
}


/* Display filter for lessons by courses
-------------------------------------------------------------------------------------------- */

// Display filter combobox
if (!function_exists('micro_office_lesson_show_courses_combo')) {
	
	function micro_office_lesson_show_courses_combo() {
		$page = get_query_var('post_type');
		if ($page != 'lesson') return;
		$courses = micro_office_get_list_posts(false, array(
					'post_type' => 'courses',
					'orderby' => 'title',
					'order' => 'asc'
					)
		);
		$list = '';
		if (count($courses) > 0) {
			$slug = 'parent_course';
			$list .= '<label class="screen-reader-text filter_label" for="'.esc_attr($slug).'">' . __('Parent Course:', 'micro-office') . "</label> <select name='".esc_attr($slug)."' id='".esc_attr($slug)."' class='postform'>";
			foreach ($courses as $id=>$name) {
				$list .= '<option value='. esc_attr($id) . (isset($_GET[$slug]) && $_GET[$slug] == $id ? ' selected="selected"' : '') . '>' . esc_html($name) . '</option>';
			}
			$list .=  "</select>";
		}
		micro_office_show_layout($list);
	}
}

// Add filter in main query
if (!function_exists('micro_office_lesson_add_parent_course_in_query')) {
	
	function micro_office_lesson_add_parent_course_in_query($query) {
		if ( is_admin() && micro_office_check_admin_page('edit.php') && $query->is_main_query() && $query->get( 'post_type' )=='lesson' ) {
			$parent_course = isset( $_GET['parent_course'] ) ? intval($_GET['parent_course']) : 0;
			if ($parent_course > 0 ) {
				$meta_query = $query->get( 'meta_query' );
				if (!is_array($meta_query)) $meta_query = array();
				$meta_query['relation'] = 'AND';
				$meta_query[] = array(
					'meta_filter' => 'lesson',
					'key' => micro_office_storage_get('options_prefix').'_parent_course',
					'value' => $parent_course,
					'compare' => '=',
					'type' => 'NUMERIC'
				);
				$query->set( 'meta_query', $meta_query );
			}
		}
		return $query;
	}
}


/* Display metabox for lessons
-------------------------------------------------------------------------------------------- */

if (!function_exists('micro_office_lesson_after_theme_setup')) {
	add_action( 'micro_office_action_after_init_theme', 'micro_office_lesson_after_theme_setup' );
	function micro_office_lesson_after_theme_setup() {
		// Update fields in the meta box
		if (micro_office_storage_get_array('post_meta_box', 'page')=='lesson') {
			// Meta box fields
			micro_office_storage_set_array('post_meta_box','title', __('Lesson Options', 'micro-office'));
			micro_office_storage_set_array('post_meta_box', 'fields', array(
				"mb_partition_lessons" => array(
					"title" => __('Lesson', 'micro-office'),
					"override" => "page,post,custom",
					"divider" => false,
					"icon" => "iconadmin-users-1",
					"type" => "partition"),
				"mb_info_lessons_1" => array(
					"title" => __('Lesson details', 'micro-office'),
					"override" => "page,post,custom",
					"divider" => false,
					"desc" => __('In this section you can put details for this lesson', 'micro-office'),
					"class" => "course_meta",
					"type" => "info"),
				"parent_course" => array(
					"title" => __('Parent Course',  'micro-office'),
					"desc" => __("Select parent course for this lesson", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "lesson_parent_course",
					"std" => '',
					"options" => micro_office_get_list_posts(false, array(
						'post_type' => 'courses',
						'orderby' => 'title',
						'order' => 'asc'
						)
					),
					"type" => "select"),
				"teacher" => array(
					"title" => __('Teacher',  'micro-office'),
					"desc" => __("Main Teacher for this lesson", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "lesson_teacher",
					"std" => '',
					"options" => micro_office_get_list_posts(false, array(
						'post_type' => 'team',
						'orderby' => 'title',
						'order' => 'asc')
					),
					"type" => "select"),
				"date_start" => array(
					"title" => __('Start date',  'micro-office'),
					"desc" => __("Lesson start date", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "lesson_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"date_end" => array(
					"title" => __('End date',  'micro-office'),
					"desc" => __("Lesson finish date", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "lesson_date",
					"std" => date('Y-m-d'),
					"format" => 'yy-mm-dd',
					"type" => "date"),
				"shedule" => array(
					"title" => __('Schedule time',  'micro-office'),
					"desc" => __("Lesson start days and time. For example: Mon, Wed, Fri 19:00-21:00", 'micro-office'),
					"override" => "page,post,custom",
					"class" => "lesson_time",
					"std" => '',
					"divider" => false,
					"type" => "text")
				)
			);
		}
	}
}

// Before save custom options - calc and save average rating
if (!function_exists('micro_office_lesson_save_custom_options')) {
	
	function micro_office_lesson_save_custom_options($custom_options, $post_type, $post_id) {
		if (isset($custom_options['parent_course'])) {
			update_post_meta($post_id, micro_office_storage_get('options_prefix').'_parent_course', $custom_options['parent_course']);
		}
		if (isset($custom_options['date_start'])) {
			update_post_meta($post_id, micro_office_storage_get('options_prefix').'_date_start', $custom_options['date_start']);
		}
		return $custom_options;
	}
}


// Return lessons list by parent course post ID
if ( !function_exists( 'micro_office_get_lessons_list' ) ) {
	function micro_office_get_lessons_list($parent_id, $count=-1) {
		$list = array();
		$args = array(
			'post_type' => 'lesson',
			'post_status' => 'publish',
			'meta_key' => micro_office_storage_get('options_prefix').'_date_start',
			'orderby' => 'meta_value',		//'date'
			'order' => 'asc',
			'ignore_sticky_posts' => true,
			'posts_per_page' => $count,
			'meta_query' => array(
				array(
					'key'     => 'parent_course',
					'value'   => $parent_id,
					'compare' => '=',
					'type'    => 'NUMERIC'
				)
			)
		);
		global $post;
		$query = new WP_Query( $args );
		while ( $query->have_posts() ) { $query->the_post();
			$list[] = $post;
		}
		wp_reset_postdata();
		return $list;
	}
}

// Return lessons TOC by parent course post ID
if ( !function_exists( 'micro_office_get_lessons_links' ) ) {
	function micro_office_get_lessons_links($parent_id, $current_id=0, $opt = array()) {
		$opt = array_merge( array(
			'show_lessons' => true,
			'show_prev_next' => false,
			'header' => '',
			'description' => ''
			), $opt);
		$output = '';
		if ($parent_id > 0) {
			$courses_list = micro_office_get_lessons_list($parent_id);
			$courses_toc = '';
			$prev_course = $next_course = null;
			if (count($courses_list) > 1) {
				$step = 0;
				foreach ($courses_list as $course) {
					if ($course->ID == $current_id)
						$step = 1;
					else if ($step==0)
						$prev_course = $course;
					else if ($step==1) {
						$next_course = $course;
						$step = 2;
						if (!$opt['show_lessons']) break;
					}
					if ($opt['show_lessons']) {
						$teacher_id = micro_office_get_custom_option('teacher', '', $course->ID, $course->post_type);				//!!!!! Get option from specified post
						$teacher_post = get_post($teacher_id);
						$teacher_link = get_permalink($teacher_id);
						$teacher_position = '';
						$course_start = micro_office_get_custom_option('date_start', '', $course->ID, $course->post_type);			//!!!!! Get option from specified post
						$courses_toc .= '<li class="sc_list_item course_lesson_item">'
							. '<span class="sc_list_icon icon-dot"></span>'
							. ($course->ID == $current_id ? '<span class="course_lesson_title">' : '<a href="'.esc_url(get_permalink($course->ID)).'" class="course_lesson_title">') 
								. strip_tags($course->post_title) 
							. ($course->ID == $current_id ? '</span>' : '</a>')
							. ' | <span class="course_lesson_date">' . esc_html(micro_office_get_date_or_difference(!empty($course_start) ? $course_start : $course->post_date)) . '</span>'
							. ' <span class="course_lesson_by">' . esc_html(__('by', 'micro-office')) . '</span>'
							. ' <a href="'.esc_url($teacher_link).'" class="course_lesson_teacher">' . trim($teacher_position) . ' ' . trim($teacher_post->post_title) . '</a>'
							. (!empty($course->post_excerpt) ? '<div class="course_lesson_excerpt">' . strip_tags($course->post_excerpt) . '</div>' : '')
							. '</li>';
					}
				}
				$output .= ($opt['show_lessons'] 
								? ('<div class="course_toc' . ($opt['show_prev_next'] ? ' course_toc_with_pagination' : '') . '">'
									. ($opt['header'] ? '<h2 class="course_toc_title">' . trim($opt['header']) . '</h2>' : '')
									. ($opt['description'] ? '<div class="course_toc_description">' . trim($opt['description']) . '</div>' : '')
									. '<ul class="sc_list sc_list_style_iconed">' . trim($courses_toc) . '</ul>'
									. '</div>')
								: '')
					. ($opt['show_prev_next']
								? ('<nav class="pagination_single pagination_lessons" role="navigation">'
									. ($prev_course != null 
										? '<a href="' . esc_url(get_permalink($prev_course->ID)) . '" class="pager_prev"><span class="pager_numbers">&laquo;&nbsp;' . strip_tags($prev_course->post_title) . '</span></a>'
										: '')
									. ($next_course != null
										? '<a href="' . esc_url(get_permalink($next_course->ID)) . '" class="pager_next"><span class="pager_numbers">' . strip_tags($next_course->post_title) . '&nbsp;&raquo;</span></a>'
										: '')
									. '</nav>')
								: '');
			}
		}
		return $output;
	}
}






// ---------------------------------- [trx_lessons] ---------------------------------------

if ( !function_exists( 'micro_office_sc_lessons' ) ) {
	function micro_office_sc_lessons($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"course_id" => "",
			"align" => "",
			"title" => "",
			"description" => "",
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
	
		$css .= micro_office_get_css_position_from_values($top, $right, $bottom, $left, $width, $height);
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_lessons' 
								. (!empty($class) ? ' '.esc_attr($class) : '') 
								. ($align && $align!='none' ? ' align'.esc_attr($align) : '')
								. '"'
							. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '') 
					. '>'
					. micro_office_get_lessons_links($course_id, 0, array(
							'header' => $title,
							'description' => $description,
							'show_lessons' => true,
							'show_prev_next' => false
							)
						)
					. '</div>';
		
		return apply_filters('micro_office_shortcode_output', $output, 'trx_lessons', $atts, $content);
	}
	micro_office_require_shortcode('trx_lessons', 'micro_office_sc_lessons');
}
// ---------------------------------- [/trx_lessons] ---------------------------------------


// Add [trx_lessons] in the shortcodes list
if (!function_exists('micro_office_lesson_reg_shortcodes')) {
	
	function micro_office_lesson_reg_shortcodes() {
		if (micro_office_storage_isset('shortcodes')) {

			$courses = micro_office_get_list_posts(false, array(
						'post_type' => 'courses',
						'orderby' => 'title',
						'order' => 'asc'
						)
			);

			micro_office_sc_map_after('trx_infobox', array(

				// Lessons
				"trx_lessons" => array(
					"title" => __("Lessons", "micro-office"),
					"desc" => __("Insert list of lessons for specified course", "micro-office"),
					"decorate" => false,
					"container" => false,
					"params" => array(
						"course_id" => array(
							"title" => __("Course", "micro-office"),
							"desc" => __("Select the desired course", "micro-office"),
							"value" => "",
							"options" => $courses,
							"type" => "select"
						),
						"title" => array(
							"title" => __("Title", "micro-office"),
							"desc" => __("Title for the section with lessons", "micro-office"),
							"divider" => true,
							"dependency" => array(
								'course_id' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"description" => array(
							"title" => __("Description", "micro-office"),
							"desc" => __("Description for the section with lessons", "micro-office"),
							"divider" => true,
							"dependency" => array(
								'course_id' => array('not_empty')
							),
							"value" => "",
							"type" => "text"
						),
						"align" => array(
							"title" => __("Alignment", "micro-office"),
							"desc" => __("Align block to the left or right side", "micro-office"),
							"divider" => true,
							"value" => "",
							"type" => "checklist",
							"dir" => "horizontal",
							"options" => micro_office_get_sc_param('float')
						), 
						"width" => micro_office_shortcodes_width(),
						"height" => micro_office_shortcodes_height(),
						"top" => micro_office_get_sc_param('top'),
						"bottom" => micro_office_get_sc_param('bottom'),
						"left" => micro_office_get_sc_param('left'),
						"right" => micro_office_get_sc_param('right'),
						"id" => micro_office_get_sc_param('id'),
						"class" => micro_office_get_sc_param('class'),
						"css" => micro_office_get_sc_param('css')
					)
				)

			));
		}
	}
}


// Add [trx_lessons] in the VC shortcodes list
if (!function_exists('micro_office_lesson_reg_shortcodes_vc')) {
	
	function micro_office_lesson_reg_shortcodes_vc() {

		$courses = micro_office_get_list_posts(false, array(
					'post_type' => 'courses',
					'orderby' => 'title',
					'order' => 'asc'
					)
		);

		// Lessons
		vc_map( array(
			"base" => "trx_lessons",
			"name" => __("Lessons", "micro-office"),
			"description" => __("Insert list of lessons for specified course", "micro-office"),
			"category" => __('Content', 'micro-office'),
			'icon' => 'icon_trx_lessons',
			"class" => "trx_sc_single trx_sc_lessons",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "course_id",
					"heading" => __("Course", "micro-office"),
					"description" => __("Select the desired course", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip($courses),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => __("Title", "micro-office"),
					"description" => __("Title for the section with lessons", "micro-office"),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => __("Description", "micro-office"),
					"description" => __("Description for the section with lessons", "micro-office"),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => __("Alignment", "micro-office"),
					"description" => __("Alignment of the lessons block", "micro-office"),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
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
		
		class WPBakeryShortCode_Trx_Lessons extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>