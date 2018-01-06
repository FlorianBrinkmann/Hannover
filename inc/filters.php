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
