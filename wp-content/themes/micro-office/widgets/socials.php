<?php
/**
 * Theme Widget: Socials
 */

// Theme init
if (!function_exists('micro_office_widget_socials_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_widget_socials_theme_setup', 1 );
	function micro_office_widget_socials_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_widget_socials_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('micro_office_widget_socials_load')) {
	add_action( 'widgets_init', 'micro_office_widget_socials_load' );
	function micro_office_widget_socials_load() {
		register_widget( 'micro_office_widget_socials' );
	}
}

// Widget Class
class micro_office_widget_socials extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_socials', 'description' => esc_html__('Show site logo and social links', 'micro-office') );
		parent::__construct( 'micro_office_widget_socials', esc_html__('Micro Office - Show logo and social links', 'micro-office'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$text = isset($instance['text']) ? micro_office_do_shortcode($instance['text']) : '';
		$logo_image = isset($instance['logo_image']) ? $instance['logo_image'] : '';
		$logo_text = isset($instance['logo_text']) ? $instance['logo_text'] : '';
		$logo_slogan = isset($instance['logo_slogan']) ? $instance['logo_slogan'] : '';
		$show_logo = isset($instance['show_logo']) ? (int) $instance['show_logo'] : 1;
		$show_icons = isset($instance['show_icons']) ? (int) $instance['show_icons'] : 1;

		// Before widget (defined by themes)
		micro_office_show_layout($before_widget);

		// Display the widget title if one was input (before and after defined by themes)
		if ($title) micro_office_show_layout($before_title . $title . $after_title);
		
		// Display widget body
		?>
		<div class="widget_inner">
            <?php
				if ($show_logo) {
					if ($logo_image=='')
						$logo_image = true;
					else {
						if ((int) $logo_image > 0) {
							$attach = wp_get_attachment_image_src( $logo_image, 'full' );
							if (isset($attach[0]) && $attach[0]!='')
								$logo_image = $attach[0];
						}
					}
					if ($logo_text=='')		$logo_text = true;
					if ($logo_slogan=='')	$logo_slogan = true;
					if ($logo_image || $logo_text)
						micro_office_show_logo($logo_image, false, false, false, $logo_text, $logo_slogan);
				}

				if (!empty($text)) {
					?>
					<div class="logo_descr"><?php echo nl2br(do_shortcode($text)); ?></div>
                    <?php
				}
				
				if ($show_icons) {
					micro_office_show_layout(micro_office_sc_socials(array('size' => "tiny", 'shape' => 'round')));
				}
			?>
		</div>

		<?php
		// After widget (defined by themes)
		micro_office_show_layout($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['text'] = $new_instance['text'];
		$instance['logo_image'] = strip_tags( $new_instance['logo_image'] );
		$instance['logo_text'] = strip_tags( $new_instance['logo_text'] );
		$instance['logo_slogan'] = strip_tags( $new_instance['logo_slogan'] );
		$instance['show_logo'] = (int) $new_instance['show_logo'];
		$instance['show_icons'] = (int) $new_instance['show_icons'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array( 
			'title' => '',
			'text' => '',
			'logo_image' => '',
			'logo_text' => '',
			'logo_slogan' => '',
			'show_logo' => '1',
			'show_icons' => '1'
			)
		);
		$title = $instance['title'];
		$text = $instance['text'];
		$logo_image = $instance['logo_image'];
		$logo_text = $instance['logo_text'];
		$logo_slogan = $instance['logo_slogan'];
		$show_logo = (int) $instance['show_logo'];
		$show_icons = (int) $instance['show_icons'];
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e('Title:', 'micro-office'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr($instance['title']); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'text' )); ?>"><?php esc_html_e('Description:', 'micro-office'); ?></label>
			<textarea id="<?php echo esc_attr($this->get_field_id( 'text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'text' )); ?>" class="widgets_param_fullwidth"><?php echo htmlspecialchars($instance['text']); ?></textarea>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>"><?php echo wp_kses_data( __('Logo image:<br />(if empty - use logo from Theme Options)', 'micro-office') ); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_image' )); ?>" value="<?php echo esc_attr($logo_image); ?>" class="widgets_param_fullwidth widgets_param_img_selector" />
            <?php
			micro_office_show_layout(micro_office_show_custom_field($this->get_field_id( 'logo_media' ), array('type'=>'mediamanager', 'media_field_id'=>$this->get_field_id( 'logo_image' )), null));
			if ($logo_image) {
			?>
	            <br /><br /><img src="<?php echo esc_url($logo_image); ?>" class="widgets_param_maxwidth" alt="" />
			<?php
			}
			?>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>"><?php esc_html_e('Logo text:', 'micro-office'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_text' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_text' )); ?>" value="<?php echo esc_attr($instance['logo_text']); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'logo_slogan' )); ?>"><?php esc_html_e('Logo slogan:', 'micro-office'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'logo_slogan' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'logo_slogan' )); ?>" value="<?php echo esc_attr($instance['logo_slogan']); ?>" class="widgets_param_fullwidth" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show logo:', 'micro-office'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="1" <?php echo (1==$show_logo ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_1"><?php esc_html_e('Show', 'micro-office'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_logo')); ?>" value="0" <?php echo (0==$show_logo ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_logo')); ?>_0"><?php esc_html_e('Hide', 'micro-office'); ?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show social icons:', 'micro-office'); ?></label><br />
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="1" <?php echo (1==$show_icons ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_1"><?php esc_html_e('Show', 'micro-office'); ?></label>
			<input type="radio" id="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0" name="<?php echo esc_attr($this->get_field_name('show_icons')); ?>" value="0" <?php echo (0==$show_icons ? ' checked="checked"' : ''); ?> />
			<label for="<?php echo esc_attr($this->get_field_id('show_icons')); ?>_0"><?php esc_html_e('Hide', 'micro-office'); ?></label>
		</p>

	<?php
	}
}



// trx_widget_socials
//-------------------------------------------------------------

if ( !function_exists( 'micro_office_sc_widget_socials' ) ) {
	function micro_office_sc_widget_socials($atts, $content=null){	
		$atts = micro_office_html_decode(shortcode_atts(array(
			// Individual params
			"title" => "",
			"text" => "",
			"logo_image" => "",
			"logo_text" => "",
			"logo_slogan" => "",
			"show_logo" => 1,
			"show_icons" => 1,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		if ($atts['show_logo']=='') $atts['show_logo'] = 0;
		if ($atts['show_icons']=='') $atts['show_icons'] = 0;
		extract($atts);
		$type = 'micro_office_widget_socials';
		$output = '';
		global $wp_widget_factory;
		if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
			$output = '<div' . ($id ? ' id="'.esc_attr($id).'"' : '')
							. ' class="widget_area sc_widget_socials' 
								. (micro_office_exists_visual_composer() ? ' vc_widget_socials wpb_content_element' : '') 
								. (!empty($class) ? ' ' . esc_attr($class) : '') 
						. '">';
			ob_start();
			the_widget( $type, $atts, micro_office_prepare_widgets_args(micro_office_storage_get('widgets_args'), $id ? $id.'_widget' : 'widget_socials', 'widget_socials') );
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
		}
		return apply_filters('micro_office_shortcode_output', $output, 'trx_widget_socials', $atts, $content);
	}
	micro_office_require_shortcode("trx_widget_socials", "micro_office_sc_widget_socials");
}


// Add [trx_widget_socials] in the VC shortcodes list
if (!function_exists('micro_office_widget_socials_reg_shortcodes_vc')) {
	function micro_office_widget_socials_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_socials",
				"name" => esc_html__("Widget Socials", "micro-office"),
				"description" => wp_kses_data( __("Insert site logo, description and/or socials list", "micro-office") ),
				"category" => esc_html__('Content', 'micro-office'),
				"icon" => 'icon_trx_widget_socials',
				"class" => "trx_widget_socials",
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
						"param_name" => "text",
						"heading" => esc_html__("Widget text", "micro-office"),
						"description" => wp_kses_data( __("Any description", "micro-office") ),
						"class" => "",
						"value" => "",
						"type" => "textarea"
					),
					array(
						"param_name" => "show_logo",
						"heading" => esc_html__("Show logo", "micro-office"),
						"description" => wp_kses_data( __("Do you want display logo image?", "micro-office") ),
						"class" => "",
						"std" => 1,
						"value" => array("Show logo" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "show_icons",
						"heading" => esc_html__("Show social icons", "micro-office"),
						"description" => wp_kses_data( __("Do you want display social icons?", "micro-office") ),
						"class" => "",
						"std" => 1,
						"value" => array("Show icons" => "1" ),
						"type" => "checkbox"
					),
					array(
						"param_name" => "logo_image",
						"heading" => esc_html__("Logo image", "micro-office"),
						"description" => wp_kses_data( __("Select or upload image or write URL from other site for the logo (leave empty if you want use default site logo)", "micro-office") ),
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "attach_image"
					),
					array(
						"param_name" => "logo_text",
						"heading" => esc_html__("Logo text", "micro-office"),
						"description" => wp_kses_data( __("Site name for the logo(leave empty if you want use default site text)", "micro-office") ),
						"admin_label" => true,
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					array(
						"param_name" => "logo_slogan",
						"heading" => esc_html__("Logo slogan", "micro-office"),
						"description" => wp_kses_data( __("Site slogan for the logo(leave empty if you want use default site tagline)", "micro-office") ),
						'dependency' => array(
							'element' => 'show_logo',
							'not_empty' => true
						),
						"class" => "",
						"value" => "",
						"type" => "textfield"
					),
					micro_office_get_vc_param('id'),
					micro_office_get_vc_param('class'),
					micro_office_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Socials extends WPBakeryShortCode {}

	}
}
?>