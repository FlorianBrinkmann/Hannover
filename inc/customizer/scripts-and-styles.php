<?php
/**
 * Functions which include scripts and styles for the Customizer.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Prints CSS inside header.
 */
function hannover_customizer_css() {
	// Check if header text should be displayed. Otherwise hide it.
	if ( display_header_text() ) {
		return;
	} else { ?>
		<style type="text/css">
			.site-title,
			.site-description {
				clip: rect(1px, 1px, 1px, 1px);
				height: 1px;
				overflow: hidden;
				position: absolute !important;
				width: 1px;
				word-wrap: normal !important;
			}
		</style>
	<?php }
}

/**
 * Prints styles for the customize controls.
 */
function hannover_customize_controls_styles() { ?>
	<style>
		.hannover-customize-control-text-wrapper {
			margin-bottom: 24px;
			padding-left: 12px;
			padding-right: 12px;
		}

		.no-border-top,
		#accordion-section-hannover_portfolio_page_section {
			border-top: 0 !important;
		}

		.button.hannover-customize-create-portfolio-category-page {
			margin-top: 24px;
		}

		li[id*=accordion-section-hannover_portfolio_category_page_section] .hannover-customize-control-text-wrapper + .hannover-customize-control-text-wrapper .hannover-customize-create-portfolio-category-page {
			margin-top: 0;
		}

		.accordion-section-title .page-category {
			display: block;
			font-weight: 600;
			font-size: 10px;
		}
	</style>
<?php }

/**
 * Prints styles inside the customizer view.
 */
function hannover_customizer_controls_script() {
	// Add script control section.
	wp_enqueue_script( 'hannover-customize-controls', get_theme_file_uri( 'assets/js/customize/controls.js' ), [], null, true );

	// We need a few things from PHP in the controls.js.
	// Get the categories.
	$categories = get_categories();

	// Build category array.
	$category_array = array();
	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$category_array[ $category->term_id ] = $category->name;
		}
	}

	// Build array that is handed to the hannoverCustomizeControls function.
	$exports = [
		'l10n'       => [
			/* translators: Title of the theme options panel in the Customizer. */
			'panelTitle' => __( 'Theme Options', 'hannover' ),
		],
		'categories' => $category_array,
		'themeMods'  => get_theme_mods(),
	];

	// Add inline script to init the controls component and submit internationalized strings.
	wp_add_inline_script( 'hannover-customize-controls', sprintf( 'hannoverCustomizeControls.init( %s );', wp_json_encode( $exports ) ) );
}
