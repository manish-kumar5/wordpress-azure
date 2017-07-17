<?php
/**
 * Micro Office Framework: return lists
 *
 * @package micro_office
 * @since micro_office 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }



// Return styles list
if ( !function_exists( 'micro_office_get_list_styles' ) ) {
	function micro_office_get_list_styles($from=1, $to=2, $prepend_inherit=false) {
		$list = array();
		for ($i=$from; $i<=$to; $i++)
			$list[$i] = sprintf(esc_html__('Style %d', 'micro-office'), $i);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the shortcodes margins
if ( !function_exists( 'micro_office_get_list_margins' ) ) {
	function micro_office_get_list_margins($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_margins'))=='') {
			$list = array(
				'null'		=> esc_html__('0 (No margin)',	'micro-office'),
				'tiny'		=> esc_html__('Tiny',		'micro-office'),
				'small'		=> esc_html__('Small',		'micro-office'),
				'medium'	=> esc_html__('Medium',		'micro-office'),
				'large'		=> esc_html__('Large',		'micro-office'),
				'huge'		=> esc_html__('Huge',		'micro-office'),
				'tiny-'		=> esc_html__('Tiny (negative)',	'micro-office'),
				'small-'	=> esc_html__('Small (negative)',	'micro-office'),
				'medium-'	=> esc_html__('Medium (negative)',	'micro-office'),
				'large-'	=> esc_html__('Large (negative)',	'micro-office'),
				'huge-'		=> esc_html__('Huge (negative)',	'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_margins', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_margins', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the line styles
if ( !function_exists( 'micro_office_get_list_line_styles' ) ) {
	function micro_office_get_list_line_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_line_styles'))=='') {
			$list = array(
				'solid'	=> esc_html__('Solid', 'micro-office'),
				'dashed'=> esc_html__('Dashed', 'micro-office'),
				'dotted'=> esc_html__('Dotted', 'micro-office'),
				'double'=> esc_html__('Double', 'micro-office'),
				'image'	=> esc_html__('Image', 'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_line_styles', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_line_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the animations
if ( !function_exists( 'micro_office_get_list_animations' ) ) {
	function micro_office_get_list_animations($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_animations'))=='') {
			$list = array(
				'none'			=> esc_html__('- None -',	'micro-office'),
				'bounce'		=> esc_html__('Bounce',		'micro-office'),
				'elastic'		=> esc_html__('Elastic',	'micro-office'),
				'flash'			=> esc_html__('Flash',		'micro-office'),
				'flip'			=> esc_html__('Flip',		'micro-office'),
				'pulse'			=> esc_html__('Pulse',		'micro-office'),
				'rubberBand'	=> esc_html__('Rubber Band','micro-office'),
				'shake'			=> esc_html__('Shake',		'micro-office'),
				'swing'			=> esc_html__('Swing',		'micro-office'),
				'tada'			=> esc_html__('Tada',		'micro-office'),
				'wobble'		=> esc_html__('Wobble',		'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_animations', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_animations', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the enter animations
if ( !function_exists( 'micro_office_get_list_animations_in' ) ) {
	function micro_office_get_list_animations_in($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_animations_in'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'micro-office'),
				'bounceIn'			=> esc_html__('Bounce In',			'micro-office'),
				'bounceInUp'		=> esc_html__('Bounce In Up',		'micro-office'),
				'bounceInDown'		=> esc_html__('Bounce In Down',		'micro-office'),
				'bounceInLeft'		=> esc_html__('Bounce In Left',		'micro-office'),
				'bounceInRight'		=> esc_html__('Bounce In Right',	'micro-office'),
				'elastic'			=> esc_html__('Elastic In',			'micro-office'),
				'fadeIn'			=> esc_html__('Fade In',			'micro-office'),
				'fadeInUp'			=> esc_html__('Fade In Up',			'micro-office'),
				'fadeInUpSmall'		=> esc_html__('Fade In Up Small',	'micro-office'),
				'fadeInUpBig'		=> esc_html__('Fade In Up Big',		'micro-office'),
				'fadeInDown'		=> esc_html__('Fade In Down',		'micro-office'),
				'fadeInDownBig'		=> esc_html__('Fade In Down Big',	'micro-office'),
				'fadeInLeft'		=> esc_html__('Fade In Left',		'micro-office'),
				'fadeInLeftBig'		=> esc_html__('Fade In Left Big',	'micro-office'),
				'fadeInRight'		=> esc_html__('Fade In Right',		'micro-office'),
				'fadeInRightBig'	=> esc_html__('Fade In Right Big',	'micro-office'),
				'flipInX'			=> esc_html__('Flip In X',			'micro-office'),
				'flipInY'			=> esc_html__('Flip In Y',			'micro-office'),
				'lightSpeedIn'		=> esc_html__('Light Speed In',		'micro-office'),
				'rotateIn'			=> esc_html__('Rotate In',			'micro-office'),
				'rotateInUpLeft'	=> esc_html__('Rotate In Down Left','micro-office'),
				'rotateInUpRight'	=> esc_html__('Rotate In Up Right',	'micro-office'),
				'rotateInDownLeft'	=> esc_html__('Rotate In Up Left',	'micro-office'),
				'rotateInDownRight'	=> esc_html__('Rotate In Down Right','micro-office'),
				'rollIn'			=> esc_html__('Roll In',			'micro-office'),
				'slideInUp'			=> esc_html__('Slide In Up',		'micro-office'),
				'slideInDown'		=> esc_html__('Slide In Down',		'micro-office'),
				'slideInLeft'		=> esc_html__('Slide In Left',		'micro-office'),
				'slideInRight'		=> esc_html__('Slide In Right',		'micro-office'),
				'wipeInLeftTop'		=> esc_html__('Wipe In Left Top',	'micro-office'),
				'zoomIn'			=> esc_html__('Zoom In',			'micro-office'),
				'zoomInUp'			=> esc_html__('Zoom In Up',			'micro-office'),
				'zoomInDown'		=> esc_html__('Zoom In Down',		'micro-office'),
				'zoomInLeft'		=> esc_html__('Zoom In Left',		'micro-office'),
				'zoomInRight'		=> esc_html__('Zoom In Right',		'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_animations_in', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_animations_in', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the out animations
if ( !function_exists( 'micro_office_get_list_animations_out' ) ) {
	function micro_office_get_list_animations_out($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_animations_out'))=='') {
			$list = array(
				'none'				=> esc_html__('- None -',			'micro-office'),
				'bounceOut'			=> esc_html__('Bounce Out',			'micro-office'),
				'bounceOutUp'		=> esc_html__('Bounce Out Up',		'micro-office'),
				'bounceOutDown'		=> esc_html__('Bounce Out Down',	'micro-office'),
				'bounceOutLeft'		=> esc_html__('Bounce Out Left',	'micro-office'),
				'bounceOutRight'	=> esc_html__('Bounce Out Right',	'micro-office'),
				'fadeOut'			=> esc_html__('Fade Out',			'micro-office'),
				'fadeOutUp'			=> esc_html__('Fade Out Up',		'micro-office'),
				'fadeOutUpBig'		=> esc_html__('Fade Out Up Big',	'micro-office'),
				'fadeOutDown'		=> esc_html__('Fade Out Down',		'micro-office'),
				'fadeOutDownSmall'	=> esc_html__('Fade Out Down Small','micro-office'),
				'fadeOutDownBig'	=> esc_html__('Fade Out Down Big',	'micro-office'),
				'fadeOutLeft'		=> esc_html__('Fade Out Left',		'micro-office'),
				'fadeOutLeftBig'	=> esc_html__('Fade Out Left Big',	'micro-office'),
				'fadeOutRight'		=> esc_html__('Fade Out Right',		'micro-office'),
				'fadeOutRightBig'	=> esc_html__('Fade Out Right Big',	'micro-office'),
				'flipOutX'			=> esc_html__('Flip Out X',			'micro-office'),
				'flipOutY'			=> esc_html__('Flip Out Y',			'micro-office'),
				'hinge'				=> esc_html__('Hinge Out',			'micro-office'),
				'lightSpeedOut'		=> esc_html__('Light Speed Out',	'micro-office'),
				'rotateOut'			=> esc_html__('Rotate Out',			'micro-office'),
				'rotateOutUpLeft'	=> esc_html__('Rotate Out Down Left','micro-office'),
				'rotateOutUpRight'	=> esc_html__('Rotate Out Up Right','micro-office'),
				'rotateOutDownLeft'	=> esc_html__('Rotate Out Up Left',	'micro-office'),
				'rotateOutDownRight'=> esc_html__('Rotate Out Down Right','micro-office'),
				'rollOut'			=> esc_html__('Roll Out',			'micro-office'),
				'slideOutUp'		=> esc_html__('Slide Out Up',		'micro-office'),
				'slideOutDown'		=> esc_html__('Slide Out Down',		'micro-office'),
				'slideOutLeft'		=> esc_html__('Slide Out Left',		'micro-office'),
				'slideOutRight'		=> esc_html__('Slide Out Right',	'micro-office'),
				'zoomOut'			=> esc_html__('Zoom Out',			'micro-office'),
				'zoomOutUp'			=> esc_html__('Zoom Out Up',		'micro-office'),
				'zoomOutDown'		=> esc_html__('Zoom Out Down',		'micro-office'),
				'zoomOutLeft'		=> esc_html__('Zoom Out Left',		'micro-office'),
				'zoomOutRight'		=> esc_html__('Zoom Out Right',		'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_animations_out', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_animations_out', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return classes list for the specified animation
if (!function_exists('micro_office_get_animation_classes')) {
	function micro_office_get_animation_classes($animation, $speed='normal', $loop='none') {
		// speed:	fast=0.5s | normal=1s | slow=2s
		// loop:	none | infinite
		return micro_office_param_is_off($animation) ? '' : 'animated '.esc_attr($animation).' '.esc_attr($speed).(!micro_office_param_is_off($loop) ? ' '.esc_attr($loop) : '');
	}
}


// Return list of the main menu hover effects
if ( !function_exists( 'micro_office_get_list_menu_hovers' ) ) {
	function micro_office_get_list_menu_hovers($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_menu_hovers'))=='') {
			$list = array(
				'fade'			=> esc_html__('Fade',		'micro-office'),
				'slide_line'	=> esc_html__('Slide Line',	'micro-office'),
				'slide_box'		=> esc_html__('Slide Box',	'micro-office'),
				'zoom_line'		=> esc_html__('Zoom Line',	'micro-office'),
				'path_line'		=> esc_html__('Path Line',	'micro-office'),
				'roll_down'		=> esc_html__('Roll Down',	'micro-office'),
				'color_line'	=> esc_html__('Color Line',	'micro-office'),
				);
			$list = apply_filters('micro_office_filter_list_menu_hovers', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_menu_hovers', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the button's hover effects
if ( !function_exists( 'micro_office_get_list_button_hovers' ) ) {
	function micro_office_get_list_button_hovers($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_button_hovers'))=='') {
			$list = array(
				'default'		=> esc_html__('Default',			'micro-office'),
				'fade'			=> esc_html__('Fade',				'micro-office'),
				'slide_left'	=> esc_html__('Slide from Left',	'micro-office'),
				'slide_top'		=> esc_html__('Slide from Top',		'micro-office'),
				'arrow'			=> esc_html__('Arrow',				'micro-office'),
				);
			$list = apply_filters('micro_office_filter_list_button_hovers', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_button_hovers', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the input field's hover effects
if ( !function_exists( 'micro_office_get_list_input_hovers' ) ) {
	function micro_office_get_list_input_hovers($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_input_hovers'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'micro-office'),
				'accent'	=> esc_html__('Accented',	'micro-office'),
				'path'		=> esc_html__('Path',		'micro-office'),
				'jump'		=> esc_html__('Jump',		'micro-office'),
				'underline'	=> esc_html__('Underline',	'micro-office'),
				'iconed'	=> esc_html__('Iconed',		'micro-office'),
				);
			$list = apply_filters('micro_office_filter_list_input_hovers', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_input_hovers', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the search field's styles
if ( !function_exists( 'micro_office_get_list_search_styles' ) ) {
	function micro_office_get_list_search_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_search_styles'))=='') {
			$list = array(
				'default'	=> esc_html__('Default',	'micro-office'),
				'fullscreen'=> esc_html__('Fullscreen',	'micro-office'),
				'slide'		=> esc_html__('Slide',		'micro-office'),
				'expand'	=> esc_html__('Expand',		'micro-office'),
				);
			$list = apply_filters('micro_office_filter_list_search_styles', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_search_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of categories
if ( !function_exists( 'micro_office_get_list_categories' ) ) {
	function micro_office_get_list_categories($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_categories'))=='') {
			$list = array();
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => 'category',
				'pad_counts'               => false );
			$taxonomies = get_categories( $args );
			if (is_array($taxonomies) && count($taxonomies) > 0) {
				foreach ($taxonomies as $cat) {
					$list[$cat->term_id] = $cat->name;
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_categories', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of taxonomies
if ( !function_exists( 'micro_office_get_list_terms' ) ) {
	function micro_office_get_list_terms($prepend_inherit=false, $taxonomy='category') {
		if (($list = micro_office_storage_get('list_taxonomies_'.($taxonomy)))=='') {
			$list = array();
			if ( is_array($taxonomy) || taxonomy_exists($taxonomy) ) {
				$terms = get_terms( $taxonomy, array(
					'child_of'                 => 0,
					'parent'                   => '',
					'orderby'                  => 'name',
					'order'                    => 'ASC',
					'hide_empty'               => 0,
					'hierarchical'             => 1,
					'exclude'                  => '',
					'include'                  => '',
					'number'                   => '',
					'taxonomy'                 => $taxonomy,
					'pad_counts'               => false
					)
				);
			} else {
				$terms = micro_office_get_terms_by_taxonomy_from_db($taxonomy);
			}
			if (!is_wp_error( $terms ) && is_array($terms) && count($terms) > 0) {
				foreach ($terms as $cat) {
					$list[$cat->term_id] = $cat->name;	// . ($taxonomy!='category' ? ' /'.($cat->taxonomy).'/' : '');
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_taxonomies_'.($taxonomy), $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list of post's types
if ( !function_exists( 'micro_office_get_list_posts_types' ) ) {
	function micro_office_get_list_posts_types($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_posts_types'))=='') {
			// Return only theme inheritance supported post types
			$list = apply_filters('micro_office_filter_list_post_types', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_posts_types', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list post items from any post type and taxonomy
if ( !function_exists( 'micro_office_get_list_posts' ) ) {
	function micro_office_get_list_posts($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'post',
			'post_status'		=> 'publish',
			'taxonomy'			=> 'category',
			'taxonomy_value'	=> '',
			'posts_per_page'	=> -1,
			'orderby'			=> 'post_date',
			'order'				=> 'desc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));

		$hash = 'list_posts_'.($opt['post_type']).'_'.($opt['taxonomy']).'_'.($opt['taxonomy_value']).'_'.($opt['orderby']).'_'.($opt['order']).'_'.($opt['return']).'_'.($opt['posts_per_page']);
		if (($list = micro_office_storage_get($hash))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'micro-office');
			$args = array(
				'post_type' => $opt['post_type'],
				'post_status' => $opt['post_status'],
				'posts_per_page' => $opt['posts_per_page'],
				'ignore_sticky_posts' => true,
				'orderby'	=> $opt['orderby'],
				'order'		=> $opt['order']
			);
			if (!empty($opt['taxonomy_value'])) {
				$args['tax_query'] = array(
					array(
						'taxonomy' => $opt['taxonomy'],
						'field' => (int) $opt['taxonomy_value'] > 0 ? 'id' : 'slug',
						'terms' => $opt['taxonomy_value']
					)
				);
			}
			$posts = get_posts( $args );
			if (is_array($posts) && count($posts) > 0) {
				foreach ($posts as $post) {
					$list[$opt['return']=='id' ? $post->ID : $post->post_title] = $post->post_title;
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set($hash, $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list pages
if ( !function_exists( 'micro_office_get_list_pages' ) ) {
	function micro_office_get_list_pages($prepend_inherit=false, $opt=array()) {
		$opt = array_merge(array(
			'post_type'			=> 'page',
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'return'			=> 'id'
			), is_array($opt) ? $opt : array('post_type'=>$opt));
		return micro_office_get_list_posts($prepend_inherit, $opt);
	}
}


// Return list of registered users
if ( !function_exists( 'micro_office_get_list_users' ) ) {
	function micro_office_get_list_users($prepend_inherit=false, $roles=array('administrator', 'editor', 'author', 'contributor', 'shop_manager')) {
		if (($list = micro_office_storage_get('list_users'))=='') {
			$list = array();
			$list['none'] = esc_html__("- Not selected -", 'micro-office');
			$args = array(
				'orderby'	=> 'display_name',
				'order'		=> 'ASC' );
			$users = get_users( $args );
			if (is_array($users) && count($users) > 0) {
				foreach ($users as $user) {
					$accept = true;
					if (is_array($user->roles)) {
						if (is_array($user->roles) && count($user->roles) > 0) {
							$accept = false;
							foreach ($user->roles as $role) {
								if (in_array($role, $roles)) {
									$accept = true;
									break;
								}
							}
						}
					}
					if ($accept) $list[$user->user_login] = $user->display_name;
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_users', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return slider engines list, prepended inherit (if need)
if ( !function_exists( 'micro_office_get_list_sliders' ) ) {
	function micro_office_get_list_sliders($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_sliders'))=='') {
			$list = array(
				'swiper' => esc_html__("Posts slider (Swiper)", 'micro-office')
			);
			$list = apply_filters('micro_office_filter_list_sliders', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_sliders', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return slider controls list, prepended inherit (if need)
if ( !function_exists( 'micro_office_get_list_slider_controls' ) ) {
	function micro_office_get_list_slider_controls($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_slider_controls'))=='') {
			$list = array(
				'no'		=> esc_html__('None', 'micro-office'),
				'side'		=> esc_html__('Side', 'micro-office'),
				'bottom'	=> esc_html__('Bottom', 'micro-office'),
				'pagination'=> esc_html__('Pagination', 'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_slider_controls', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_slider_controls', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return slider controls classes
if ( !function_exists( 'micro_office_get_slider_controls_classes' ) ) {
	function micro_office_get_slider_controls_classes($controls) {
		if (micro_office_param_is_off($controls))	$classes = 'sc_slider_nopagination sc_slider_nocontrols';
		else if ($controls=='bottom')			$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_bottom';
		else if ($controls=='pagination')		$classes = 'sc_slider_pagination sc_slider_pagination_bottom sc_slider_nocontrols';
		else									$classes = 'sc_slider_nopagination sc_slider_controls sc_slider_controls_side';
		return $classes;
	}
}

// Return list with popup engines
if ( !function_exists( 'micro_office_get_list_popup_engines' ) ) {
	function micro_office_get_list_popup_engines($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_popup_engines'))=='') {
			$list = array(
				"pretty"	=> esc_html__("Pretty photo", 'micro-office'),
				"magnific"	=> esc_html__("Magnific popup", 'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_popup_engines', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_popup_engines', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return menus list, prepended inherit
if ( !function_exists( 'micro_office_get_list_menus' ) ) {
	function micro_office_get_list_menus($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_menus'))=='') {
			$list = array();
			$list['default'] = esc_html__("Default", 'micro-office');
			$menus = wp_get_nav_menus();
			if (is_array($menus) && count($menus) > 0) {
				foreach ($menus as $menu) {
					$list[$menu->slug] = $menu->name;
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_menus', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return custom sidebars list, prepended inherit and main sidebars item (if need)
if ( !function_exists( 'micro_office_get_list_sidebars' ) ) {
	function micro_office_get_list_sidebars($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_sidebars'))=='') {
			if (($list = micro_office_storage_get('registered_sidebars'))=='') $list = array();
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_sidebars', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return sidebars positions
if ( !function_exists( 'micro_office_get_list_sidebars_positions' ) ) {
	function micro_office_get_list_sidebars_positions($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_sidebars_positions'))=='') {
			$list = array(
				'none'  => esc_html__('Hide',  'micro-office'),
				'left'  => esc_html__('Left',  'micro-office'),
				'right' => esc_html__('Right', 'micro-office')
				);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_sidebars_positions', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return sidebars class
if ( !function_exists( 'micro_office_get_sidebar_class' ) ) {
	function micro_office_get_sidebar_class() {
		$sb_main = micro_office_get_custom_option('show_sidebar_main');
		$sb_outer = micro_office_get_custom_option('show_sidebar_outer');
		return (micro_office_param_is_off($sb_main) ? 'sidebar_hide' : 'sidebar_show sidebar_'.($sb_main))
				. ' ' . (micro_office_param_is_off($sb_outer) ? 'sidebar_outer_hide' : 'sidebar_outer_show sidebar_outer_'.($sb_outer));
	}
}

// Return body styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_body_styles' ) ) {
	function micro_office_get_list_body_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_body_styles'))=='') {
			$list = array(
				'boxed'	=> esc_html__('Boxed',		'micro-office'),
				'wide'	=> esc_html__('Wide',		'micro-office')
				);
			if (micro_office_get_theme_setting('allow_fullscreen')) {
				$list['fullwide']	= esc_html__('Fullwide',	'micro-office');
				$list['fullscreen']	= esc_html__('Fullscreen',	'micro-office');
			}
			$list = apply_filters('micro_office_filter_list_body_styles', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_body_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return templates list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates' ) ) {
	function micro_office_get_list_templates($mode='') {
		if (($list = micro_office_storage_get('list_templates_'.($mode)))=='') {
			$list = array();
			$tpl = micro_office_storage_get('registered_templates');
			if (is_array($tpl) && count($tpl) > 0) {
				foreach ($tpl as $k=>$v) {
					if ($mode=='' || in_array($mode, explode(',', $v['mode'])))
						$list[$k] = !empty($v['icon']) 
									? $v['icon'] 
									: (!empty($v['title']) 
										? $v['title'] 
										: micro_office_strtoproper($v['layout'])
										);
				}
			}
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_'.($mode), $list);
		}
		return $list;
	}
}

// Return blog styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates_blog' ) ) {
	function micro_office_get_list_templates_blog($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_templates_blog'))=='') {
			$list = micro_office_get_list_templates('blog');
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_blog', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return blogger styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates_blogger' ) ) {
	function micro_office_get_list_templates_blogger($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_templates_blogger'))=='') {
			$list = micro_office_array_merge(micro_office_get_list_templates('blogger'), micro_office_get_list_templates('blog'));
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_blogger', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return single page styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates_single' ) ) {
	function micro_office_get_list_templates_single($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_templates_single'))=='') {
			$list = micro_office_get_list_templates('single');
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_single', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return header styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates_header' ) ) {
	function micro_office_get_list_templates_header($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_templates_header'))=='') {
			$list = micro_office_get_list_templates('header');
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_header', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return form styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_templates_forms' ) ) {
	function micro_office_get_list_templates_forms($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_templates_forms'))=='') {
			$list = micro_office_get_list_templates('forms');
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_templates_forms', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return article styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_article_styles' ) ) {
	function micro_office_get_list_article_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_article_styles'))=='') {
			$list = array(
				"boxed"   => esc_html__('Boxed', 'micro-office'),
				"stretch" => esc_html__('Stretch', 'micro-office')
				);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_article_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return post-formats filters list, prepended inherit
if ( !function_exists( 'micro_office_get_list_post_formats_filters' ) ) {
	function micro_office_get_list_post_formats_filters($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_post_formats_filters'))=='') {
			$list = array(
				"no"      => esc_html__('All posts', 'micro-office'),
				"thumbs"  => esc_html__('With thumbs', 'micro-office'),
				"reviews" => esc_html__('With reviews', 'micro-office'),
				"video"   => esc_html__('With videos', 'micro-office'),
				"audio"   => esc_html__('With audios', 'micro-office'),
				"gallery" => esc_html__('With galleries', 'micro-office')
				);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_post_formats_filters', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return portfolio filters list, prepended inherit
if ( !function_exists( 'micro_office_get_list_portfolio_filters' ) ) {
	function micro_office_get_list_portfolio_filters($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_portfolio_filters'))=='') {
			$list = array(
				"hide"		=> esc_html__('Hide', 'micro-office'),
				"tags"		=> esc_html__('Tags', 'micro-office'),
				"categories"=> esc_html__('Categories', 'micro-office')
				);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_portfolio_filters', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return hover styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_hovers' ) ) {
	function micro_office_get_list_hovers($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_hovers'))=='') {
			$list = array();
			$list['circle effect1']  = esc_html__('Circle Effect 1',  'micro-office');
			$list['circle effect2']  = esc_html__('Circle Effect 2',  'micro-office');
			$list['circle effect3']  = esc_html__('Circle Effect 3',  'micro-office');
			$list['circle effect4']  = esc_html__('Circle Effect 4',  'micro-office');
			$list['circle effect5']  = esc_html__('Circle Effect 5',  'micro-office');
			$list['circle effect6']  = esc_html__('Circle Effect 6',  'micro-office');
			$list['circle effect7']  = esc_html__('Circle Effect 7',  'micro-office');
			$list['circle effect8']  = esc_html__('Circle Effect 8',  'micro-office');
			$list['circle effect9']  = esc_html__('Circle Effect 9',  'micro-office');
			$list['circle effect10'] = esc_html__('Circle Effect 10',  'micro-office');
			$list['circle effect11'] = esc_html__('Circle Effect 11',  'micro-office');
			$list['circle effect12'] = esc_html__('Circle Effect 12',  'micro-office');
			$list['circle effect13'] = esc_html__('Circle Effect 13',  'micro-office');
			$list['circle effect14'] = esc_html__('Circle Effect 14',  'micro-office');
			$list['circle effect15'] = esc_html__('Circle Effect 15',  'micro-office');
			$list['circle effect16'] = esc_html__('Circle Effect 16',  'micro-office');
			$list['circle effect17'] = esc_html__('Circle Effect 17',  'micro-office');
			$list['circle effect18'] = esc_html__('Circle Effect 18',  'micro-office');
			$list['circle effect19'] = esc_html__('Circle Effect 19',  'micro-office');
			$list['circle effect20'] = esc_html__('Circle Effect 20',  'micro-office');
			$list['square effect1']  = esc_html__('Square Effect 1',  'micro-office');
			$list['square effect2']  = esc_html__('Square Effect 2',  'micro-office');
			$list['square effect3']  = esc_html__('Square Effect 3',  'micro-office');
			$list['square effect5']  = esc_html__('Square Effect 5',  'micro-office');
			$list['square effect6']  = esc_html__('Square Effect 6',  'micro-office');
			$list['square effect7']  = esc_html__('Square Effect 7',  'micro-office');
			$list['square effect8']  = esc_html__('Square Effect 8',  'micro-office');
			$list['square effect9']  = esc_html__('Square Effect 9',  'micro-office');
			$list['square effect10'] = esc_html__('Square Effect 10',  'micro-office');
			$list['square effect11'] = esc_html__('Square Effect 11',  'micro-office');
			$list['square effect12'] = esc_html__('Square Effect 12',  'micro-office');
			$list['square effect13'] = esc_html__('Square Effect 13',  'micro-office');
			$list['square effect14'] = esc_html__('Square Effect 14',  'micro-office');
			$list['square effect15'] = esc_html__('Square Effect 15',  'micro-office');
			$list['square effect_dir']   = esc_html__('Square Effect Dir',   'micro-office');
			$list['square effect_shift'] = esc_html__('Square Effect Shift', 'micro-office');
			$list['square effect_book']  = esc_html__('Square Effect Book',  'micro-office');
			$list['square effect_more']  = esc_html__('Square Effect More',  'micro-office');
			$list['square effect_fade']  = esc_html__('Square Effect Fade',  'micro-office');
			$list['square effect_pull']  = esc_html__('Square Effect Pull',  'micro-office');
			$list['square effect_slide'] = esc_html__('Square Effect Slide', 'micro-office');
			$list['square effect_border'] = esc_html__('Square Effect Border', 'micro-office');
			$list = apply_filters('micro_office_filter_portfolio_hovers', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_hovers', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the blog counters
if ( !function_exists( 'micro_office_get_list_blog_counters' ) ) {
	function micro_office_get_list_blog_counters($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_blog_counters'))=='') {
			$list = array(
				'views'		=> esc_html__('Views', 'micro-office'),
				'likes'		=> esc_html__('Likes', 'micro-office'),
				'rating'	=> esc_html__('Rating', 'micro-office'),
				'comments'	=> esc_html__('Comments', 'micro-office')
				);
			$list = apply_filters('micro_office_filter_list_blog_counters', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_blog_counters', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list of the item sizes for the portfolio alter style, prepended inherit
if ( !function_exists( 'micro_office_get_list_alter_sizes' ) ) {
	function micro_office_get_list_alter_sizes($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_alter_sizes'))=='') {
			$list = array(
					'1_1' => esc_html__('1x1', 'micro-office'),
					'1_2' => esc_html__('1x2', 'micro-office'),
					'2_1' => esc_html__('2x1', 'micro-office'),
					'2_2' => esc_html__('2x2', 'micro-office'),
					'1_3' => esc_html__('1x3', 'micro-office'),
					'2_3' => esc_html__('2x3', 'micro-office'),
					'3_1' => esc_html__('3x1', 'micro-office'),
					'3_2' => esc_html__('3x2', 'micro-office'),
					'3_3' => esc_html__('3x3', 'micro-office')
					);
			$list = apply_filters('micro_office_filter_portfolio_alter_sizes', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_alter_sizes', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return extended hover directions list, prepended inherit
if ( !function_exists( 'micro_office_get_list_hovers_directions' ) ) {
	function micro_office_get_list_hovers_directions($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_hovers_directions'))=='') {
			$list = array(
				'left_to_right' => esc_html__('Left to Right',  'micro-office'),
				'right_to_left' => esc_html__('Right to Left',  'micro-office'),
				'top_to_bottom' => esc_html__('Top to Bottom',  'micro-office'),
				'bottom_to_top' => esc_html__('Bottom to Top',  'micro-office'),
				'scale_up'      => esc_html__('Scale Up',  'micro-office'),
				'scale_down'    => esc_html__('Scale Down',  'micro-office'),
				'scale_down_up' => esc_html__('Scale Down-Up',  'micro-office'),
				'from_left_and_right' => esc_html__('From Left and Right',  'micro-office'),
				'from_top_and_bottom' => esc_html__('From Top and Bottom',  'micro-office')
			);
			$list = apply_filters('micro_office_filter_portfolio_hovers_directions', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_hovers_directions', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the label positions in the custom forms
if ( !function_exists( 'micro_office_get_list_label_positions' ) ) {
	function micro_office_get_list_label_positions($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_label_positions'))=='') {
			$list = array(
				'top'		=> esc_html__('Top',		'micro-office'),
				'bottom'	=> esc_html__('Bottom',		'micro-office'),
				'left'		=> esc_html__('Left',		'micro-office'),
				'over'		=> esc_html__('Over',		'micro-office')
			);
			$list = apply_filters('micro_office_filter_label_positions', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_label_positions', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the bg image positions
if ( !function_exists( 'micro_office_get_list_bg_image_positions' ) ) {
	function micro_office_get_list_bg_image_positions($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_bg_image_positions'))=='') {
			$list = array(
				'left top'	   => esc_html__('Left Top', 'micro-office'),
				'center top'   => esc_html__("Center Top", 'micro-office'),
				'right top'    => esc_html__("Right Top", 'micro-office'),
				'left center'  => esc_html__("Left Center", 'micro-office'),
				'center center'=> esc_html__("Center Center", 'micro-office'),
				'right center' => esc_html__("Right Center", 'micro-office'),
				'left bottom'  => esc_html__("Left Bottom", 'micro-office'),
				'center bottom'=> esc_html__("Center Bottom", 'micro-office'),
				'right bottom' => esc_html__("Right Bottom", 'micro-office')
			);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_bg_image_positions', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the bg image repeat
if ( !function_exists( 'micro_office_get_list_bg_image_repeats' ) ) {
	function micro_office_get_list_bg_image_repeats($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_bg_image_repeats'))=='') {
			$list = array(
				'repeat'	=> esc_html__('Repeat', 'micro-office'),
				'repeat-x'	=> esc_html__('Repeat X', 'micro-office'),
				'repeat-y'	=> esc_html__('Repeat Y', 'micro-office'),
				'no-repeat'	=> esc_html__('No Repeat', 'micro-office')
			);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_bg_image_repeats', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the bg image attachment
if ( !function_exists( 'micro_office_get_list_bg_image_attachments' ) ) {
	function micro_office_get_list_bg_image_attachments($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_bg_image_attachments'))=='') {
			$list = array(
				'scroll'	=> esc_html__('Scroll', 'micro-office'),
				'fixed'		=> esc_html__('Fixed', 'micro-office'),
				'local'		=> esc_html__('Local', 'micro-office')
			);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_bg_image_attachments', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}


// Return list of the bg tints
if ( !function_exists( 'micro_office_get_list_bg_tints' ) ) {
	function micro_office_get_list_bg_tints($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_bg_tints'))=='') {
			$list = array(
				'white'	=> esc_html__('White', 'micro-office'),
				'light'	=> esc_html__('Light', 'micro-office'),
				'dark'	=> esc_html__('Dark', 'micro-office')
			);
			$list = apply_filters('micro_office_filter_bg_tints', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_bg_tints', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return custom fields types list, prepended inherit
if ( !function_exists( 'micro_office_get_list_field_types' ) ) {
	function micro_office_get_list_field_types($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_field_types'))=='') {
			$list = array(
				'text'     => esc_html__('Text',  'micro-office'),
				'textarea' => esc_html__('Text Area','micro-office'),
				'password' => esc_html__('Password',  'micro-office'),
				'radio'    => esc_html__('Radio',  'micro-office'),
				'checkbox' => esc_html__('Checkbox',  'micro-office'),
				'select'   => esc_html__('Select',  'micro-office'),
				'date'     => esc_html__('Date','micro-office'),
				'time'     => esc_html__('Time','micro-office'),
				'button'   => esc_html__('Button','micro-office')
			);
			$list = apply_filters('micro_office_filter_field_types', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_field_types', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return Google map styles
if ( !function_exists( 'micro_office_get_list_googlemap_styles' ) ) {
	function micro_office_get_list_googlemap_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_googlemap_styles'))=='') {
			$list = array(
				'default' => esc_html__('Default', 'micro-office')
			);
			$list = apply_filters('micro_office_filter_googlemap_styles', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_googlemap_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return images list
if (!function_exists('micro_office_get_list_images')) {	
	function micro_office_get_list_images($folder, $ext='', $only_names=false) {
		return function_exists('trx_utils_get_folder_list') ? trx_utils_get_folder_list($folder, $ext, $only_names) : array();
	}
}

// Return iconed classes list
if ( !function_exists( 'micro_office_get_list_icons' ) ) {
	function micro_office_get_list_icons($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_icons'))=='') {
			$list = micro_office_parse_icons_classes(micro_office_get_file_dir("css/fontello/css/fontello-codes.css"));
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_icons', $list);
		}
		return $prepend_inherit ? array_merge(array('inherit'), $list) : $list;
	}
}

// Return socials list
if ( !function_exists( 'micro_office_get_list_socials' ) ) {
	function micro_office_get_list_socials($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_socials'))=='') {
			$list = micro_office_get_list_images("fw/images/socials", "png");
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_socials', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with 'Yes' and 'No' items
if ( !function_exists( 'micro_office_get_list_yesno' ) ) {
	function micro_office_get_list_yesno($prepend_inherit=false) {
		$list = array(
			'yes' => esc_html__("Yes", 'micro-office'),
			'no'  => esc_html__("No", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with 'On' and 'Of' items
if ( !function_exists( 'micro_office_get_list_onoff' ) ) {
	function micro_office_get_list_onoff($prepend_inherit=false) {
		$list = array(
			"on" => esc_html__("On", 'micro-office'),
			"off" => esc_html__("Off", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with 'Show' and 'Hide' items
if ( !function_exists( 'micro_office_get_list_showhide' ) ) {
	function micro_office_get_list_showhide($prepend_inherit=false) {
		$list = array(
			"show" => esc_html__("Show", 'micro-office'),
			"hide" => esc_html__("Hide", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with 'Ascending' and 'Descending' items
if ( !function_exists( 'micro_office_get_list_orderings' ) ) {
	function micro_office_get_list_orderings($prepend_inherit=false) {
		$list = array(
			"asc" => esc_html__("Ascending", 'micro-office'),
			"desc" => esc_html__("Descending", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with 'Horizontal' and 'Vertical' items
if ( !function_exists( 'micro_office_get_list_directions' ) ) {
	function micro_office_get_list_directions($prepend_inherit=false) {
		$list = array(
			"horizontal" => esc_html__("Horizontal", 'micro-office'),
			"vertical" => esc_html__("Vertical", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with item's shapes
if ( !function_exists( 'micro_office_get_list_shapes' ) ) {
	function micro_office_get_list_shapes($prepend_inherit=false) {
		$list = array(
			"round"  => esc_html__("Round", 'micro-office'),
			"square" => esc_html__("Square", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with item's sizes
if ( !function_exists( 'micro_office_get_list_sizes' ) ) {
	function micro_office_get_list_sizes($prepend_inherit=false) {
		$list = array(
			"tiny"   => esc_html__("Tiny", 'micro-office'),
			"small"  => esc_html__("Small", 'micro-office'),
			"medium" => esc_html__("Medium", 'micro-office'),
			"large"  => esc_html__("Large", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with slider (scroll) controls positions
if ( !function_exists( 'micro_office_get_list_controls' ) ) {
	function micro_office_get_list_controls($prepend_inherit=false) {
		$list = array(
			"hide" => esc_html__("Hide", 'micro-office'),
			"side" => esc_html__("Side", 'micro-office'),
			"bottom" => esc_html__("Bottom", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with float items
if ( !function_exists( 'micro_office_get_list_floats' ) ) {
	function micro_office_get_list_floats($prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'micro-office'),
			"left" => esc_html__("Float Left", 'micro-office'),
			"right" => esc_html__("Float Right", 'micro-office')
		);
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with alignment items
if ( !function_exists( 'micro_office_get_list_alignments' ) ) {
	function micro_office_get_list_alignments($justify=false, $prepend_inherit=false) {
		$list = array(
			"none" => esc_html__("None", 'micro-office'),
			"left" => esc_html__("Left", 'micro-office'),
			"center" => esc_html__("Center", 'micro-office'),
			"right" => esc_html__("Right", 'micro-office')
		);
		if ($justify) $list["justify"] = esc_html__("Justify", 'micro-office');
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with horizontal positions
if ( !function_exists( 'micro_office_get_list_hpos' ) ) {
	function micro_office_get_list_hpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['left'] = esc_html__("Left", 'micro-office');
		if ($center) $list['center'] = esc_html__("Center", 'micro-office');
		$list['right'] = esc_html__("Right", 'micro-office');
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with vertical positions
if ( !function_exists( 'micro_office_get_list_vpos' ) ) {
	function micro_office_get_list_vpos($prepend_inherit=false, $center=false) {
		$list = array();
		$list['top'] = esc_html__("Top", 'micro-office');
		if ($center) $list['center'] = esc_html__("Center", 'micro-office');
		$list['bottom'] = esc_html__("Bottom", 'micro-office');
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return sorting list items
if ( !function_exists( 'micro_office_get_list_sortings' ) ) {
	function micro_office_get_list_sortings($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_sortings'))=='') {
			$list = array(
				"date" => esc_html__("Date", 'micro-office'),
				"title" => esc_html__("Alphabetically", 'micro-office'),
				"views" => esc_html__("Popular (views count)", 'micro-office'),
				"comments" => esc_html__("Most commented (comments count)", 'micro-office'),
				"author_rating" => esc_html__("Author rating", 'micro-office'),
				"users_rating" => esc_html__("Visitors (users) rating", 'micro-office'),
				"random" => esc_html__("Random", 'micro-office')
			);
			$list = apply_filters('micro_office_filter_list_sortings', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_sortings', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list with columns widths
if ( !function_exists( 'micro_office_get_list_columns' ) ) {
	function micro_office_get_list_columns($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_columns'))=='') {
			$list = array(
				"none" => esc_html__("None", 'micro-office'),
				"1_1" => esc_html__("100%", 'micro-office'),
				"1_2" => esc_html__("1/2", 'micro-office'),
				"1_3" => esc_html__("1/3", 'micro-office'),
				"2_3" => esc_html__("2/3", 'micro-office'),
				"1_4" => esc_html__("1/4", 'micro-office'),
				"3_4" => esc_html__("3/4", 'micro-office'),
				"1_5" => esc_html__("1/5", 'micro-office'),
				"2_5" => esc_html__("2/5", 'micro-office'),
				"3_5" => esc_html__("3/5", 'micro-office'),
				"4_5" => esc_html__("4/5", 'micro-office'),
				"1_6" => esc_html__("1/6", 'micro-office'),
				"5_6" => esc_html__("5/6", 'micro-office'),
				"1_7" => esc_html__("1/7", 'micro-office'),
				"2_7" => esc_html__("2/7", 'micro-office'),
				"3_7" => esc_html__("3/7", 'micro-office'),
				"4_7" => esc_html__("4/7", 'micro-office'),
				"5_7" => esc_html__("5/7", 'micro-office'),
				"6_7" => esc_html__("6/7", 'micro-office'),
				"1_8" => esc_html__("1/8", 'micro-office'),
				"3_8" => esc_html__("3/8", 'micro-office'),
				"5_8" => esc_html__("5/8", 'micro-office'),
				"7_8" => esc_html__("7/8", 'micro-office'),
				"1_9" => esc_html__("1/9", 'micro-office'),
				"2_9" => esc_html__("2/9", 'micro-office'),
				"4_9" => esc_html__("4/9", 'micro-office'),
				"5_9" => esc_html__("5/9", 'micro-office'),
				"7_9" => esc_html__("7/9", 'micro-office'),
				"8_9" => esc_html__("8/9", 'micro-office'),
				"1_10"=> esc_html__("1/10", 'micro-office'),
				"3_10"=> esc_html__("3/10", 'micro-office'),
				"7_10"=> esc_html__("7/10", 'micro-office'),
				"9_10"=> esc_html__("9/10", 'micro-office'),
				"1_11"=> esc_html__("1/11", 'micro-office'),
				"2_11"=> esc_html__("2/11", 'micro-office'),
				"3_11"=> esc_html__("3/11", 'micro-office'),
				"4_11"=> esc_html__("4/11", 'micro-office'),
				"5_11"=> esc_html__("5/11", 'micro-office'),
				"6_11"=> esc_html__("6/11", 'micro-office'),
				"7_11"=> esc_html__("7/11", 'micro-office'),
				"8_11"=> esc_html__("8/11", 'micro-office'),
				"9_11"=> esc_html__("9/11", 'micro-office'),
				"10_11"=> esc_html__("10/11", 'micro-office'),
				"1_12"=> esc_html__("1/12", 'micro-office'),
				"5_12"=> esc_html__("5/12", 'micro-office'),
				"7_12"=> esc_html__("7/12", 'micro-office'),
				"10_12"=> esc_html__("10/12", 'micro-office'),
				"11_12"=> esc_html__("11/12", 'micro-office')
			);
			$list = apply_filters('micro_office_filter_list_columns', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_columns', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return list of locations for the dedicated content
if ( !function_exists( 'micro_office_get_list_dedicated_locations' ) ) {
	function micro_office_get_list_dedicated_locations($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_dedicated_locations'))=='') {
			$list = array(
				"default" => esc_html__('As in the post defined', 'micro-office'),
				"center"  => esc_html__('Above the text of the post', 'micro-office'),
				"left"    => esc_html__('To the left the text of the post', 'micro-office'),
				"right"   => esc_html__('To the right the text of the post', 'micro-office'),
				"alter"   => esc_html__('Alternates for each post', 'micro-office')
			);
			$list = apply_filters('micro_office_filter_list_dedicated_locations', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_dedicated_locations', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return post-format name
if ( !function_exists( 'micro_office_get_post_format_name' ) ) {
	function micro_office_get_post_format_name($format, $single=true) {
		$name = '';
		if ($format=='gallery')		$name = $single ? esc_html__('gallery', 'micro-office') : esc_html__('galleries', 'micro-office');
		else if ($format=='video')	$name = $single ? esc_html__('video', 'micro-office') : esc_html__('videos', 'micro-office');
		else if ($format=='audio')	$name = $single ? esc_html__('audio', 'micro-office') : esc_html__('audios', 'micro-office');
		else if ($format=='image')	$name = $single ? esc_html__('image', 'micro-office') : esc_html__('images', 'micro-office');
		else if ($format=='quote')	$name = $single ? esc_html__('quote', 'micro-office') : esc_html__('quotes', 'micro-office');
		else if ($format=='link')	$name = $single ? esc_html__('link', 'micro-office') : esc_html__('links', 'micro-office');
		else if ($format=='status')	$name = $single ? esc_html__('status', 'micro-office') : esc_html__('statuses', 'micro-office');
		else if ($format=='aside')	$name = $single ? esc_html__('aside', 'micro-office') : esc_html__('asides', 'micro-office');
		else if ($format=='chat')	$name = $single ? esc_html__('chat', 'micro-office') : esc_html__('chats', 'micro-office');
		else						$name = $single ? esc_html__('standard', 'micro-office') : esc_html__('standards', 'micro-office');
		return apply_filters('micro_office_filter_list_post_format_name', $name, $format);
	}
}

// Return post-format icon name (from Fontello library)
if ( !function_exists( 'micro_office_get_post_format_icon' ) ) {
	function micro_office_get_post_format_icon($format) {
		$icon = 'icon-';
		if ($format=='gallery')		$icon .= 'pictures';
		else if ($format=='video')	$icon .= 'video';
		else if ($format=='audio')	$icon .= 'note';
		else if ($format=='image')	$icon .= 'picture';
		else if ($format=='quote')	$icon .= 'quote';
		else if ($format=='link')	$icon .= 'link';
		else if ($format=='status')	$icon .= 'comment';
		else if ($format=='aside')	$icon .= 'doc-text';
		else if ($format=='chat')	$icon .= 'chat';
		else						$icon .= 'book-open';
		return apply_filters('micro_office_filter_list_post_format_icon', $icon, $format);
	}
}

// Return fonts styles list, prepended inherit
if ( !function_exists( 'micro_office_get_list_fonts_styles' ) ) {
	function micro_office_get_list_fonts_styles($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_fonts_styles'))=='') {
			$list = array(
				'i' => esc_html__('I','micro-office'),
				'u' => esc_html__('U', 'micro-office')
			);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_fonts_styles', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return Google fonts list
if ( !function_exists( 'micro_office_get_list_fonts' ) ) {
	function micro_office_get_list_fonts($prepend_inherit=false) {
		if (($list = micro_office_storage_get('list_fonts'))=='') {
			$list = array();
			$list = micro_office_array_merge($list, micro_office_get_list_font_faces());
			$list['Open Sans'] = array(
					'family'=>'sans-serif',																						// (required) font family
					'css'=>micro_office_get_file_url('/css/font-face/Open_Sans/stylesheet.css')									// (optional) if you use custom font-face
			);
			$list = micro_office_array_merge($list, array(
				'Advent Pro' => array('family'=>'sans-serif'),
				'Alegreya Sans' => array('family'=>'sans-serif'),
				'Arimo' => array('family'=>'sans-serif'),
				'Asap' => array('family'=>'sans-serif'),
				'Averia Sans Libre' => array('family'=>'cursive'),
				'Averia Serif Libre' => array('family'=>'cursive'),
				'Bree Serif' => array('family'=>'serif',),
				'Cabin' => array('family'=>'sans-serif'),
				'Cabin Condensed' => array('family'=>'sans-serif'),
				'Caudex' => array('family'=>'serif'),
				'Comfortaa' => array('family'=>'cursive'),
				'Cousine' => array('family'=>'sans-serif'),
				'Crimson Text' => array('family'=>'serif'),
				'Cuprum' => array('family'=>'sans-serif'),
				'Dosis' => array('family'=>'sans-serif'),
				'Economica' => array('family'=>'sans-serif'),
				'Exo' => array('family'=>'sans-serif'),
				'Expletus Sans' => array('family'=>'cursive'),
				'Karla' => array('family'=>'sans-serif'),
				'Lato' => array('family'=>'sans-serif'),
				'Lekton' => array('family'=>'sans-serif'),
				'Lobster Two' => array('family'=>'cursive'),
				'Maven Pro' => array('family'=>'sans-serif'),
				'Merriweather' => array('family'=>'serif'),
				'Montserrat' => array('family'=>'sans-serif'),
				'Neuton' => array('family'=>'serif'),
				'Noticia Text' => array('family'=>'serif'),
				'Old Standard TT' => array('family'=>'serif'),
				'Orbitron' => array('family'=>'sans-serif'),
				'Oswald' => array('family'=>'sans-serif'),
				'Overlock' => array('family'=>'cursive'),
				'Oxygen' => array('family'=>'sans-serif'),
				'Philosopher' => array('family'=>'serif'),
				'PT Serif' => array('family'=>'serif'),
				'Puritan' => array('family'=>'sans-serif'),
				'Raleway' => array('family'=>'sans-serif'),
				'Roboto' => array('family'=>'sans-serif'),
				'Roboto Slab' => array('family'=>'sans-serif'),
				'Roboto Condensed' => array('family'=>'sans-serif'),
				'Rosario' => array('family'=>'sans-serif'),
				'Share' => array('family'=>'cursive'),
				'Signika' => array('family'=>'sans-serif'),
				'Signika Negative' => array('family'=>'sans-serif'),
				'Source Sans Pro' => array('family'=>'sans-serif'),
				'Tinos' => array('family'=>'serif'),
				'Ubuntu' => array('family'=>'sans-serif'),
				'Vollkorn' => array('family'=>'serif')
				)
			);
			$list = apply_filters('micro_office_filter_list_fonts', $list);
			if (micro_office_get_theme_setting('use_list_cache')) micro_office_storage_set('list_fonts', $list);
		}
		return $prepend_inherit ? micro_office_array_merge(array('inherit' => esc_html__("Inherit", 'micro-office')), $list) : $list;
	}
}

// Return Custom font-face list
if ( !function_exists( 'micro_office_get_list_font_faces' ) ) {
	function micro_office_get_list_font_faces($prepend_inherit=false) {
		static $list = false;
		if (is_array($list)) return $list;
		$fonts = micro_office_storage_get('required_custom_fonts');
		$list = array();
		if (is_array($fonts)) {
			foreach ($fonts as $font) {
				if (($url = micro_office_get_file_url('css/font-face/'.trim($font).'/stylesheet.css'))!='') {
					$list[sprintf(esc_html__('%s (uploaded font)', 'micro-office'), $font)] = array('css' => $url);
				}
			}
		}
		return $list;
	}
}
?>