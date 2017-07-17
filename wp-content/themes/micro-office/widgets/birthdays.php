<?php
/**
 * Theme Widget: Advanced Calendar
 */

// Theme init
if (!function_exists('micro_office_widget_birthdays_theme_setup')) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_widget_birthdays_theme_setup', 1 );
	function micro_office_widget_birthdays_theme_setup() {

		// Register shortcodes in the shortcodes list
		if (function_exists('micro_office_exists_visual_composer') && micro_office_exists_visual_composer())
			add_action('micro_office_action_shortcodes_list_vc','micro_office_widget_birthdays_reg_shortcodes_vc');
	}
}

// Load widget
if (!function_exists('micro_office_widget_birthdays_load')) {
	add_action( 'widgets_init', 'micro_office_widget_birthdays_load' );
	function micro_office_widget_birthdays_load() {
		register_widget( 'micro_office_widget_birthdays' );
	}
}

// Widget Class
class micro_office_widget_birthdays extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_birthdays', 'description' => esc_html__('Display birthday list', 'micro-office') );
		parent::__construct( 'micro_office_widget_birthdays', esc_html__('Micro Office - Birthdays list', 'micro-office'), $widget_ops );
	}

	// Show widget
	function widget( $args, $instance ) {

		extract( $args );

		$title = apply_filters('widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$number = isset($instance['number']) ? (int) $instance['number'] : '';

		$args = array('number' => $number);

		// Before widget (defined by themes)
		micro_office_show_layout($before_widget);

		if ($title) micro_office_show_layout($before_title . $title . $after_title);
		?>			
		<div class="widget_birthdays_inner">
				<ul>
					<?php
					echo do_shortcode('[trx_widget_birthdays number="'.esc_attr($number).'"]');
					?>
				</ul>
		</div>
		<?php

		// After widget (defined by themes)
		micro_office_show_layout($after_widget);
	}

	// Update the widget settings.
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		return $instance;
	}

	// Displays the widget settings controls on the widget panel.
	function form( $instance ) {

		// Set up some default widget settings
		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> '',
			'number'		=> '2',
			)
		);

		$title = $instance['title'];
		$number = (int) $instance['number'];
		
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e( 'Title:', 'micro-office' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number posts to show:', 'micro-office'); ?></label>
			<input type="text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" value="<?php echo esc_attr($number); ?>" class="widgets_param_fullwidth" />
		</p>
		<?php
	}
}



// trx_widget_birthdays
//-------------------------------------------------------------

if ( !function_exists( 'micro_office_sc_widget_birthdays' ) ) {
	function micro_office_sc_widget_birthdays($atts, $content=null){	
		$atts = micro_office_html_decode(shortcode_atts(array(
			// Individual params
			'number' => 2,
			// Common params
			"id" => "",
			"class" => "",
			"css" => ""
		), $atts));
		$output = '';
		$members = get_users(array('fields' => array('ID')));
		$members_birthdays = array();	
		
		foreach($members as $member) {
			$birthday = micro_office_get_user_bithday($member->ID);
			
			if($birthday) {
				$date_created = date("m-d", strtotime($birthday));
				$members_birthdays[$member->ID] =  $date_created;
			}
		}
		asort($members_birthdays);
		$members_birthdays = micro_office_birthday_sort($members_birthdays);
		$i = 0;
		
		foreach($members_birthdays as $key => $value){
			$i++;
			if($i <= $atts['number']){
				$user_info = get_userdata($key);
				$date = micro_office_get_user_bithday($key);
				$year = date('Y') - date("Y", strtotime($date));
				$month = date("M d", strtotime($date));
				$output .= 	'<li><div class="post_thumb">'.get_avatar($key).'</div>'
							.'<div class="post_title"><strong>'.esc_html($user_info->user_login).'</strong>'
							.' <span>('.esc_html($year).')</span> '.esc_html__("on", "micro-office").' <span class="date">'.esc_html($month).'</span>'
							.'</div></li>';
			}
		}
		
		return apply_filters('micro_office_shortcode_output', $output, 'trx_widget_birthdays', $atts, $content);
	}
	micro_office_require_shortcode("trx_widget_birthdays", "micro_office_sc_widget_birthdays");
}

add_action( 'bp_profile_header_meta', 'micro_office_get_user_bithday' );
function micro_office_get_user_bithday($id) {
     $args = array(
        'field'   => 'Birthday', // Field name or ID.
        'user_id' => $id
        );
    $birthday = bp_get_profile_field_data( $args );
 
    if ($birthday) {
        return $birthday;
    }
}

if ( !function_exists( 'micro_office_birthday_sort' ) ) {
	function micro_office_birthday_sort($members_birthdays){	
		$today = date('m-d');
		foreach($members_birthdays as $key => $value){
			if($today > $value){
				unset($members_birthdays[$key]);
				$members_birthdays[$key] = $value;
			}			
		}	
		return $members_birthdays;
	}
}


// Add [trx_widget_birthdays] in the VC shortcodes list
if (!function_exists('micro_office_widget_birthdays_reg_shortcodes_vc')) {
	
	function micro_office_widget_birthdays_reg_shortcodes_vc() {
		
		vc_map( array(
				"base" => "trx_widget_birthdays",
				"name" => esc_html__("Widget Birthdays", "micro-office"),
				"description" => wp_kses_data( __("Display the birthdays list", "micro-office") ),
				"category" => esc_html__('Content', 'micro-office'),
				"icon" => 'icon_trx_widget_birthdays',
				"class" => "trx_widget_birthdays",
				"content_element" => true,
				"is_container" => false,
				"show_settings_on_create" => true,
				"params" => array(
					array(
						"param_name" => "number",
						"heading" => esc_html__("Number posts to show", "micro-office"),
						"description" => wp_kses_data( __("How many posts display in widget?", "micro-office") ),
						"class" => "",
						"std" => "5",
						"type" => "textfield"
					),
					micro_office_get_vc_param('id'),
					micro_office_get_vc_param('class'),
					micro_office_get_vc_param('css')
				)
			) );
			
		class WPBakeryShortCode_Trx_Widget_Birthdays extends WPBakeryShortCode {}

	}
}
?>