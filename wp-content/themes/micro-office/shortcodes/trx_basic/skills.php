<?php

/* Theme setup section
-------------------------------------------------------------------- */
if (!function_exists('micro_office_sc_skills_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_sc_skills_theme_setup' );
	function micro_office_sc_skills_theme_setup() {
		add_action('micro_office_action_shortcodes_list', 		'micro_office_sc_skills_reg_shortcodes');
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_sc_skills_reg_shortcodes_vc');
	}
}



/* Shortcode implementation
-------------------------------------------------------------------- */


if (!function_exists('micro_office_sc_skills')) {	
	function micro_office_sc_skills($atts, $content=null){	
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"max_value" => "100",
			"type" => "bar",
			"layout" => "",
			"dir" => "",
			"style" => "1",
			"columns" => "",
			"align" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"arc_caption" => esc_html__("Skills", "micro-office"),
			"pie_compact" => "on",
			"pie_cutout" => 0,
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
		micro_office_storage_set('sc_skills_data', array(
			'counter' => 0,
            'columns' => 0,
            'height'  => 0,
            'type'    => $type,
            'pie_compact' => micro_office_param_is_on($pie_compact) ? 'on' : 'off',
            'pie_cutout'  => max(0, min(99, $pie_cutout)),
            'color'   => $color,
            'bg_color'=> $bg_color,
            'border_color'=> $border_color,
            'legend'  => '',
            'data'    => '',
			'dir' 	  => $dir
			)
		);
		micro_office_enqueue_diagram($type);
		if ($type!='arc') {
			if ($layout=='' || ($layout=='columns' && $columns<1)) $layout = 'rows';
			if ($layout=='columns') micro_office_storage_set_array('sc_skills_data', 'columns', $columns);
			if ($type=='bar') {
				if ($dir == '') $dir = 'horizontal';
				if ($dir == 'vertical' && $height < 1) $height = 300;
			}
		}
		if (empty($id)) $id = 'sc_skills_diagram_'.str_replace('.','',mt_rand());
		if ($max_value < 1) $max_value = 100;
		if ($style) {
			$style = max(1, min(4, $style));
			micro_office_storage_set_array('sc_skills_data', 'style', $style);
		}
		micro_office_storage_set_array('sc_skills_data', 'max', $max_value);
		micro_office_storage_set_array('sc_skills_data', 'dir', $dir);
		micro_office_storage_set_array('sc_skills_data', 'height', micro_office_prepare_css_value($height));
		$css .= ($css ? '; ' : '') . micro_office_get_css_position_from_values($top, $right, $bottom, $left);
		$css .= micro_office_get_css_dimensions_from_values($width);
		if (!micro_office_storage_empty('sc_skills_data', 'height') && (micro_office_storage_get_array('sc_skills_data', 'type') == 'arc' || (micro_office_storage_get_array('sc_skills_data', 'type') == 'pie' && micro_office_param_is_on(micro_office_storage_get_array('sc_skills_data', 'pie_compact')))))
			$css .= 'height: '.micro_office_storage_get_array('sc_skills_data', 'height');
		$content = do_shortcode($content);
		$output = '<div id="'.esc_attr($id).'"' 
					. ' class="sc_skills sc_skills_' . esc_attr($type) 
						. ($type=='bar' ? ' sc_skills_'.esc_attr($dir) : '') 
						. ($type=='pie' ? ' sc_skills_compact_'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'pie_compact')) : '') 
						. (!empty($class) ? ' '.esc_attr($class) : '') 
						. ($align && $align!='none' ? ' align'.esc_attr($align) : '') 
						. '"'
					. ($css!='' ? ' style="'.esc_attr($css).'"' : '')
					. (!micro_office_param_is_off($animation) ? ' data-animation="'.esc_attr(micro_office_get_animation_classes($animation)).'"' : '')
					. ' data-type="'.esc_attr($type).'"'
					. ' data-caption="'.esc_attr($arc_caption).'"'
					. ($type=='bar' ? ' data-dir="'.esc_attr($dir).'"' : '')
				. '>'
					. (!empty($subtitle) ? '<h6 class="sc_skills_subtitle sc_item_subtitle">' . esc_html($subtitle) . '</h6>' : '')
					. (!empty($title) ? '<h2 class="sc_skills_title sc_item_title' . (empty($description) ? ' sc_item_title_without_descr' : ' sc_item_title_without_descr') . '">' . esc_html($title) . '</h2>' : '')
					. (!empty($description) ? '<div class="sc_skills_descr sc_item_descr">' . trim($description) . '</div>' : '')
					. ($layout == 'columns' ? '<div class="columns_wrap sc_skills_'.esc_attr($layout).' sc_skills_columns_'.esc_attr($columns).'">' : '')
					. ($type=='arc' 
						? ('<div class="sc_skills_legend">'.(micro_office_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_diagram" class="sc_skills_arc_canvas"></div>'
							. '<div class="sc_skills_data" style="display:none;">' . (micro_office_storage_get_array('sc_skills_data', 'data')) . '</div>'
						  )
						: '')
					. ($type=='pie' && micro_office_param_is_on(micro_office_storage_get_array('sc_skills_data', 'pie_compact'))
						? ('<div class="sc_skills_legend">'.(micro_office_storage_get_array('sc_skills_data', 'legend')).'</div>'
							. '<div id="'.esc_attr($id).'_pie" class="sc_skills_item">'
								. '<canvas id="'.esc_attr($id).'_pie_canvas" class="sc_skills_pie_canvas"></canvas>'
								. '<div class="sc_skills_data" style="display:none;">' . (micro_office_storage_get_array('sc_skills_data', 'data')) . '</div>'
							. '</div>'
						  )
						: '')
					. ($content)
					. ($layout == 'columns' ? '</div>' : '')
					. (!empty($link) ? '<div class="sc_skills_button sc_item_button">'.do_shortcode('[trx_button link="'.esc_url($link).'" icon="icon-right"]'.esc_html($link_caption).'[/trx_button]').'</div>' : '')
				. '</div>';
		return apply_filters('micro_office_shortcode_output', $output, 'trx_skills', $atts, $content);
	}
	micro_office_require_shortcode('trx_skills', 'micro_office_sc_skills');
}


if (!function_exists('micro_office_sc_skills_item')) {	
	function micro_office_sc_skills_item($atts, $content=null) {
		if (micro_office_in_shortcode_blogger()) return '';
		extract(micro_office_html_decode(shortcode_atts( array(
			// Individual params
			"title" => "",
			"value" => "",
			"color" => "",
			"bg_color" => "",
			"border_color" => "",
			"style" => "",
			"icon" => "",
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts)));
		micro_office_storage_inc_array('sc_skills_data', 'counter');
		$ed = micro_office_substr($value, -1)=='%' ? '%' : '';
		$value = str_replace('%', '', $value);
		if (micro_office_storage_get_array('sc_skills_data', 'max') < $value) micro_office_storage_set_array('sc_skills_data', 'max', $value);
		$percent = round($value / micro_office_storage_get_array('sc_skills_data', 'max') * 100);
		$start = 0;
		$stop = $value;
		$steps = 100;
		$step = max(1, round(micro_office_storage_get_array('sc_skills_data', 'max')/$steps));
		$speed = mt_rand(10,40);
		$animation = round(($stop - $start) / $step * $speed);
		$old_color = $color;
		if (empty($color)) $color = micro_office_storage_get_array('sc_skills_data', 'color');
		if (empty($color)) $color = micro_office_get_scheme_color('text_hover', $color);
		if (empty($bg_color)) $bg_color = micro_office_storage_get_array('sc_skills_data', 'bg_color');
		if (empty($bg_color)) $bg_color = micro_office_get_scheme_color('bg_color', $bg_color);
		if (empty($border_color)) $border_color = '#fff';
		if (empty($border_color)) $border_color = micro_office_get_scheme_color('bd_color', $border_color);;
		if (empty($style)) $style = micro_office_storage_get_array('sc_skills_data', 'style');
		
		$title_block = '<div class="sc_skills_info" '.(micro_office_storage_get_array('sc_skills_data', 'type')=='counter' && $style == 1 && $color ? 'style="background-color:' . esc_attr($color) . ';"' : '').'><div class="sc_skills_label">' . ($title) . '</div></div>';
		
		$rgb = micro_office_hex2rgb($color);
		$counter_bg = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].',0.8)';
		
		$style = max(1, min(4, $style));
		$output = '';
		if (micro_office_storage_get_array('sc_skills_data', 'type') == 'arc' || (micro_office_storage_get_array('sc_skills_data', 'type') == 'pie' && micro_office_param_is_on(micro_office_storage_get_array('sc_skills_data', 'pie_compact')))) {
			if (micro_office_storage_get_array('sc_skills_data', 'type') == 'arc' && empty($old_color)) {
				$rgb = micro_office_hex2rgb($color);
				$color = 'rgba('.(int)$rgb['r'].','.(int)$rgb['g'].','.(int)$rgb['b'].','.(1 - 0.1*(micro_office_storage_get_array('sc_skills_data', 'counter')-1)).')';
			}
			micro_office_storage_concat_array('sc_skills_data', 'legend', 
				'<div class="sc_skills_legend_item"><span class="sc_skills_legend_marker" style="background-color:'.esc_attr($color).'"></span><span class="sc_skills_legend_title">' . ($title) . '</span><span class="sc_skills_legend_value">' . ($value) . '</span></div>'
			);
			micro_office_storage_concat_array('sc_skills_data', 'data', 
				'<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
					. ' class="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'type')).'"'
					. (micro_office_storage_get_array('sc_skills_data', 'type')=='pie'
						? ( ' data-start="'.esc_attr($start).'"'
							. ' data-stop="'.esc_attr($stop).'"'
							. ' data-step="'.esc_attr($step).'"'
							. ' data-steps="'.esc_attr($steps).'"'
							. ' data-max="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'max')).'"'
							. ' data-speed="'.esc_attr($speed).'"'
							. ' data-duration="'.esc_attr($animation).'"'
							. ' data-color="'.esc_attr($color).'"'
							. ' data-bg_color="'.esc_attr($bg_color).'"'
							. ' data-border_color="'.esc_attr($border_color).'"'
							. ' data-cutout="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
							. ' data-easing="easeOutCirc"'
							. ' data-ed="'.esc_attr($ed).'"'
							)
						: '')
					. '><input type="hidden" class="text" value="'.esc_attr($title).'" /><input type="hidden" class="percent" value="'.esc_attr($percent).'" /><input type="hidden" class="color" value="'.esc_attr($color).'" /></div>'
			);
		} else {
			$output .= (micro_office_storage_get_array('sc_skills_data', 'columns') > 0 
							? '<div class="sc_skills_column column-1_'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'columns')).'">' 
							: '')
					. (micro_office_storage_get_array('sc_skills_data', 'type')=='bar' && micro_office_storage_get_array('sc_skills_data', 'dir')=='horizontal' ? $title_block : '')
					. '<div' . ($id ? ' id="'.esc_attr($id).'"' : '') 
						. ' class="sc_skills_item' . ($style ? ' sc_skills_style_'.esc_attr($style) : '') 
							. (!empty($class) ? ' '.esc_attr($class) : '')
							. (micro_office_storage_get_array('sc_skills_data', 'counter') % 2 == 1 ? ' odd' : ' even') 
							. (micro_office_storage_get_array('sc_skills_data', 'counter') == 1 ? ' first' : '') 
							. '"'
						. (micro_office_storage_get_array('sc_skills_data', 'height') !='' || $css || $counter_bg
							? ' style="' 
								. (micro_office_storage_get_array('sc_skills_data', 'height') !='' 
										? 'height: '.esc_attr(micro_office_storage_get_array('sc_skills_data', 'height')).';' 
										: '') 
								. ($css) 
								. (micro_office_storage_get_array('sc_skills_data', 'type')=='counter' &&  $style == 1 ? 'background-color:' . esc_attr($counter_bg) . ';' : '')
								. (micro_office_storage_get_array('sc_skills_data', 'type')=='bar' &&  micro_office_storage_get_array('sc_skills_data', 'dir')=='vertical' ? 'background-color:' . esc_attr($counter_bg) . ';' : '')
								. '"' 
							: '')
					. '>'
					. (micro_office_storage_get_array('sc_skills_data', 'type')=='counter' ? (!empty($icon)  ? '<div class="sc_skills_icon '.esc_attr($icon).'"></div>' : ($style == 1 ? '<div class="sc_skills_icon icon-trophy"></div>' : '')) : '')
					. (micro_office_storage_get_array('sc_skills_data', 'type')=='counter' && $style == 1 ? $title_block : '');
			if (in_array(micro_office_storage_get_array('sc_skills_data', 'type'), array('bar', 'counter'))) {
				$output .= '<div class="sc_skills_count"' . (micro_office_storage_get_array('sc_skills_data', 'type')=='bar' && $color ? ' style="background-color:' . esc_attr($color) . '; border-color:' . esc_attr($color) . '"' 
							: ($style == 1 || $style == 3 ? ' style="background-color:' . esc_attr($color) . ';"' : ''))  
							.'>'
							. '<div class="sc_skills_total"'
								. ' data-start="'.esc_attr($start).'"'
								. ' data-stop="'.esc_attr($stop).'"'
								. ' data-step="'.esc_attr($step).'"'
								. ' data-max="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'max')).'"'
								. ' data-speed="'.esc_attr($speed).'"'
								. ' data-duration="'.esc_attr($animation).'"'
								. ' data-ed="'.esc_attr($ed).'">'
								. ($start) . ($ed)
								
							.'</div>'
						. '</div>'
						. (micro_office_storage_get_array('sc_skills_data', 'type')=='counter' && $style != 1 ? $title_block : '');
			} else if (micro_office_storage_get_array('sc_skills_data', 'type')=='pie') {
				if (empty($id)) $id = 'sc_skills_canvas_'.str_replace('.','',mt_rand());
				$output .= '<canvas id="'.esc_attr($id).'_canvas"></canvas>'
					. '<div class="sc_skills_total"'
						. ' data-start="'.esc_attr($start).'"'
						. ' data-stop="'.esc_attr($stop).'"'
						. ' data-step="'.esc_attr($step).'"'
						. ' data-steps="'.esc_attr($steps).'"'
						. ' data-max="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'max')).'"'
						. ' data-speed="'.esc_attr($speed).'"'
						. ' data-duration="'.esc_attr($animation).'"'
						. ' data-color="'.esc_attr($color).'"'
						. ' data-bg_color="'.esc_attr($counter_bg).'"'
						. ' data-border_color="'.esc_attr($border_color).'"'
						. ' data-cutout="'.esc_attr(micro_office_storage_get_array('sc_skills_data', 'pie_cutout')).'"'
						. ' data-easing="easeOutCirc"'
						. ' data-ed="'.esc_attr($ed).'">'
						. ($start) . ($ed)
					.'</div>';
			}
			$output .=  '</div>'
						. (micro_office_storage_get_array('sc_skills_data', 'type')=='bar' && micro_office_storage_get_array('sc_skills_data', 'dir')=='vertical' || micro_office_storage_get_array('sc_skills_data', 'type') == 'pie' ? $title_block : '')
						. (micro_office_storage_get_array('sc_skills_data', 'columns') > 0 ? '</div>' : '');
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_skills_item', $atts, $content);
	}
	micro_office_require_shortcode('trx_skills_item', 'micro_office_sc_skills_item');
}



/* Register shortcode in the internal SC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_skills_reg_shortcodes' ) ) {
	
	function micro_office_sc_skills_reg_shortcodes() {
	
		micro_office_sc_map("trx_skills", array(
			"title" => esc_html__("Skills", "micro-office"),
			"desc" => wp_kses_data( __("Insert skills diagramm in your page (post)", "micro-office") ),
			"decorate" => true,
			"container" => false,
			"params" => array(
				"max_value" => array(
					"title" => esc_html__("Max value", "micro-office"),
					"desc" => wp_kses_data( __("Max value for skills items", "micro-office") ),
					"value" => 100,
					"min" => 1,
					"type" => "spinner"
				),
				"type" => array(
					"title" => esc_html__("Skills type", "micro-office"),
					"desc" => wp_kses_data( __("Select type of skills block", "micro-office") ),
					"value" => "bar",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'bar' => esc_html__('Bar', 'micro-office'),
						'pie' => esc_html__('Pie chart', 'micro-office'),
						'counter' => esc_html__('Counter', 'micro-office')
					)
				), 
				"layout" => array(
					"title" => esc_html__("Skills layout", "micro-office"),
					"desc" => wp_kses_data( __("Select layout of skills block", "micro-office") ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "rows",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => array(
						'rows' => esc_html__('Rows', 'micro-office'),
						'columns' => esc_html__('Columns', 'micro-office')
					)
				),
				"dir" => array(
					"title" => esc_html__("Direction", "micro-office"),
					"desc" => wp_kses_data( __("Select direction of skills block", "micro-office") ),
					"dependency" => array(
						'type' => array('counter','pie','bar')
					),
					"value" => "horizontal",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('dir')
				), 
				"style" => array(
					"title" => esc_html__("Counters style", "micro-office"),
					"desc" => wp_kses_data( __("Select style of skills items (only for type=counter)", "micro-office") ),
					"dependency" => array(
						'type' => array('counter')
					),
					"value" => 1,
					"options" => micro_office_get_list_styles(1, 4),
					"type" => "checklist"
				), 
				// "columns" - autodetect, not set manual
				"color" => array(
					"title" => esc_html__("Skills items color", "micro-office"),
					"desc" => wp_kses_data( __("Color for all skills items", "micro-office") ),
					"divider" => true,
					"value" => "",
					"type" => "color"
				),
				"bg_color" => array(
					"title" => esc_html__("Background color", "micro-office"),
					"desc" => wp_kses_data( __("Background color for all skills items (only for type=pie)", "micro-office") ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"border_color" => array(
					"title" => esc_html__("Border color", "micro-office"),
					"desc" => wp_kses_data( __("Border color for all skills items (only for type=pie)", "micro-office") ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "",
					"type" => "color"
				),
				"align" => array(
					"title" => esc_html__("Align skills block", "micro-office"),
					"desc" => wp_kses_data( __("Align skills block to left or right side", "micro-office") ),
					"value" => "",
					"type" => "checklist",
					"dir" => "horizontal",
					"options" => micro_office_get_sc_param('float')
				), 
				"arc_caption" => array(
					"title" => esc_html__("Arc Caption", "micro-office"),
					"desc" => wp_kses_data( __("Arc caption - text in the center of the diagram", "micro-office") ),
					"dependency" => array(
						'type' => array('arc')
					),
					"value" => "",
					"type" => "text"
				),
				"pie_compact" => array(
					"title" => esc_html__("Pie compact", "micro-office"),
					"desc" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", "micro-office") ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => "yes",
					"type" => "switch",
					"options" => micro_office_get_sc_param('yes_no')
				),
				"pie_cutout" => array(
					"title" => esc_html__("Pie cutout", "micro-office"),
					"desc" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "micro-office") ),
					"dependency" => array(
						'type' => array('pie')
					),
					"value" => 0,
					"min" => 0,
					"max" => 99,
					"type" => "spinner"
				),
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
			),
			"children" => array(
				"name" => "trx_skills_item",
				"title" => esc_html__("Skill", "micro-office"),
				"desc" => wp_kses_data( __("Skills item", "micro-office") ),
				"container" => false,
				"params" => array(
					"title" => array(
						"title" => esc_html__("Title", "micro-office"),
						"desc" => wp_kses_data( __("Current skills item title", "micro-office") ),
						"value" => "",
						"type" => "text"
					),
					"value" => array(
						"title" => esc_html__("Value", "micro-office"),
						"desc" => wp_kses_data( __("Current skills level", "micro-office") ),
						"value" => 50,
						"min" => 0,
						"step" => 1,
						"type" => "spinner"
					),
					"color" => array(
						"title" => esc_html__("Color", "micro-office"),
						"desc" => wp_kses_data( __("Current skills item color", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"bg_color" => array(
						"title" => esc_html__("Background color", "micro-office"),
						"desc" => wp_kses_data( __("Current skills item background color (only for type=pie)", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"border_color" => array(
						"title" => esc_html__("Border color", "micro-office"),
						"desc" => wp_kses_data( __("Current skills item border color (only for type=pie)", "micro-office") ),
						"value" => "",
						"type" => "color"
					),
					"style" => array(
						"title" => esc_html__("Counter style", "micro-office"),
						"desc" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", "micro-office") ),
						"value" => 1,
						"options" => micro_office_get_list_styles(1, 4),
						"type" => "checklist"
					), 
					"icon" => array(
						"title" => esc_html__("Counter icon",  'micro-office'),
						"desc" => wp_kses_data( __('Select icon from Fontello icons set, placed above counter (only for type=counter)',  'micro-office') ),
						"value" => "",
						"type" => "icons",
						"options" => micro_office_get_sc_param('icons')
					),
					"id" => micro_office_get_sc_param('id'),
					"class" => micro_office_get_sc_param('class'),
					"css" => micro_office_get_sc_param('css')
				)
			)
		));
	}
}


/* Register shortcode in the VC Builder
-------------------------------------------------------------------- */
if ( !function_exists( 'micro_office_sc_skills_reg_shortcodes_vc' ) ) {
	
	function micro_office_sc_skills_reg_shortcodes_vc() {
	
		vc_map( array(
			"base" => "trx_skills",
			"name" => esc_html__("Skills", "micro-office"),
			"description" => wp_kses_data( __("Insert skills diagramm", "micro-office") ),
			"category" => esc_html__('Content', 'micro-office'),
			'icon' => 'icon_trx_skills',
			"class" => "trx_sc_collection trx_sc_skills",
			"content_element" => true,
			"is_container" => true,
			"show_settings_on_create" => true,
			"as_parent" => array('only' => 'trx_skills_item'),
			"params" => array(
				array(
					"param_name" => "max_value",
					"heading" => esc_html__("Max value", "micro-office"),
					"description" => wp_kses_data( __("Max value for skills items", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "100",
					"type" => "textfield"
				),
				array(
					"param_name" => "type",
					"heading" => esc_html__("Skills type", "micro-office"),
					"description" => wp_kses_data( __("Select type of skills block", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array(
						esc_html__('Bar', 'micro-office') => 'bar',
						esc_html__('Pie chart', 'micro-office') => 'pie',
						esc_html__('Counter', 'micro-office') => 'counter'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "layout",
					"heading" => esc_html__("Skills layout", "micro-office"),
					"description" => wp_kses_data( __("Select layout of skills block", "micro-office") ),
					"admin_label" => true,
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter','bar','pie')
					),
					"class" => "",
					"value" => array(
						esc_html__('Rows', 'micro-office') => 'rows',
						esc_html__('Columns', 'micro-office') => 'columns'
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "dir",
					"heading" => esc_html__("Direction", "micro-office"),
					"description" => wp_kses_data( __("Select direction of skills block", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('dir')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counters style", "micro-office"),
					"description" => wp_kses_data( __("Select style of skills items (only for type=counter)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_list_styles(1, 4)),
					'dependency' => array(
						'element' => 'type',
						'value' => array('counter')
					),
					"type" => "dropdown"
				),
				array(
					"param_name" => "columns",
					"heading" => esc_html__("Columns count", "micro-office"),
					"description" => wp_kses_data( __("Skills columns count (required)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("Color for all skills items", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Background color for all skills items (only for type=pie)", "micro-office") ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "micro-office"),
					"description" => wp_kses_data( __("Border color for all skills items (only for type=pie)", "micro-office") ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "align",
					"heading" => esc_html__("Alignment", "micro-office"),
					"description" => wp_kses_data( __("Align skills block to left or right side", "micro-office") ),
					"class" => "",
					"value" => array_flip(micro_office_get_sc_param('float')),
					"type" => "dropdown"
				),
				array(
					"param_name" => "arc_caption",
					"heading" => esc_html__("Arc caption", "micro-office"),
					"description" => wp_kses_data( __("Arc caption - text in the center of the diagram", "micro-office") ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('arc')
					),
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "pie_compact",
					"heading" => esc_html__("Pie compact", "micro-office"),
					"description" => wp_kses_data( __("Show all skills in one diagram or as separate diagrams", "micro-office") ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
					"class" => "",
					"value" => array(esc_html__('Show separate skills', 'micro-office') => 'no'),
					"type" => "checkbox"
				),
				array(
					"param_name" => "pie_cutout",
					"heading" => esc_html__("Pie cutout", "micro-office"),
					"description" => wp_kses_data( __("Pie cutout (0-99). 0 - without cutout, 99 - max cutout", "micro-office") ),
					'dependency' => array(
						'element' => 'type',
						'value' => array('pie')
					),
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
		) );
		
		
		vc_map( array(
			"base" => "trx_skills_item",
			"name" => esc_html__("Skill", "micro-office"),
			"description" => wp_kses_data( __("Skills item", "micro-office") ),
			"show_settings_on_create" => true,
			'icon' => 'icon_trx_skills_item',
			"class" => "trx_sc_single trx_sc_skills_item",
			"content_element" => true,
			"is_container" => false,
			"as_child" => array('only' => 'trx_skills'),
			"as_parent" => array('except' => 'trx_skills'),
			"params" => array(
				array(
					"param_name" => "title",
					"heading" => esc_html__("Title", "micro-office"),
					"description" => wp_kses_data( __("Title for the current skills item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "value",
					"heading" => esc_html__("Value", "micro-office"),
					"description" => wp_kses_data( __("Value for the current skills item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "textfield"
				),
				array(
					"param_name" => "color",
					"heading" => esc_html__("Color", "micro-office"),
					"description" => wp_kses_data( __("Color for current skills item", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "bg_color",
					"heading" => esc_html__("Background color", "micro-office"),
					"description" => wp_kses_data( __("Background color for current skills item (only for type=pie)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "border_color",
					"heading" => esc_html__("Border color", "micro-office"),
					"description" => wp_kses_data( __("Border color for current skills item (only for type=pie)", "micro-office") ),
					"class" => "",
					"value" => "",
					"type" => "colorpicker"
				),
				array(
					"param_name" => "style",
					"heading" => esc_html__("Counter style", "micro-office"),
					"description" => wp_kses_data( __("Select style for the current skills item (only for type=counter)", "micro-office") ),
					"admin_label" => true,
					"class" => "",
					"value" => array_flip(micro_office_get_list_styles(1, 4)),
					"type" => "dropdown"
				),
				array(
					"param_name" => "icon",
					"heading" => esc_html__("Counter icon", "micro-office"),
					"description" => wp_kses_data( __("Select icon from Fontello icons set, placed before counter (only for type=counter)", "micro-office") ),
					"class" => "",
					"value" => micro_office_get_sc_param('icons'),
					"type" => "dropdown"
				),
				micro_office_get_vc_param('id'),
				micro_office_get_vc_param('class'),
				micro_office_get_vc_param('css'),
			)
		) );
		
		class WPBakeryShortCode_Trx_Skills extends MICRO_OFFICE_VC_ShortCodeCollection {}
		class WPBakeryShortCode_Trx_Skills_Item extends MICRO_OFFICE_VC_ShortCodeSingle {}
	}
}
?>