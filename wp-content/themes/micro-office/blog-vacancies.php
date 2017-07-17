<?php
/*
Template Name: Vacancies list
*/

/**
 * Make empty page with this template 
 * and put it into menu
 * to display all Vacancies as streampage
 */

micro_office_storage_set('blog_filters', 'vacancies');

get_template_part('blog');
?>