<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_twitter_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_twitter_theme_setup' );
	function micro_office_sc_twitter_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_twitter_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_twitter_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

if (!function_exists('micro_office_sc_twitter')) {	
	function micro_office_sc_twitter($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"user" => "",
			"consumer_key" => "",
			"consumer_secret" => "",
			"token_key" => "",
			"token_secret" => "",
			"count" => "3",
			"controls" => "yes",
			"interval" => "",
			"autoheight" => "no",
			"align" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
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
	
		$twitter_username = $user ? $user : micro_office_get_theme_option('twitter_username');
		$twitter_consumer_key = $consumer_key ? $consumer_key : micro_office_get_theme_option('twitter_consumer_key');
		$twitter_consumer_secret = $consumer_secret ? $consumer_secret : micro_office_get_theme_option('twitter_consumer_secret');
		$twitter_token_key = $token_key ? $token_key : micro_office_get_theme_option('twitter_token_key');
		$twitter_token_secret = $token_secret ? $token_secret : micro_office_get_theme_option('twitter_token_secret');
		$twitter_count = max(1, $count ? $count : intval(micro_office_get_theme_option('twitter_count')));
	
		if (empty($id)) $id = "sc_testimonials_".str_replace('.', '', mt_rand());
		if (empty($width)) $width = "100%";
		if (!empty($height) && micro_office_param_is_on($autoheight)) $autoheight = "no";
		if (empty($interval)) $interval = mt_rand(5000, 10000);
	
		if ($bg_image > 0) {
			$attach = wp_get_attachment_image_src( $bg_image, 'full' );
			if (isset($attach[0]) && $attach[0]!='')
				$bg_image = $attach[0];
		}
	
		if ($bg_overlay > 0) {
			if ($bg_color=='') $bg_color = micro_office_get_scheme_color('bg');
			$rgb = micro_office_hex2rgb($bg_color);
		}
		
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$ws = micro_office_get_css_dimensions_from_values($width);
		$hs = micro_office_get_css_dimensions_from_values('', $height);
	
		$css .= ($hs) . ($ws);
	
		$output = '';
	
		if (!empty($twitter_consumer_key) && !empty($twitter_consumer_secret) && !empty($twitter_token_key) && !empty($twitter_token_secret)) {
			$data = micro_office_get_twitter_data(array(
				'mode'            => 'user_timeline',
				'consumer_key'    => $twitter_consumer_key,
				'consumer_secret' => $twitter_consumer_secret,
				'token'           => $twitter_token_key,
				'secret'          => $twitter_token_secret
				)
			);
			if ($data && isset($data[0]['text'])) {
				micro_office_enqueue_slider('swiper');
				$output = ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || micro_office_strlen($bg_texture)>2 || ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme))
						? '<div class="sc_twitter_wrap sc_section'
								. ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
								. ($align && $align!='none' && !micro_office_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							.' style="'
								. ($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
								. ($bg_image !== '' ? 'background-image:url('.esc_url($bg_image).');' : '')
								. '"'
							. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
							. '>'
							. '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
									. ' style="' 
										. ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
										. (micro_office_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
										. '"'
										. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
										. '>' 
						: '')
						. '<div class="sc_twitter'
								. (!empty($class) ? ' '.esc_attr($class) : '')
								. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && $align && $align!='none' && !micro_office_param_is_inherit($align) ? ' align' . esc_attr($align) : '')
								. '"'
							. ($bg_color=='' && $bg_image=='' && $bg_overlay==0 && ($bg_texture=='' || $bg_texture=='0') && !micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
							. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
							. '>'
								. '<div class="sc_slider_swiper sc_slider_nopagination swiper-slider-container'
										. (micro_office_param_is_on($controls) ? ' sc_slider_controls' : ' sc_slider_nocontrols')
										. (micro_office_param_is_on($autoheight) ? ' sc_slider_height_auto' : '')
										. ($hs ? ' sc_slider_height_fixed' : '')
										. '"'
									. (!empty($width) && micro_office_strpos($width, '%')===false ? ' data-old-width="' . esc_attr($width) . '"' : '')
									. (!empty($height) && micro_office_strpos($height, '%')===false ? ' data-old-height="' . esc_attr($height) . '"' : '')
									. ((int) $interval > 0 ? ' data-interval="'.esc_attr($interval).'"' : '')
									. '>'
									. '<div class="slides swiper-wrapper">';
				$cnt = 0;
				if (is_array($data) && count($data) > 0) {
					foreach ($data as $tweet) {
						if (micro_office_substr($tweet['text'], 0, 1)=='@') continue;
							$output .= '<div class="swiper-slide" data-style="'.esc_attr(($ws).($hs)).'" style="'.esc_attr(($ws).($hs)).'">'
										. '<div class="sc_twitter_item">'
											. '<span class="sc_twitter_icon icon-twitter"></span>'
											. '<div class="sc_twitter_content">'
												. '<a href="' . esc_url('https://twitter.com/'.($twitter_username)).'" class="sc_twitter_author" target="_blank">@' . esc_html($tweet['user']['screen_name']) . '</a> '
												. force_balance_tags(micro_office_prepare_twitter_text($tweet))
											. '</div>'
										. '</div>'
									. '</div>';
						if (++$cnt >= $twitter_count) break;
					}
				}
				$output .= '</div>'
						. '<div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>'
						. '</div>'
					. '</div>'
					. ($bg_color!='' || $bg_image!='' || $bg_overlay>0 || $bg_texture>0 || micro_office_strlen($bg_texture)>2
						?  '</div></div>'
						: '');
			}
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_twitter', $atts, $content);
	}
	micro_office_require_shortcode('trx_twitter', 'micro_office_sc_twitter');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_twitter_reg_shortcodes' ) ) {
	
	function micro_office_sc_twitter_reg_shortcodes() {
	
		micro_office_sc_map("trx_twitter", array(
			"title" => esc_html__("Twitter", "micro-office"),
			"desc" => wp_kses_data( __("Insert twitter feed into post (page)", "micro-office") ),
			"decorate" => false,
			"container" => false,
			"params" => array(
				"user" => array(
					"title" => esc_html__("Twitter Username", "micro-office"),
					"desc" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"consumer_key" => array(
					"title" => esc_html__("Consumer Key", "micro-office"),
					"desc" => wp_kses_data( __("Consumer Key from the twitter account", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"consumer_secret" => array(
					"title" => esc_html__("Consumer Secret", "micro-office"),
					"desc" => wp_kses_data( __("Consumer Secret from the twitter account", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"token_key" => array(
					"title" => esc_html__("Token Key", "micro-office"),
					"desc" => wp_kses_data( __("Token Key from the twitter account", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"token_secret" => array(
					"title" => esc_html__("Token Secret", "micro-office"),
					"desc" => wp_kses_data( __("Token Secret from the twitter account", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"count" => array(
					"title" => esc_html__("Tweets number", "micro-office"),
					"desc" => wp_kses_data( __("Tweets number to show", "micro-office") ),
					"divider" => true,
					"value" => 3,
					"max" => 20,
					"min" => 1,
					"type" => "spinner"
				),
				"controls" => array(
					"title" => esc_html__("Show arrows", "micro-office"),
					"desc" => wp_kses_data( __("Show control buttons", "micro-office") ),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"interval" => array(
					"title" => esc_html__("Tweets change interval", "micro-office"),
					"desc" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", "micro-office") ),
					"value" => 7000,
					"step" => 500,
					"min" => 0,
					"type" => "spinner"
				),
				"align" => array(
					"title" => esc_html__("Alignment", "micro-office"),
					"desc" => wp_kses_data( __("Alignment of the tweets block", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
				),
				"autoheight" => array(
					"title" => esc_html__("Autoheight", "micro-office"),
					"desc" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", "micro-office") ),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"scheme" => array(
					"title" => esc_html__("Color scheme", "micro-office"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('schemes')
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "micro-office"),
					"desc" => wp_kses_data( __("Any background color for this section", "micro-office") ),
					"value" => "",
					"type" => "color"
				),
				"bg_image" => array(
					"title" => esc_html__("Background image URL", "micro-office"),
					"desc" => wp_kses_data( __("Select or upload image or write URL from other site for the background", "micro-office") ),
					"readonly" => false,
					"value" => "",
					"type" => "media"
				),
				"bg_overlay" => array(
					"title" => esc_html__("Overlay", "micro-office"),
					"desc" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", "micro-office") ),
					"min" => "0",
					"max" => "1",
					"step" => "0.1",
					"value" => "0",
					"type" => "spinner"
				),
				"bg_texture" => array(
					"title" => esc_html__("Texture", "micro-office"),
					"desc" => wp_kses_data( __("Predefined texture style from 1 to 11. 0 - without texture.", "micro-office") ),
					"min" => "0",
					"max" => "11",
					"step" => "1",
					"value" => "0",
					"type" => "spinner"
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
if ( !function_exists( 'micro_office_sc_twitter_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_twitter_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_twitter",
			"name" => esc_html__("Twitter", "micro-office"),
			"description" => wp_kses_data( __("Insert twitter feed into post (page)", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_twitter',
			"class" => "trx_sc_single trx_sc_twitter",
			"content_element" => true,
			"is_container" => false,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "user",
					"heading" => esc_html__("Twitter Username", "micro-office"),
					"description" => wp_kses_data( __("Your username in the twitter account. If empty - get it from Theme Options.", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_key",
					"heading" => esc_html__("Consumer Key", "micro-office"),
					"description" => wp_kses_data( __("Consumer Key from the twitter account", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "consumer_secret",
					"heading" => esc_html__("Consumer Secret", "micro-office"),
					"description" => wp_kses_data( __("Consumer Secret from the twitter account", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_key",
					"heading" => esc_html__("Token Key", "micro-office"),
					"description" => wp_kses_data( __("Token Key from the twitter account", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "token_secret",
					"heading" => esc_html__("Token Secret", "micro-office"),
					"description" => wp_kses_data( __("Token Secret from the twitter account", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "count",
					"heading" => esc_html__("Tweets number", "micro-office"),
					"description" => wp_kses_data( __("Number tweets to show", "micro-office") ),
					"class" => "",
					"divider" => true,
					"value" => 3,
					"type" => "textfield"
				),
				array(
					"param_name" => "controls",
					"heading" => esc_html__("Show arrows", "micro-office"),
					"description" => wp_kses_data( __("Show control buttons", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('yes_no')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "interval",
					"heading" => esc_html__("Tweets change interval", "micro-office"),
					"description" => wp_kses_data( __("Tweets change interval (in milliseconds: 1000ms = 1s)", "micro-office") ),
					"class" => "",
					"value" => "7000",
					"type" => "textfield"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Alignment of the tweets block", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "autoheight",
					"heading" => esc_html__("Autoheight", "micro-office"),
					"description" => wp_kses_data( __("Change whole slider's height (make it equal current slide's height)", "micro-office") ),
					"class" => "",
					"value" => array("Autoheight" => "yes" ),
					"type" => "checkbox"
				),
				array(
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "micro-office"),
					"description" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Any background color for this section", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_image",
					"heading" => esc_html__("Background image URL", "micro-office"),
					"description" => wp_kses_data( __("Select background image from library for this section", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "attach_image"
				),
				array(
					"param_name" => "bg_overlay",
					"heading" => esc_html__("Overlay", "micro-office"),
					"description" => wp_kses_data( __("Overlay color opacity (from 0.0 to 1.0)", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "bg_texture",
					"heading" => esc_html__("Texture", "micro-office"),
					"description" => wp_kses_data( __("Texture style from 1 to 11. Empty or 0 - without texture.", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "textfield"
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
		
		class WPBakeryShortCode_Trx_Twitter extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>