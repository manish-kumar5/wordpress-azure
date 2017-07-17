<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_blogger_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_blogger_theme_setup' );
	function micro_office_sc_blogger_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_blogger_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_blogger_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

micro_office_storage_set('sc_blogger_busy', false);

if (!function_exists('micro_office_sc_blogger')) {	
	function micro_office_sc_blogger($atts, $content=null){	
		if (micro_office_in_shortcode_blogger(true)) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "masonry_3",
			"filters" => "no",
			"post_type" => "post",
			"ids" => "",
			"cat" => "",
			"count" => "3",
			"columns" => "",
			"offset" => "",
			"orderby" => "date",
			"order" => "asc",
			"only" => "no",
			"descr" => "",
			"readmore" => "",
			"loadmore" => "no",
			"location" => "default",
			"dir" => "horizontal",
			"hover" => micro_office_get_theme_option('hover_style'),
			"hover_dir" => micro_office_get_theme_option('hover_dir'),
			"scroll" => "no",
			"controls" => "no",
			"rating" => "no",
			"info" => "yes",
			"links" => "yes",
			"date_format" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link" => '',
			"link_caption" => esc_html__('Learn more', 'micro-office'),
			// Common params
			"id" => "",
			"class" => "",
			"css" => "",
			"animation" => "",
			"width" => "",
			"height" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
	
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);

		$css .= micro_office_get_css_dimensions_from_values($width, $height);
		$width  = micro_office_prepare_css_value($width);
		$height = micro_office_prepare_css_value($height);
	
		global $post;
	
		micro_office_storage_set('sc_blogger_busy', true);
		micro_office_storage_set('sc_blogger_counter', 0);
	
		if (empty($id)) $id = "sc_blogger_".str_replace('.', '', mt_rand());
		
		if ($style=='date' && empty($date_format)) $date_format = 'd.m+Y';
	
		if (!empty($ids)) {
			$posts = explode(',', str_replace(' ', '', $ids));
			$count = count($posts);
		}
		
		if ($descr == '') $descr = micro_office_get_custom_option('post_excerpt_maxlength'.($columns > 1 ? '_masonry' : ''));
	
		if (!micro_office_param_is_off($scroll)) {
			micro_office_enqueue_slider();
			if (empty($id)) $id = 'sc_blogger_'.str_replace('.', '', mt_rand());
		}
		
		$class = apply_filters('micro_office_filter_blog_class',
					'sc_blogger'
					. ' layout_'.esc_attr($style)
					. ' template_'.esc_attr(micro_office_get_template_name($style))
					. (!empty($class) ? ' '.esc_attr($class) : '')
					. ' ' . esc_attr(micro_office_get_template_property($style, 'container_classes'))
					. ' sc_blogger_' . ($dir=='vertical' ? 'vertical' : 'horizontal')
					. (micro_office_param_is_on($scroll) && micro_office_param_is_on($controls) ? ' sc_scroll_controls sc_scroll_controls_type_top sc_scroll_controls_'.esc_attr($dir) : '')
					. ($descr == 0 ? ' no_description' : ''),
					array('style'=>$style, 'dir'=>$dir, 'descr'=>$descr)
		);
	
		$container = apply_filters('micro_office_filter_blog_container', micro_office_get_template_property($style, 'container'), array('style'=>$style, 'dir'=>$dir));
		$container_start = $container_end = '';
		if (!empty($container)) {
			$container = explode('%s', $container);
			$container_start = !empty($container[0]) ? $container[0] : '';
			$container_end = !empty($container[1]) ? $container[1] : '';
		}
		$container2 = apply_filters('micro_office_filter_blog_container2', micro_office_get_template_property($style, 'container2'), array('style'=>$style, 'dir'=>$dir));
		$container2_start = $container2_end = '';
		if (!empty($container2)) {
			$container2 = explode('%s', $container2);
			$container2_start = !empty($container2[0]) ? $container2[0] : '';
			$container2_end = !empty($container2[1]) ? $container2[1] : '';
		}
	
		$output = '<div'
				. ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="'.($style=='list' ? 'sc_list sc_list_style_iconed ' : '') . esc_attr($class).'"'
				. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
			. '>'
			. ($container_start)
			. (!empty($subtitle) ? '<h6 class="sc_blogger_subtitle sc_item_subtitle">' . trim(micro_office_strmacros($subtitle)) . '</h6>' : '')
			. (!empty($title) ? '<h2 class="sc_blogger_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(micro_office_strmacros($title)) . '</h2>' : '')
			. (!empty($description) ? '<div class="sc_blogger_descr sc_item_descr">' . trim(micro_office_strmacros($description)) . '</div>' : '')
			. ($container2_start)
			. ($style=='list' ? '<ul class="sc_list sc_list_style_iconed">' : '')
			. ($dir=='horizontal' && $columns > 1 && micro_office_get_template_property($style, 'need_columns') ? '<div class="columns_wrap">' : '')
			. (micro_office_param_is_on($scroll) 
				? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($dir).' sc_slider_noresize swiper-slider-container scroll-container"'
					. ' style="'.($dir=='vertical' ? 'height:'.($height != '' ? $height : "230px").';' : 'width:'.($width != '' ? $width.';' : "100%;")).'"'
					. '>'
					. '<div class="sc_scroll_wrapper swiper-wrapper">' 
						. '<div class="sc_scroll_slide swiper-slide">' 
				: '')
			;
	
		if (micro_office_get_template_property($style, 'need_isotope')) {
			if (!micro_office_param_is_off($filters))
				$output .= '<div class="isotope_filters"></div>';
			if ($columns<1) $columns = micro_office_substr($style, -1);
			$output .= '<div class="isotope_wrap" data-columns="'.max(1, min(12, $columns)).'">';
		}
	
		$args = array(
			'post_status' => current_user_can('read_private_pages') && current_user_can('read_private_posts') ? array('publish', 'private') : 'publish',
			'posts_per_page' => $count,
			'ignore_sticky_posts' => true,
			'order' => $order=='asc' ? 'asc' : 'desc',
			'orderby' => 'date',
		);
	
		if ($offset > 0 && empty($ids)) {
			$args['offset'] = $offset;
		}
	
		$args = micro_office_query_add_sort_order($args, $orderby, $order);
		if (!micro_office_param_is_off($only)) $args = micro_office_query_add_filters($args, $only);
		$args = micro_office_query_add_posts_and_cats($args, $ids, $post_type, $cat);

		$query = new WP_Query( $args );
	
		$flt_ids = array();
	
		while ( $query->have_posts() ) { $query->the_post();
	
			micro_office_storage_inc('sc_blogger_counter');
	
			$args = array(
				'layout' => $style,
				'show' => false,
				'number' => micro_office_storage_get('sc_blogger_counter'),
				'add_view_more' => false,
				'posts_on_page' => ($count > 0 ? $count : $query->found_posts),
				// Additional options to layout generator
				"location" => $location,
				"descr" => $descr,
				"readmore" => $readmore,
				"loadmore" => $loadmore,
				"reviews" => micro_office_param_is_on($rating),
				"dir" => $dir,
				"scroll" => micro_office_param_is_on($scroll),
				"info" => micro_office_param_is_on($info),
				"links" => micro_office_param_is_on($links),
				"orderby" => $orderby,
				"columns_count" => $columns,
				"date_format" => $date_format,
				// Get post data
				'strip_teaser' => false,
				'content' => micro_office_get_template_property($style, 'need_content'),
				'terms_list' => !micro_office_param_is_off($filters) || micro_office_get_template_property($style, 'need_terms'),
				'filters' => micro_office_param_is_off($filters) ? '' : $filters,
				'hover' => $hover,
				'hover_dir' => $hover_dir
			);
			$post_data = micro_office_get_post_data($args);
			$output .= micro_office_show_post_layout($args, $post_data);
		
			if (!micro_office_param_is_off($filters)) {
				if ($filters == 'tags') {			// Use tags as filter items
					if (!empty($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms) && is_array($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms)) {
						foreach ($post_data['post_terms'][$post_data['post_taxonomy_tags']]->terms as $tag) {
							$flt_ids[$tag->term_id] = $tag->name;
						}
					}
				}
			}
	
		}
	
		wp_reset_postdata();
	
		// Close isotope wrapper
		if (micro_office_get_template_property($style, 'need_isotope'))
			$output .= '</div>';
	
		// Isotope filters list
		if (!micro_office_param_is_off($filters)) {
			$filters_list = '';
			if ($filters == 'categories') {			// Use categories as filter items
				$taxonomy = micro_office_get_taxonomy_categories_by_post_type($post_type);
				$portfolio_parent = $cat ? max(0, micro_office_get_parent_taxonomy_by_property($cat, 'show_filters', 'yes', true, $taxonomy)) : 0;
				$args2 = array(
					'type'			=> $post_type,
					'child_of'		=> $portfolio_parent,
					'orderby'		=> 'name',
					'order'			=> 'ASC',
					'hide_empty'	=> 1,
					'hierarchical'	=> 0,
					'exclude'		=> '',
					'include'		=> '',
					'number'		=> '',
					'taxonomy'		=> $taxonomy,
					'pad_counts'	=> false
				);
				$portfolio_list = get_categories($args2);
				if (is_array($portfolio_list) && count($portfolio_list) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.esc_html__('All', 'micro-office').'</a>';
					foreach ($portfolio_list as $cat) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($cat->term_id).'" class="theme_button">'.($cat->name).'</a>';
					}
				}
			} else {								// Use tags as filter items
				if (is_array($flt_ids) && count($flt_ids) > 0) {
					$filters_list .= '<a href="#" data-filter="*" class="theme_button active">'.esc_html__('All', 'micro-office').'</a>';
					foreach ($flt_ids as $flt_id=>$flt_name) {
						$filters_list .= '<a href="#" data-filter=".flt_'.esc_attr($flt_id).'" class="theme_button">'.($flt_name).'</a>';
					}
				}
			}
			if ($filters_list) {
				micro_office_storage_concat('js_code', '
					jQuery("#'.esc_attr($id).' .isotope_filters").append("'.addslashes($filters_list).'");
				');
			}
		}
		$output	.= (micro_office_param_is_on($scroll) 
				? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
					. (!micro_office_param_is_off($controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
				: '')
			. ($dir=='horizontal' && $columns > 1 && micro_office_get_template_property($style, 'need_columns') ? '</div>' :  '')
			. ($style == 'list' ? '</ul>' : '')
			. ($container2_end)
			. (!empty($link) 
				? '<div class="sc_blogger_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' 				: '')
			. ($container_end)
			. '</div>';
	
		// Add template specific scripts and styles
		do_action('micro_office_action_blog_scripts', $style);
		
		micro_office_storage_set('sc_blogger_busy', false);
	
		return apply_filters('micro_office_shortcode_output', $output, 'trx_blogger', $atts, $content);
	}
	micro_office_require_shortcode('trx_blogger', 'micro_office_sc_blogger');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_blogger_reg_shortcodes' ) ) {
	
	function micro_office_sc_blogger_reg_shortcodes() {
	
		micro_office_sc_map("trx_blogger", array(
			"title" => esc_html__("Blogger", "micro-office"),
			"desc" => wp_kses_data( __("Insert posts (pages) in many styles from desired categories or directly from ids", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"title" => array(
					"title" => esc_html__("Title", "micro-office"),
					"desc" => wp_kses_data( __("Title for the block", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"subtitle" => array(
					"title" => esc_html__("Subtitle", "micro-office"),
					"desc" => wp_kses_data( __("Subtitle for the block", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"description" => array(
					"title" => esc_html__("Description", "micro-office"),
					"desc" => wp_kses_data( __("Short description for the block", "micro-office") ),
					"value" => "",
					"type" => "textarea"
				),
				"style" => array(
					"title" => esc_html__("Posts output style", "micro-office"),
					"desc" => wp_kses_data( __("Select desired style for posts output", "micro-office") ),
					"value" => "masonry_3",
					"type" => "select",
					"options" => micro_office_get_sc_param('blogger_styles')
				),
				"filters" => array(
					"title" => esc_html__("Show filters", "micro-office"),
					"desc" => wp_kses_data( __("Use post's tags or categories as filter buttons", "micro-office") ),
					"value" => "no",
					"dir" => "horizontal",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('filters')
				),
				"post_type" => array(
					"title" => esc_html__("Post type", "micro-office"),
					"desc" => wp_kses_data( __("Select post type to show", "micro-office") ),
					"value" => "post",
					"type" => "select",
					"options" => micro_office_get_sc_param('posts_types')
				),
				"ids" => array(
					"title" => esc_html__("Post IDs list", "micro-office"),
					"desc" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"cat" => array(
					"title" => esc_html__("Categories list", "micro-office"),
					"desc" => wp_kses_data( __("Select the desired categories. If not selected - show posts from any category or from IDs list", "micro-office") ),
					"dependency" => array(
						'ids' => array('is_empty'),
						'post_type' => array('refresh')
					),
					"divider" => true,
					"value" => "",
					"type" => "select",
					"style" => "list",
					"multiple" => true,
					"options" => micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), micro_office_get_sc_param('categories'))
				),
				"count" => array(
					"title" => esc_html__("Total posts to show", "micro-office"),
					"desc" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "micro-office") ),
					"dependency" => array(
						'ids' => array('is_empty')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"columns" => array(
					"title" => esc_html__("Columns number", "micro-office"),
					"desc" => wp_kses_data( __("How many columns used to show posts? If empty or 0 - equal to posts number", "micro-office") ),
					"dependency" => array(
						'dir' => array('horizontal')
					),
					"value" => 3,
					"min" => 1,
					"max" => 100,
					"type" => "spinner"
				),
				"offset" => array(
					"title" => esc_html__("Offset before select posts", "micro-office"),
					"desc" => wp_kses_data( __("Skip posts before select next part.", "micro-office") ),
					"dependency" => array(
						'ids' => array('is_empty')
					),
					"value" => 0,
					"min" => 0,
					"max" => 100,
					"type" => "spinner"
				),
				"orderby" => array(
					"title" => esc_html__("Post order by", "micro-office"),
					"desc" => wp_kses_data( __("Select desired posts sorting method", "micro-office") ),
					"value" => "date",
					"type" => "select",
					"options" => micro_office_get_sc_param('sorting')
				),
				"order" => array(
					"title" => esc_html__("Post order", "micro-office"),
					"desc" => wp_kses_data( __("Select desired posts order", "micro-office") ),
					"value" => "asc",
					"type" => "switch",
					"size" => "big",
					"options" => micro_office_get_sc_param('ordering')
				),
				"only" => array(
					"title" => esc_html__("Select posts only", "micro-office"),
					"desc" => wp_kses_data( __("Select posts only with reviews, videos, audios, thumbs or galleries", "micro-office") ),
					"value" => "no",
					"type" => "select",
					"options" => micro_office_get_sc_param('formats')
				),
				"info" => array(
					"title" => esc_html__("Show post info block", "micro-office"),
					"desc" => wp_kses_data( __("Show post info block (author, date, tags, etc.)", "micro-office") ),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"links" => array(
					"title" => esc_html__("Allow links on the post", "micro-office"),
					"desc" => wp_kses_data( __("Allow links on the post from each blogger item", "micro-office") ),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"descr" => array(
					"title" => esc_html__("Description length", "micro-office"),
					"desc" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", "micro-office") ),
					"value" => 0,
					"min" => 0,
					"step" => 10,
					"type" => "spinner"
				),
				"readmore" => array(
					"title" => esc_html__("More link text", "micro-office"),
					"desc" => wp_kses_data( __("Read more link text. If empty - show 'More', else - used as link text", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"link" => array(
					"title" => esc_html__("Button URL", "micro-office"),
					"desc" => wp_kses_data( __("Link URL for the button at the bottom of the block", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"link_caption" => array(
					"title" => esc_html__("Button caption", "micro-office"),
					"desc" => wp_kses_data( __("Caption for the button at the bottom of the block", "micro-office") ),
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
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_blogger_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_blogger_reg_shortcodes_vc() {

		vc_map( array(
			"base" => "trx_blogger",
			"name" => esc_html__("Blogger", "micro-office"),
			"description" => wp_kses_data( __("Insert posts (pages) in many styles from desired categories or directly from ids", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_blogger',
			"class" => "trx_sc_single trx_sc_blogger",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Output style", "micro-office"),
					"description" => wp_kses_data( __("Select desired style for posts output", "micro-office") ),
					"admin_label" => true,
					"std" => "masonry_3",
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('blogger_styles')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "filters",
					"heading" => esc_html__("Show filters", "micro-office"),
					"description" => wp_kses_data( __("Use post's tags or categories as filter buttons", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('filters')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns number", "micro-office"),
					"description" => wp_kses_data( __("How many columns used to display posts?", "micro-office") ),
					'dependency' => array(
						'element' => 'dir',
						'value' => 'horizontal'
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "info",
					"heading" => esc_html__("Show post info block", "micro-office"),
					"description" => wp_kses_data( __("Show post info block (author, date, tags, etc.)", "micro-office") ),
					"class" => "",
					"std" => 'yes',
					"value" => array(esc_html__('Show info', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "descr",
					"heading" => esc_html__("Description length", "micro-office"),
					"description" => wp_kses_data( __("How many characters are displayed from post excerpt? If 0 - don't show description", "micro-office") ),
					"group" => esc_html__('Details', 'micro-office'),
					"class" => "",
					"value" => 0,
					"type" => "textfield"
				),
				array(
					"param_name" => "links",
					"heading" => esc_html__("Allow links to the post", "micro-office"),
					"description" => wp_kses_data( __("Allow links to the post from each blogger item", "micro-office") ),
					"group" => esc_html__('Details', 'micro-office'),
					"class" => "",
					"std" => 'yes',
					"value" => array(esc_html__('Allow links', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "readmore",
					"heading" => esc_html__("More link text", "micro-office"),
					"description" => wp_kses_data( __("Read more link text. If empty - show 'More', else - used as link text", "micro-office") ),
					"group" => esc_html__('Details', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for the block", "micro-office") ),
					"admin_label" => true,
					"group" => esc_html__('Captions', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "subtitle",
					"heading" => esc_html__("Subtitle", "micro-office"),
					"description" => wp_kses_data( __("Subtitle for the block", "micro-office") ),
					"group" => esc_html__('Captions', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "description",
					"heading" => esc_html__("Description", "micro-office"),
					"description" => wp_kses_data( __("Description for the block", "micro-office") ),
					"group" => esc_html__('Captions', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textarea"
				),
				array(
					"param_name" => "link",
					"heading" => esc_html__("Button URL", "micro-office"),
					"description" => wp_kses_data( __("Link URL for the button at the bottom of the block", "micro-office") ),
					"group" => esc_html__('Captions', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "link_caption",
					"heading" => esc_html__("Button caption", "micro-office"),
					"description" => wp_kses_data( __("Caption for the button at the bottom of the block", "micro-office") ),
					"group" => esc_html__('Captions', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "post_type",
					"heading" => esc_html__("Post type", "micro-office"),
					"description" => wp_kses_data( __("Select post type to show", "micro-office") ),
					"group" => esc_html__('Query', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('posts_types')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "ids",
					"heading" => esc_html__("Post IDs list", "micro-office"),
					"description" => wp_kses_data( __("Comma separated list of posts ID. If set - parameters above are ignored!", "micro-office") ),
					"group" => esc_html__('Query', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "cat",
					"heading" => esc_html__("Categories list", "micro-office"),
					"description" => wp_kses_data( __("Select category. If empty - show posts from any category or from IDs list", "micro-office") ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"group" => esc_html__('Query', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_array_merge(array(0 => esc_html__('- Select category -', 'micro-office')), micro_office_get_sc_param('categories'))),
					"type" => "dropdown"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Total posts to show", "micro-office"),
					"description" => wp_kses_data( __("How many posts will be displayed? If used IDs - this parameter ignored.", "micro-office") ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"admin_label" => true,
					"group" => esc_html__('Query', 'micro-office'),
					"class" => "",
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "offset",
					"heading" => esc_html__("Offset before select posts", "micro-office"),
					"description" => wp_kses_data( __("Skip posts before select next part.", "micro-office") ),
					'dependency' => array(
						'element' => 'ids',
						'is_empty' => true
					),
					"group" => esc_html__('Query', 'micro-office'),
					"class" => "",
					"value" => 0,
					"type" => "textfield"
				),
				array(
					"param_name" => "orderby",
					"heading" => esc_html__("Post order by", "micro-office"),
					"description" => wp_kses_data( __("Select desired posts sorting method", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Query', 'micro-office'),
					"value" => array_flip(micro_office_get_sc_param('sorting')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "order",
					"heading" => esc_html__("Post order", "micro-office"),
					"description" => wp_kses_data( __("Select desired posts order", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Query', 'micro-office'),
					"value" => array_flip(micro_office_get_sc_param('ordering')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "only",
					"heading" => esc_html__("Select posts only", "micro-office"),
					"description" => wp_kses_data( __("Select posts only with reviews, videos, audios, thumbs or galleries", "micro-office") ),
					"class" => "",
					"group" => esc_html__('Query', 'micro-office'),
					"value" => array_flip(micro_office_get_sc_param('formats')),
					"type" => "dropdown"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_vc_width(),
				micro_office_vc_height(),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			),
		) );
		
		class WPBakeryShortCode_Trx_Blogger extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>