<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_search_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_search_theme_setup' );
	function micro_office_sc_search_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_search_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_search_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_search')) {	
	function micro_office_sc_search($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"style" => "",
			"state" => "",
			"ajax" => "",
			"title" => esc_html__('Search', 'micro-office'),
			"scheme" => "original",
			// Common params
			"id" => "",
			"class" => "",
			"animation" => "",
			"css" => "",
			"top" => "",
			"bottom" => "",
			"left" => "",
			"right" => ""
		), $atts)));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		if ($style == 'fullscreen') {
			if (empty($ajax)) $ajax = "no";
			if (empty($state)) $state = "closed";
		} else if ($style == 'expand') {
			if (empty($ajax)) $ajax = micro_office_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else if ($style == 'slide') {
			if (empty($ajax)) $ajax = micro_office_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "closed";
		} else {
			if (empty($ajax)) $ajax = micro_office_get_theme_option('use_ajax_search');
			if (empty($state)) $state = "fixed";
		}
		// Load core messages
		micro_office_enqueue_messages();
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') . ' class="search_wrap search_style_'.esc_attr($style).' search_state_'.esc_attr($state)
						. (micro_office_param_is_on($ajax) ? ' search_ajax' : '')
						. ($class ? ' '.esc_attr($class) : '')
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. '>
						<div class="search_form_wrap">
							<form role="search" method="get" class="search_form" action="' . esc_url(home_url('/')) . '">
								<button type="submit" class="search_submit sc_button" title="' . ($state=='closed' ? esc_attr__('Open search', 'micro-office') : esc_attr__('Start search', 'micro-office')) . '"><span class="icon-search-1"></span></button>
								<input type="text" class="search_field" placeholder="' . esc_attr($title) . '" value="' . esc_attr(get_search_query()) . '" name="s" />'
								. ($style == 'fullscreen' ? '<a class="search_close icon-cancel"></a>' : '')
							. '</form>
						</div>'
						. (micro_office_param_is_on($ajax) ? '<div class="search_results widget_area' . ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') . '"><a class="search_results_close icon-cancel"></a><div class="search_results_content"></div></div>' : '')
					. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_search', $atts, $content);
	}
	micro_office_require_shortcode('trx_search', 'micro_office_sc_search');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_search_reg_shortcodes' ) ) {
	
	function micro_office_sc_search_reg_shortcodes() {
	
		micro_office_sc_map("trx_search", array(
			"title" => esc_html__("Search", "micro-office"),
			"desc" => wp_kses_data( __("Show search form", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"style" => array(
					"title" => esc_html__("Style", "micro-office"),
					"desc" => wp_kses_data( __("Select style to display search field", "micro-office") ),
					"value" => "regular",
					"options" => micro_office_get_list_search_styles(),
					"type" => "checklist"
				),
				"state" => array(
					"title" => esc_html__("State", "micro-office"),
					"desc" => wp_kses_data( __("Select search field initial state", "micro-office") ),
					"value" => "fixed",
					"options" => array(
						"fixed"  => esc_html__('Fixed',  'micro-office'),
						"opened" => esc_html__('Opened', 'micro-office'),
						"closed" => esc_html__('Closed', 'micro-office')
					),
					"type" => "checklist"
				),
				"title" => array(
					"title" => esc_html__("Title", "micro-office"),
					"desc" => wp_kses_data( __("Title (placeholder) for the search field", "micro-office") ),
					"value" => esc_html__("Search &hellip;", 'micro-office'),
					"type" => "text"
				),
				"ajax" => array(
					"title" => esc_html__("AJAX", "micro-office"),
					"desc" => wp_kses_data( __("Search via AJAX or reload page", "micro-office") ),
					"value" => "yes",
					"options" => micro_office_get_sc_param('yes_no'),
					"type" => "switch"
				),
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
if ( !function_exists( 'micro_office_sc_search_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_search_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_search",
			"name" => esc_html__("Search form", "micro-office"),
			"description" => wp_kses_data( __("Insert search form", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_search',
			"class" => "trx_sc_single trx_sc_search",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "style",
					"heading" => esc_html__("Style", "micro-office"),
					"description" => wp_kses_data( __("Select style to display search field", "micro-office") ),
					"class" => "",
					"value" => micro_office_get_list_search_styles(),
					"type" => "dropdown"
				),
				array(
					"param_name" => "state",
					"heading" => esc_html__("State", "micro-office"),
					"description" => wp_kses_data( __("Select search field initial state", "micro-office") ),
					"class" => "",
					"value" => array(
						esc_html__('Fixed', 'micro-office')  => "fixed",
						esc_html__('Opened', 'micro-office') => "opened",
						esc_html__('Closed', 'micro-office') => "closed"
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title (placeholder) for the search field", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => esc_html__("Search &hellip;", 'micro-office'),
					"type" => "textfield"
				),
				array(
					"param_name" => "ajax",
					"heading" => esc_html__("AJAX", "micro-office"),
					"description" => wp_kses_data( __("Search via AJAX or reload page", "micro-office") ),
					"class" => "",
					"value" => array(esc_html__('Use AJAX search', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('animation'),
				micro_office_get_vc_param('css'),
				micro_office_get_vc_param('margin_top'),
				micro_office_get_vc_param('margin_bottom'),
				micro_office_get_vc_param('margin_left'),
				micro_office_get_vc_param('margin_right')
			)
		) );
		
		class WPBakeryShortCode_Trx_Search extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>