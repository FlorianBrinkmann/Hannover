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
		'portfolio_page[id]', array(
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[active]', array(
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[from_category]', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_setting(
		'portfolio_page[category]', array(//'sanitize_callback' => 'hannover_sanitize_select'
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
		'portfolio_archive[id]', array(
			'default'           => 'no_archive',
			'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[active]', array(
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive[category]', array(//'sanitize_callback' => 'hannover_sanitize_select'
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
		<div class="hannover-customize-control-text-wrapper">
			<p class="customize-control-title"><?php _e( 'Portfolio feature', 'hannover' ); ?></p>
			<p class="customize-control-description">
				<?php _e( 'Hannover allows you to display all of your posts (with the image or gallery post type) from a specific category on one page. These posts are called »portfolio elements«.', 'hannover' ); ?>
			</p>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-no-portfolio-page-notice">
		<div class="hannover-customize-control-text-wrapper">
			<p class="customize-control-description">
				<?php _e( 'It looks like your site does not have a portfolio yet.', 'hannover' ); ?>
			</p>
			<button type="button" class="button hannover-open-portfolio-page-section-button">
				<?php _e( 'Create Portfolio', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-deactivate-portfolio-button">
		<div class="hannover-customize-control-text-wrapper">
			<button type="button" class="button-link button-link-delete hannover-deactivate-portfolio-button">
				<?php _e( 'Deactivate portfolio', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-create-portfolio-button">
		<div class="hannover-customize-control-text-wrapper create-portfolio">
			<button type="button" class="button hannover-create-portfolio-button">
				<?php _e( 'Create portfolio', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-portfolio-archive-notice">
		<div class="hannover-customize-control-text-wrapper">
			<p class="customize-control-title"><?php _e( 'Portfolio archive', 'hannover' ); ?></p>
			<p class="customize-control-description">
				<?php _e( 'You can remove elements from a specific category from the portfolio page and display them on an archive page.', 'hannover' ); ?>
			</p>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-no-portfolio-archive-page-notice">
		<div class="hannover-customize-control-text-wrapper">
			<button type="button" class="button hannover-open-portfolio-archive-section-button">
				<?php _e( 'Create Archive', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-deactivate-portfolio-archive-button">
		<div class="hannover-customize-control-text-wrapper">
			<button type="button" class="button-link button-link-delete hannover-deactivate-portfolio-archive-button">
				<?php _e( 'Deactivate archive', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-create-portfolio-archive-button">
		<div class="hannover-customize-control-text-wrapper create-portfolio">
			<button type="button" class="button hannover-create-portfolio-archive-button">
				<?php _e( 'Create archive', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-portfolio-category-pages-notice">
		<div class="hannover-customize-control-text-wrapper hannover-category-pages-sections-description">
			<p class="customize-control-title"><?php _e( 'Portfolio category pages', 'hannover' ); ?></p>
			<p class="customize-control-description">
				<?php _e( 'You can create one or more pages that just show portfolio elements from a specific category.', 'hannover' ); ?>
			</p>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-add-portfolio-category-page-button">
		<div class="hannover-customize-control-text-wrapper">
			<button type="button" class="button hannover-customize-create-portfolio-category-page">
				<?php _e( 'Add category page', 'hannover' ); ?>
			</button>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-delete-portfolio-category-page-button">
		<div class="hannover-customize-control-text-wrapper delete-portfolio-category-page">
			<button type="button" class="button-link button-link-delete">
				<?php _e( 'Delete category page', 'hannover' ); ?>
			</button>
			<p><?php _e( 'This does not remove the page itself.', 'hannover' ); ?></p>
		</div>
	</script>

	<script type="text/html" id="tmpl-hannover-add-category-page-button">
		<div class="hannover-customize-control-text-wrapper delete-portfolio-category-page">
			<button type="button" class="button hannover-add-category-page-button">
				<?php _e( 'Add the page', 'hannover' ); ?>
			</button>
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
		'portfolio_category_page_id'                => '/^portfolio_category_page\[\d+\]\[id]/',
		'portfolio_category_page_category'          => '/^portfolio_category_page\[\d+\]\[category]/',
		'portfolio_category_page_elements_per_page' => '/^portfolio_category_page\[\d+\]\[elements_per_page]/',
		'portfolio_category_page_alt_layout'        => '/^portfolio_category_page\[\d+\]\[alt_layout]/',
		'portfolio_category_page_deleted'           => '/^portfolio_category_page\[(\d+)\]\[deleted\]/',
	];

	// Match for the portfolio category page id setting.
	if ( preg_match( $id_patterns['portfolio_category_page_id'], $setting_id ) ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}

	// Match for the portfolio category page category setting.
	if ( preg_match( $id_patterns['portfolio_category_page_category'], $setting_id ) ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}

	// Match for the portfolio category page elements per page setting.
	if ( preg_match( $id_patterns['portfolio_category_page_elements_per_page'], $setting_id ) ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}

	// Match for the portfolio category page alt layout setting.
	if ( preg_match( $id_patterns['portfolio_category_page_alt_layout'], $setting_id ) ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}

	// Match for the deleted portfolio category page setting.
	if ( preg_match( $id_patterns['portfolio_category_page_deleted'], $setting_id, $matches ) ) {
		$setting_args = [
			'type' => 'theme_mod',
		];
	}

	return $setting_args;
}

/**
 * Fires after customize settings are saved.
 *
 * @param WP_Customize_Manager $manager Instance of WP_Customize_Manager.
 */
function hannover_customize_save_after( $manager ) {
	// Get the theme mods.
	$theme_mods = get_theme_mods();

	// Loop them.
	foreach ( $theme_mods['portfolio_category_page'] as $id => $portfolio_category_page_theme_mod ) {
		// Check if the delete flag is set.
		if ( isset( $portfolio_category_page_theme_mod['deleted'] ) && - 1 === $portfolio_category_page_theme_mod['deleted'] ) {
			// Code inspired by the remove_theme_mod() function.
			unset( $theme_mods['portfolio_category_page'][ $id ] );

			// Check if we have no more portfolio category pages and if so, unset the array index.
			if ( empty( $theme_mods['portfolio_category_page'] ) ) {
				unset( $theme_mods['portfolio_category_page'] );
			} // End if().

			// Check if that was the last theme mod and if so, remove the entry from the database.
			if ( empty ( $theme_mods ) ) {
				remove_theme_mods();
			} else {
				// Get theme slug and update the theme mod option.
				$theme = get_option( 'stylesheet' );
				update_option( "theme_mods_$theme", $theme_mods );
			} // End if().
		} // End if().
	} // End foreach().
}

// Include file with customizer callback functions.
require_once __DIR__ . '/callbacks.php';

// Include file with script and style functions for the customizer.
require_once __DIR__ . '/scripts-and-styles.php';
