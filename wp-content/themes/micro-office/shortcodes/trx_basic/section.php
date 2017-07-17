<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_section_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_section_theme_setup' );
	function micro_office_sc_section_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_section_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_section_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */

micro_office_storage_set('sc_section_dedicated', '');

if (!function_exists('micro_office_sc_section')) {	
	function micro_office_sc_section($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"dedicated" => "no",
			"align" => "none",
			"columns" => "none",
			"pan" => "no",
			"scroll" => "no",
			"scroll_dir" => "horizontal",
			"scroll_controls" => "hide",
			"color" => "",
			"scheme" => "",
			"bg_color" => "",
			"bg_image" => "",
			"bg_overlay" => "",
			"bg_texture" => "",
			"bg_tile" => "no",
			"bg_padding" => "yes",
			"font_size" => "",
			"font_weight" => "",
			"title" => "",
			"subtitle" => "",
			"description" => "",
			"link_caption" => esc_html__('Learn more', 'micro-office'),
			"link" => '',
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
		$css .= ($color !== '' ? 'color:' . esc_attr($color) . ';' : '')
			.($bg_color !== '' && $bg_overlay==0 ? 'background-color:' . esc_attr($bg_color) . ';' : '')
			.($bg_image !== '' ? 'background-image:url(' . esc_url($bg_image) . ');'.(micro_office_param_is_on($bg_tile) ? 'background-repeat:repeat;' : 'background-repeat:no-repeat;background-size:cover;') : '')
			.(!micro_office_param_is_off($pan) ? 'position:relative;' : '')
			.($font_size != '' ? 'font-size:' . esc_attr(micro_office_prepare_css_value($font_size)) . '; line-height: 1.3em;' : '')
			.($font_weight != '' && !micro_office_param_is_inherit($font_weight) ? 'font-weight:' . esc_attr($font_weight) . ';' : '');
		$css_dim = micro_office_get_css_dimensions_from_values($width, $height);
		if ($bg_image == '' && $bg_color == '' && $bg_overlay==0 && $bg_texture==0 && micro_office_strlen($bg_texture)<2) $css .= $css_dim;
		
		$width  = micro_office_prepare_css_value($width);
		$height = micro_office_prepare_css_value($height);
	
		if ((!micro_office_param_is_off($scroll) || !micro_office_param_is_off($pan)) && empty($id)) $id = 'sc_section_'.str_replace('.', '', mt_rand());
	
		if (!micro_office_param_is_off($scroll)) micro_office_enqueue_slider();
	
		$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
				. ' class="sc_section' 
					. ($class ? ' ' . esc_attr($class) : '') 
					. ($scheme && !micro_office_param_is_off($scheme) && !micro_office_param_is_inherit($scheme) ? ' scheme_'.esc_attr($scheme) : '') 
					. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
					. (!empty($columns) && $columns!='none' ? ' column-'.esc_attr($columns) : '') 
					. (micro_office_param_is_on($scroll) && !micro_office_param_is_off($scroll_controls) ? ' sc_scroll_controls sc_scroll_controls_'.esc_attr($scroll_dir).' sc_scroll_controls_type_'.esc_attr($scroll_controls) : '')
					. '"'
				. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
				. ($css!='' || $css_dim!='' ? ' style="'.esc_attr($css.$css_dim).'"' : '')
				.'>' 
				. '<div class="sc_section_inner">'
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay>0 || $bg_texture>0 || micro_office_strlen($bg_texture)>2
						? '<div class="sc_section_overlay'.($bg_texture>0 ? ' texture_bg_'.esc_attr($bg_texture) : '') . '"'
							. ' style="' . ($bg_overlay>0 ? 'background-color:rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.min(1, max(0, $bg_overlay)).');' : '')
								. (micro_office_strlen($bg_texture)>2 ? 'background-image:url('.esc_url($bg_texture).');' : '')
								. '"'
								. ($bg_overlay > 0 ? ' data-overlay="'.esc_attr($bg_overlay).'" data-bg_color="'.esc_attr($bg_color).'"' : '')
								. '>'
								. '<div class="sc_section_content' . (micro_office_param_is_on($bg_padding) ? ' padding_on' : ' padding_off') . '"'
									. ' style="'.esc_attr($css_dim).'"'
									. '>'
						: '')
					. (micro_office_param_is_on($scroll) 
						? '<div id="'.esc_attr($id).'_scroll" class="sc_scroll sc_scroll_'.esc_attr($scroll_dir).' swiper-slider-container scroll-container"'
							. ' style="'.($height != '' ? 'height:'.esc_attr($height).';' : '') . ($width != '' ? 'width:'.esc_attr($width).';' : '').'"'
							. '>'
							. '<div class="sc_scroll_wrapper swiper-wrapper">' 
							. '<div class="sc_scroll_slide swiper-slide">' 
						: '')
					. (micro_office_param_is_on($pan) 
						? '<div id="'.esc_attr($id).'_pan" class="sc_pan sc_pan_'.esc_attr($scroll_dir).'">' 
						: '')
							. (!empty($subtitle) ? '<h6 class="sc_section_subtitle sc_item_subtitle">' . trim(micro_office_strmacros($subtitle)) . '</h6>' : '')
							. (!empty($title) ? '<h2 class="sc_section_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . trim(micro_office_strmacros($title)) . '</h2>' : '')
							. (!empty($description) ? '<div class="sc_section_descr sc_item_descr">' . trim(micro_office_strmacros($description)) . '</div>' : '')
							. '<div class="sc_section_content_wrap">' . do_shortcode($content) . '</div>'
							. (!empty($link) ? '<div class="sc_section_button sc_item_button">'.micro_office_do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
					. (micro_office_param_is_on($pan) ? '</div>' : '')
					. (micro_office_param_is_on($scroll) 
						? '</div></div><div id="'.esc_attr($id).'_scroll_bar" class="sc_scroll_bar sc_scroll_bar_'.esc_attr($scroll_dir).' '.esc_attr($id).'_scroll_bar"></div></div>'
							. (!micro_office_param_is_off($scroll_controls) ? '<div class="sc_scroll_controls_wrap"><a class="sc_scroll_prev" href="#"></a><a class="sc_scroll_next" href="#"></a></div>' : '')
						: '')
					. ($bg_image !== '' || $bg_color !== '' || $bg_overlay > 0 || $bg_texture>0 || micro_office_strlen($bg_texture)>2 ? '</div></div>' : '')
					. '</div>'
				. '</div>';
		if (micro_office_param_is_on($dedicated)) {
			if (micro_office_storage_get('sc_section_dedicated')=='') {
				micro_office_storage_set('sc_section_dedicated', $output);
			}
			$output = '';
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_section', $atts, $content);
	}
	micro_office_require_shortcode('trx_section', 'micro_office_sc_section');
}

if (!function_exists('micro_office_sc_block')) {	
	function micro_office_sc_block($atts, $content=null) {
		$atts['class'] = (!empty($atts['class']) ? $atts['class'] . ' ' : '') . 'sc_section_block';
		return apply_filters('micro_office_shortcode_output', micro_office_sc_section($atts, $content), 'trx_block', $atts, $content);
	}
	micro_office_require_shortcode('trx_block', 'micro_office_sc_block');
}


/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_section_reg_shortcodes' ) ) {
	
	function micro_office_sc_section_reg_shortcodes() {
	
		$sc = array(
			"title" => esc_html__("Block container", "micro-office"),
			"desc" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", "micro-office") ),
			"decorate" => true,
			"container" => true,
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
				"dedicated" => array(
					"title" => esc_html__("Dedicated", "micro-office"),
					"desc" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", "micro-office") ),
					"value" => "no",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"align" => array(
					"title" => esc_html__("Align", "micro-office"),
					"desc" => wp_kses_data( __("Select block alignment", "micro-office") ),
					"value" => "none",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('align')
				),
				"columns" => array(
					"title" => esc_html__("Columns emulation", "micro-office"),
					"desc" => wp_kses_data( __("Select width for columns emulation", "micro-office") ),
					"value" => "none",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('columns')
				), 
				"scheme" => array(
					"title" => esc_html__("Color scheme", "micro-office"),
					"desc" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"options" => micro_office_get_sc_param('schemes')
				),
				"color" => array(
					"title" => esc_html__("Fore color", "micro-office"),
					"desc" => wp_kses_data( __("Any color for objects in this section", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
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
				"bg_tile" => array(
					"title" => esc_html__("Tile background image", "micro-office"),
					"desc" => wp_kses_data( __("Do you want tile background image or image cover whole block?", "micro-office") ),
					"value" => "no",
					"dependency" => array(
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
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
				"bg_padding" => array(
					"title" => esc_html__("Paddings around content", "micro-office"),
					"desc" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", "micro-office") ),
					"value" => "yes",
					"dependency" => array(
						'compare' => 'or',
						'bg_color' => array('not_empty'),
						'bg_texture' => array('not_empty'),
						'bg_image' => array('not_empty')
					),
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"font_size" => array(
					"title" => esc_html__("Font size", "micro-office"),
					"desc" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", "micro-office") ),
					"value" => "",
					"type" => "text"
				),
				"font_weight" => array(
					"title" => esc_html__("Font weight", "micro-office"),
					"desc" => wp_kses_data( __("Font weight of the text", "micro-office") ),
					"value" => "",
					"type" => "select",
					"size" => "medium",
					"options" => array(
						'100' => esc_html__('Thin (100)', 'micro-office'),
						'300' => esc_html__('Light (300)', 'micro-office'),
						'400' => esc_html__('Normal (400)', 'micro-office'),
						'700' => esc_html__('Bold (700)', 'micro-office')
					)
				),
				"_content_" => array(
					"title" => esc_html__("Container content", "micro-office"),
					"desc" => wp_kses_data( __("Content for section container", "micro-office") ),
					"divider" => true,
					"rows" => 4,
					"value" => "",
					"type" => "textarea"
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
		);
		micro_office_sc_map("trx_block", $sc);
		$sc["title"] = esc_html__("Section container", "micro-office");
		$sc["desc"] = esc_html__("Container for any section ([trx_block] analog - to enable nesting)", "micro-office");
		micro_office_sc_map("trx_section", $sc);
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_section_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_section_reg_shortcodes_vc() {
	
		$sc = array(
			"base" => "trx_block",
			"name" => esc_html__("Block container", "micro-office"),
			"description" => wp_kses_data( __("Container for any block ([trx_section] analog - to enable nesting)", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_block',
			"class" => "trx_sc_collection trx_sc_block",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"params" => array(
				array(
					"param_name" => "dedicated",
					"heading" => esc_html__("Dedicated", "micro-office"),
					"description" => wp_kses_data( __("Use this block as dedicated content - show it before post title on single page", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(esc_html__('Use as dedicated content', 'micro-office') => 'yes'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Select block alignment", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('align')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns emulation", "micro-office"),
					"description" => wp_kses_data( __("Select width for columns emulation", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('columns')),
					"type" => "dropdown"
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
					"param_name" => "scheme",
					"heading" => esc_html__("Color scheme", "micro-office"),
					"description" => wp_kses_data( __("Select color scheme for this block", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('schemes')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Fore color", "micro-office"),
					"description" => wp_kses_data( __("Any color for objects in this section", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
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
					"param_name" => "bg_tile",
					"heading" => esc_html__("Tile background image", "micro-office"),
					"description" => wp_kses_data( __("Do you want tile background image or image cover whole block?", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					'dependency' => array(
						'element' => 'bg_image',
						'not_empty' => true
					),
					"std" => "no",
					"value" => array(esc_html__('Tile background image', 'micro-office') => 'yes'),
					"type" => "checkbox"
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
				array(
					"param_name" => "bg_padding",
					"heading" => esc_html__("Paddings around content", "micro-office"),
					"description" => wp_kses_data( __("Add paddings around content in this section (only if bg_color or bg_image enabled).", "micro-office") ),
					"group" => esc_html__('Colors and Images', 'micro-office'),
					"class" => "",
					'dependency' => array(
						'element' => array('bg_color','bg_texture','bg_image'),
						'not_empty' => true
					),
					"std" => "yes",
					"value" => array(esc_html__('Disable padding around content in this block', 'micro-office') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "font_size",
					"heading" => esc_html__("Font size", "micro-office"),
					"description" => wp_kses_data( __("Font size of the text (default - in pixels, allows any CSS units of measure)", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "font_weight",
					"heading" => esc_html__("Font weight", "micro-office"),
					"description" => wp_kses_data( __("Font weight of the text", "micro-office") ),
					"class" => "",
					"value" => array(
						esc_html__('Default', 'micro-office') => 'inherit',
						esc_html__('Thin (100)', 'micro-office') => '100',
						esc_html__('Light (300)', 'micro-office') => '300',
						esc_html__('Normal (400)', 'micro-office') => '400',
						esc_html__('Bold (700)', 'micro-office') => '700'
					),
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
			)
		);
		
		// Block
		vc_map($sc);
		
		// Section
		$sc["base"] = 'trx_section';
		$sc["name"] = esc_html__("Section container", "micro-office");
		$sc["description"] = wp_kses_data( __("Container for any section ([trx_block] analog - to enable nesting)", "micro-office") );
		$sc["class"] = "trx_sc_collection trx_sc_section";
		$sc["icon"] = 'icon_trx_section';
		vc_map($sc);
		
		class WPBakeryShortCode_Trx_Block extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Section extends MICRO_OFFICE_VC_ShortCodeCollection {}
	}
}
?>