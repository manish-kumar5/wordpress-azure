<?php
/**
 * Theme Widget: Recent posts
 */

// Theme init
if (!function_exists('micro_office_widget_vacancies_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_widget_vacancies_theme_setup', 1 );
	function micro_office_widget_vacancies_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_widget_vacancies_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('micro_office_widget_vacancies_load')) {
	add_action( 'widgets_init', 'micro_office_widget_vacancies_load' );
	function micro_office_widget_vacancies_load() {
		register_widget('micro_office_widget_vacancies');
	}
}

// Widget Class
class micro_office_widget_vacancies extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_vacancies', 'description' => esc_html__('The recent blog posts (extended)', 'micro-office'));
		parent::__construct( 'micro_office_widget_vacancies', esc_html__('Micro Office - Vacancies', 'micro-office'), $widget_ops );

		// Add thumb sizes into list
		micro_office_add_thumb_sizes( array( 'layout' => 'widgets', 'w' => 75, 'h' => 75, 'title'=>esc_html__('Widgets', 'micro-office') ) );
	}

	// Show widget
	function widget($args, $instance) {
		extract($args);

		global $post;

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '');

		$post_type = isset($instance['post_type']) ? $instance['post_type'] : 'vacancies';
		$category = isset($instance['category']) ? (int) $instance['category'] : 0;
		$taxonomy = micro_office_get_taxonomy_categories_by_post_type($post_type);

		$number = isset($instance['number']) ? (int) $instance['number'] : '';
		
		$output = '';

		$args = array(
			'numberposts' => $number,
			'offset' => 0,
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => $post_type,
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'ignore_sticky_posts' => true,
			'suppress_filters' => true 
    	);
		if ($category > 0) {
			if ($taxonomy=='category')
				$args['category'] = $category;
			else {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'id',
						'terms' => $category
					)
				);
			}
		}
		$ex = micro_office_get_theme_option('exclude_cats');
		if (!empty($ex)) {
			$args['category__not_in'] = explode(',', $ex);
		}

    	$vacancies = wp_get_recent_posts($args, OBJECT);
			
		$post_number = 0;

		if (is_array($vacancies) && count($vacancies) > 0) {
			foreach ($vacancies as $post) {
				$post_number++;
				micro_office_template_set_args('widgets-vacancies', array(
					'post_number' => $post_number
				));
				get_template_part(micro_office_get_file_slug('templates/_parts/widgets-vacancies.php'));
				$output .= micro_office_storage_get('widgets_vacancies_output');
				if ($post_number >= $number) break;
			}
			wp_reset_postdata();
		}

		if (!empty($output)) {
	
			// Before widget (defined by themes)
			micro_office_show_layout($before_widget);
			
			// Display the widget title if one was input (before and after defined by themes)
			if ($title) micro_office_show_layout($before_title . $title . $after_title);
	
			micro_office_show_layout($output);
			
			// After widget (defined by themes)
			micro_office_show_layout($after_widget);
		}
	}

	// Update the widget settings.
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['category'] = (int) $new_instance['category'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form($instance) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title' => '',
			'number' => '4',
			'category'=>'0',
			'post_type' => 'vacancies'
			)
		);
		$title = $instance['title'];
		$number = (int) $instance['number'];
		$category = (int) $instance['category'];

		$posts_types = micro_office_get_list_posts_types(false);
		$categories = micro_office_get_list_terms(false, micro_office_get_taxonomy_categories_by_post_type('vacancies'));
		
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Widget title:', 'micro-office'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title); ?>" class="widgets_param_fullwidth" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('category')); ?>"><?php esc_html_e('Category:', 'micro-office'); ?></label>
			<select id="<?php echo esc_attr($this->get_field_id('category')); ?>" name="<?php echo esc_attr($this->get_field_name('category')); ?>" class="widgets_param_fullwidth">
				<option value="0"><?php esc_html_e('-- Any category --', 'micro-office'); ?></option> 
			<?php
				if (is_array($categories) && count($categories) > 0) {
					foreach ($categories as $cat_id => $cat_name) {
						echo '<option value="'.esc_attr($cat_id).'"'.($category==$cat_id ? ' selected="selected"' : '').'>'.esc_html($cat_name).'</option>';
					}
				}
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number posts to show:', 'micro-office'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" class="widgets_param_fullwidth" />
		</p>

	<?php
	}
}



// trx_widget_vacancies
//-------------------------------------------------------------


if ( !function_exists( 'micro_office_sc_widget_vacancies' ) ) {
	function micro_office_sc_widget_vacancies($atts, $content=null){	
		$atts = micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"number" => 4,
			'category' 		=> '',
			'cat' 			=> 0,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		$atts['post_type'] = 'vacancies';
		if ($atts['cat']!='' && $atts['category']=='') $atts['category'] = $atts['cat'];
		extract($atts);
		$type = 'micro_office_widget_vacancies';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_vacancies' 
								. (micro_office_exists_visual_composer() ? ' vc_widget_vacancies wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, micro_office_prepare_widgets_args(micro_office_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_vacancies', 'widget_vacancies') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_widget_vacancies', $atts, $content);
	}
	micro_office_require_shortcode("trx_widget_vacancies", "micro_office_sc_widget_vacancies"); 
}


// Add [trx_widget_vacancies] in the VC shortcodes list
if (!function_exists('micro_office_widget_vacancies_reg_shortcodes_vc')) {
	function micro_office_widget_vacancies_reg_shortcodes_vc() {
		
		$posts_types = micro_office_get_list_posts_types(false);
		$categories = micro_office_get_list_terms(false, micro_office_get_taxonomy_categories_by_post_type('post'));
		$counters = array_merge(array('hide' => esc_html__('Hide', 'micro-office')), micro_office_get_list_blog_counters(false));

		vc_map( array(
				"base" => "trx_widget_vacancies",
				"name" => esc_html__("Widget Recent Posts", "micro-office"),
				"description" => wp_kses_data( __("Insert recent posts list with thumbs and post's meta", "micro-office") ),
				"category" => esc_html__('Content', 'micro-office'),
				"icon" => 'icon_trx_widget_vacancies',
				"class" => "trx_widget_vacancies",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "title",
						"heading" => esc_html__("Widget title", "micro-office"),
						"description" => wp_kses_data( __("Title of the widget", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number posts to show", "micro-office"),
						"description" => wp_kses_data( __("How many posts display in widget?", "micro-office") ),
						"admin_label" => true,
						"class" => "",
						"value" => "4",
						"type" => "textfield"
					),
					array(
						"param_name" => "cat",
						"heading" => esc_html__("Parent category", "micro-office"),
						"description" => wp_kses_data( __("Select parent category. If empty - show posts from any category", "micro-office") ),
						"class" => "",
						"value" => array_flip(micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), $categories)),
						"type" => "dropdown"
					),
					micro_office_get_vc_param('id'),
					micro_office_get_vc_param('class'),
					micro_office_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Vacancies extends WPBakeryShortCode {}

	}
}
?>