<?php
/**
 * All add_filter() calls.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

// Filter the dynamically created customizer settings.
add_filter( 'customize_dynamic_setting_args', 'hannover_filter_dynamic_setting_args', 10, 2 );

// Filter category widget args.
add_filter( 'widget_categories_args', 'hannover_filter_category_widget' );

// Remove more link jmp.
add_filter( 'the_content_more_link', 'hannover_remove_more_link_scroll' );
