<?php
/**
 * File for everything regarding to the customizer.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Registers customizer settings, controls, panels and sections.
 *
 * @param $wp_customize
 */
function hannover_customize_register( $wp_customize ) {

	// Include class files for panel and controls.
	require_once __DIR__ . '/class-hannover-customize-theme-options-panel.php';
	require_once __DIR__ . '/class-hannover-customize-categories-control.php';
	require_once __DIR__ . '/class-hannover-customize-dropdown-pages-control.php';

	// Register panel and controls.
	$wp_customize->register_panel_type( 'Hannover_Customize_Theme_Options_Panel' );
	$wp_customize->register_control_type( 'Hannover_Customize_Categories_Control' );

	$wp_customize->add_setting(
		'portfolio_page[from_category]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[id]', array(
			//'sanitize_callback' => 'hannover_sanitize_select',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[category]', array(
			//'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[remove_category_from_cat_list]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[exclude_portfolio_elements_from_blog]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[elements_per_page]', array(
			'default'           => 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[alt_layout]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive', array(
			'default'           => 'no_archive',
			'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[category]', array(
			//'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[remove_category_from_cat_widget]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[elements_per_page]', array(
			'default'           => 5,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[alt_layout]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_setting(
		'slider_autoplay', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_setting(
		'slider_autoplay_time', array(
			'default'           => 3000,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_setting(
		'galleries_as_slider', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);
}

/**
 * Prints control templates into the customizer.
 */
function hannover_customize_control_templates() { ?>
	<script type="text/html" id="tmpl-hannover-portfolio-section-title">
		<div class="hannover-customize-control-text-wrapper" id="hannover-portfolio-section-title">
			<p class="customize-control-title"><?php _e( 'Portfolio feature', 'hannover' ); ?></p>
			<p class="customize-control-description">
				<?php _e( 'Hannover allows you to display all of your posts (or the ones from a specific category) with the image or gallery post type as a portfolio.', 'hannover' ); ?>
			</p>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-no-portfolio-page-notice">
		<div class="hannover-customize-control-text-wrapper">
			<p class="customize-control-description">
				<?php _e( 'It looks like your site does not have a portfolio yet.', 'hannover' ); ?>
			</p>
			<button type="button" class="button hannover-customize-create-portfolio-page">
				<?php _e( 'Create Portfolio', 'hannover' ); ?>
			</button>
		</div>
	</script>


	<script type="text/html" id="tmpl-hannover-create-portfolio-button">
		<div class="hannover-customize-control-text-wrapper">
			<button type="button" class="button hannover-customize-create-portfolio-page">
				<?php _e( 'Create Portfolio', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-no-portfolio-archive-page-notice">
		<div class="hannover-customize-control-text-wrapper" id="hannover-portfolio-section-title">
			<p class="customize-control-description">
				<?php _e( 'You can remove posts from a specific category from the portfolio page and display them on an archive page.', 'hannover' ); ?>
			</p>
		</div>
	</script>
<?php }

/**
 * Filters a dynamic setting's constructor args.
 *
 * For a dynamic setting to be registered, this filter must be employed
 * to override the default false value with an array of args to pass to
 * the WP_Customize_Setting constructor.
 *
 * @link https://wordpress.stackexchange.com/a/286503/112824
 *
 * @param false|array $setting_args The arguments to the WP_Customize_Setting constructor.
 * @param string      $setting_id   ID for dynamic setting, usually coming from `$_POST['customized']`.
 *
 * @return array|false
 */
function hannover_filter_dynamic_setting_args( $setting_args, $setting_id ) {
	// Create array of ID patterns.
	$id_patterns = [
		'portfolio_category_page' => '/^portfolio_category_page\[(?P<id>-?\d+)\]$/',
		'portfolio_category_page_category' => '/^portfolio_category_page_category\[(?P<id>-?\d+)\]$/',
		'portfolio_category_page_elements_per_page' => '/^portfolio_category_page_elements_per_page\[(?P<id>-?\d+)\]$/',
		'portfolio_category_page_alt_layout' => '/^portfolio_category_page_alt_layout\[(?P<id>-?\d+)\]$/',
	];
	/*if ( preg_match( WP_Customize_Nav_Menu_Setting::ID_PATTERN, $setting_id ) ) {
		$setting_args = array(
			'type' => 'theme_mod',
		);
	}*/
	if ( 'portfolio_category_page' === $setting_id ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}
	return $setting_args;
}

// Include file with customizer callback functions.
require_once __DIR__ . '/callbacks.php';

// Include file with script and style functions for the customizer.
require_once __DIR__ . '/scripts-and-styles.php';
