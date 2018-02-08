<?php
/**
 * All add_action() calls.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

// Load translation from W.org if available.
add_action( 'after_setup_theme', 'hannover_load_translation' );

// Set content width.
add_action( 'after_setup_theme', 'hannover_set_content_width' );

// Run add_theme_support() things.
add_action( 'after_setup_theme', 'hannover_add_theme_support' );

// Register menu locations.
add_action( 'init', 'hannover_register_menus' );

// Register widget area.
add_action( 'widgets_init', 'hannover_register_sidebars' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'hannover_scripts_styles' );

// Exclude portfolio elements from blog view.
add_action( 'pre_get_posts', 'hannover_exlude_portfolio_elements_from_blog' );

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
