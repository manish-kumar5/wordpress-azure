<?php

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }


/* Theme setup section
-------------------------------------------------------------------- */

if ( !function_exists( 'micro_office_template_vacancies_1_theme_setup' ) ) {
	add_action( 'micro_office_action_before_init_theme', 'micro_office_template_vacancies_1_theme_setup', 1 );
	function micro_office_template_vacancies_1_theme_setup() {
		micro_office_add_template(array(
			'layout' => 'vacancies-1',
			'template' => 'vacancies-1',
			'mode'   => 'vacancies',
			'title'  => esc_html__('Vacancies /Style 1/', 'micro-office'),
			'thumb_title'  => esc_html__('Medium image (crop)', 'micro-office'),
			'w'		 => 135,
			'h'		 => 100
		));
	}
}

// Template output
if ( !function_exists( 'micro_office_template_vacancies_1_output' ) ) {
	function micro_office_template_vacancies_1_output($post_options, $post_data) {
		$vacancy_position = $post_options['vacancy_position'];
		$vacancy_location = $post_options['vacancy_location'];
		$vacancy_employment = $post_options['vacancy_employment'];
		$vacancy_salary = $post_options['vacancy_salary'];
		$employment = $vacancy_employment == 'freelance' ? 'Freelance' : ($vacancy_employment == 'full' ? 'Full Time' : 'Part Time');
		?>
		<tr>
			<td class="position">
				<a href="<?php echo esc_url($post_options['vacancy_link']); ?>">
					<span class="post_position"><?php echo ($vacancy_position); ?></span>
				</a> 
			</td>
			<td class="location">
				<a href="<?php echo esc_url($post_options['vacancy_link']); ?>"><?php echo ($vacancy_location); ?></a>
			</td>
			<td class="employment">
				<span class="<?php echo ($vacancy_employment); ?>"><?php echo ($employment); ?></span>
			</td>
			<td class="salary">
				<a href="<?php echo esc_url($post_options['vacancy_link']); ?>"><?php echo ($vacancy_salary); ?></a>
			</td>
		</tr>
		
		<?php
	}
}
?>