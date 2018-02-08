<?php
/**
 * All add_action() calls.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

// Register customizer settings.
add_action( 'customize_register', 'hannover_customize_register' );

// Include scripts into the customizer.
add_action( 'customize_controls_enqueue_scripts', 'hannover_customize_controls_script' );

// Include customize control templates into the customizer.
add_action( 'customize_controls_print_footer_scripts', 'hannover_customize_control_templates' );

// Include control CSS into the customizer.
add_action( 'customize_controls_print_styles', 'hannover_customize_controls_styles', 999 );

// Remove theme mods after portfolio category page was removed.
add_action( 'customize_save_after', 'hannover_customize_save_after' );
