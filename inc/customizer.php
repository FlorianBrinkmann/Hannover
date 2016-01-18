<?php
/**
 * File for everything regarding to the customizer
 *
 * @version 1.0.8
 */

/**
 * Registers customizer settings, controls, panels and sections
 *
 * @param $wp_customize
 */
function hannover_customize_register( $wp_customize ) {
	$wp_customize->add_setting(
		'portfolio_from_category', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'portfolio_from_category', array(
			'label'    => __( 'Use a category instead of all gallery and image posts for portfolio elements', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'portfolio_elements',
			'settings' => 'portfolio_from_category'
		)
	);

	$categories     = get_categories();
	$category_array = array();
	if ( ! empty( $categories ) ) {
		foreach ( $categories as $category ) {
			$category_array[ $category->term_id ] = $category->name;
		}
	}

	$wp_customize->add_setting(
		'portfolio_category', array(
			'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_control(
		'portfolio_category', array(
			'label'           => __( 'Portfolio category', 'hannover' ),
			'type'            => 'select',
			'section'         => 'portfolio_elements',
			'settings'        => 'portfolio_category',
			'choices'         => $category_array,
			'active_callback' => 'hannover_use_portfolio_category_callback'
		)
	);

	$wp_customize->add_setting(
		'portfolio_remove_category_from_cat_widget', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'portfolio_remove_category_from_cat_list', array(
			'label'           => __( 'Remove portfolio category from category widget', 'hannover' ),
			'type'            => 'checkbox',
			'section'         => 'portfolio_elements',
			'settings'        => 'portfolio_remove_category_from_cat_widget',
			'active_callback' => 'hannover_use_portfolio_category_callback'
		)
	);

	$wp_customize->add_setting(
		'exclude_portfolio_elements_from_blog', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'exclude_portfolio_elements_from_blog', array(
			'label'    => __( 'Exclude the portfolio elements from the blog', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'portfolio_elements',
			'settings' => 'exclude_portfolio_elements_from_blog'
		)
	);

	$wp_customize->add_setting(
		'portfolio_elements_per_page', array(
			'default'           => 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'portfolio_elements_per_page', array(
			'label'    => __( 'Number of portfolio elements to show on one page (0 to show all elements on one page).', 'hannover' ),
			'type'     => 'number',
			'section'  => 'portfolio_elements',
			'settings' => 'portfolio_elements_per_page',
		)
	);

	$wp_customize->add_setting(
		'portfolio_alt_layout', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'portfolio_alt_layout', array(
			'label'    => __( 'Use alternative layout for portfolio overview', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'portfolio_elements',
			'settings' => 'portfolio_alt_layout'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive', array(
			'default'           => 'no_archive',
			'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_control(
		'portfolio_archive', array(
			'label'    => __( 'Choose if you want to select portfolio elements for the archive.', 'hannover' ),
			'type'     => 'radio',
			'section'  => 'portfolio_archive',
			'settings' => 'portfolio_archive',
			'choices'  => array(
				'no_archive'       => 'No archive',
				'archive_category' => 'Archive category',
			)
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive_category', array(
			'sanitize_callback' => 'hannover_sanitize_select'
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_category', array(
			'label'           => __( 'Archive category', 'hannover' ),
			'type'            => 'select',
			'section'         => 'portfolio_archive',
			'settings'        => 'portfolio_archive_category',
			'choices'         => $category_array,
			'active_callback' => 'hannover_choice_callback'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive_remove_category_from_cat_widget', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_remove_category_from_cat_widget', array(
			'label'           => __( 'Remove archive category from category widget', 'hannover' ),
			'type'            => 'checkbox',
			'section'         => 'portfolio_archive',
			'settings'        => 'portfolio_archive_remove_category_from_cat_widget',
			'active_callback' => 'hannover_choice_callback'
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive_elements_per_page', array(
			'default'           => 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_elements_per_page', array(
			'label'    => __( 'Number of archived portfolio elements to show on one page (0 to show all elements on one page).', 'hannover' ),
			'type'     => 'number',
			'section'  => 'portfolio_archive',
			'settings' => 'portfolio_archive_elements_per_page',
		)
	);

	$wp_customize->add_setting(
		'portfolio_archive_alt_layout', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox'
		)
	);

	$wp_customize->add_control(
		'portfolio_archive_alt_layout', array(
			'label'    => __( 'Use alternative layout for archive', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'portfolio_archive',
			'settings' => 'portfolio_archive_alt_layout'
		)
	);

	$portfolio_category_pages = get_posts(
		array(
			'post_type'  => 'page',
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'page-templates/portfolio-category-page.php'
		)
	);


	if ( ! empty( $portfolio_category_pages ) ) {
		foreach ( $portfolio_category_pages as $portfolio_category_page ) {
			$id = $portfolio_category_page->ID;
			$wp_customize->add_setting(
				"portfolio_category_page_$id", array(
					'sanitize_callback' => 'hannover_sanitize_select'
				)
			);

			/* translators: Label for portfolio category pages customizer control. s=page title */
			$label = sprintf(
				__( 'Choose portfolio category to show on "%s"', 'hannover' ),
				esc_html( $portfolio_category_page->post_title )
			);
			$wp_customize->add_control(
				"portfolio_category_page_$id", array(
					'label'    => $label,
					'type'     => 'select',
					'section'  => 'portfolio_category_pages',
					'settings' => "portfolio_category_page_$id",
					'choices'  => $category_array,
				)
			);

			$wp_customize->add_setting(
				"portfolio_category_page_elements_per_page_$id", array(
					'default'           => 0,
					'sanitize_callback' => 'absint'
				)
			);

			$wp_customize->add_control(
				"portfolio_category_page_elements_per_page_$id", array(
					'label'    => __( 'Number of elements to show on one page (0 to show all).', 'hannover' ),
					'type'     => 'number',
					'section'  => 'portfolio_category_pages',
					'settings' => "portfolio_category_page_elements_per_page_$id",
				)
			);

			$wp_customize->add_setting(
				"portfolio_category_page_alt_layout_$id", array(
					'sanitize_callback' => 'hannover_sanitize_checkbox'
				)
			);

			$wp_customize->add_control(
				"portfolio_category_page_alt_layout_$id", array(
					'label'    => __( 'Use alternative layout', 'hannover' ),
					'type'     => 'checkbox',
					'section'  => 'portfolio_category_pages',
					'settings' => "portfolio_category_page_alt_layout_$id"
				)
			);
		}
	}

	$wp_customize->add_setting(
		'slider_autoplay', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'slider_autoplay', array(
			'label'    => __( 'Enable autoplay', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'slider_settings',
			'settings' => 'slider_autoplay'
		)
	);

	$wp_customize->add_setting(
		'slider_autoplay_time', array(
			'default'           => 3000,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'slider_autoplay_time', array(
			'label'    => __( 'Time in milliseconds to show each image with autoplay', 'hannover' ),
			'type'     => 'number',
			'section'  => 'slider_settings',
			'settings' => 'slider_autoplay_time',
		)
	);

	$wp_customize->add_setting(
		'galleries_as_slider', array(
			'sanitize_callback' => 'hannover_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'galleries_as_slider', array(
			'label'    => __( 'Display all galleries as sliders', 'hannover' ),
			'type'     => 'checkbox',
			'section'  => 'slider_settings',
			'settings' => 'galleries_as_slider'
		)
	);

	$wp_customize->add_panel(
		'theme_options', array(
			'title' => __( 'Theme Options', 'hannover' ),
		)
	);

	$wp_customize->add_section(
		'portfolio_elements', array(
			'title' => __( 'Portfolio elements', 'hannover' ),
			'panel' => 'theme_options'
		)
	);

	$wp_customize->add_section(
		'portfolio_archive', array(
			'title' => __( 'Portfolio archive', 'hannover' ),
			'panel' => 'theme_options'
		)
	);

	$wp_customize->add_section(
		'portfolio_category_pages', array(
			'title'       => __( 'Portfolio category pages', 'hannover' ),
			'description' => __( 'Here you can choose the category to display on the respective portfolio category page. The category must include portfolio elements—otherwise nothing will be displayed.', 'hannover' ),
			'panel'       => 'theme_options'
		)
	);

	$wp_customize->add_section(
		'slider_settings', array(
			'title' => __( 'Slider settings', 'hannover' ),
			'panel' => 'theme_options'
		)
	);
}

add_action( 'customize_register', 'hannover_customize_register' );

/**
 * Checks if checkbox for portfolio category is checked and returns true, otherwise false
 *
 * @param $control
 *
 * @return bool
 */
function hannover_use_portfolio_category_callback( $control ) {
	if ( $control->manager->get_setting( 'portfolio_from_category' )->value() == 'checked' ) {
		return true;
	} else {
		return false;
	}
}

function hannover_header_textcolor() {
	if ( get_theme_mod( 'header_textcolor' ) ) { ?>
		<style type="text/css">
			#header,
			#header a {
				color: <?php echo '#' . get_theme_mod('header_textcolor'); ?>;
			}
		</style>
	<?php }
}

add_action( 'wp_head', 'hannover_header_textcolor' );

/**
 * Callback function for controls dependent from radio controls
 *
 * @param $control
 *
 * @return bool
 */
function hannover_choice_callback( $control ) {
	$radio_setting = $control->manager->get_setting( 'portfolio_archive' )->value();
	$control_id    = $control->id;
	if ( $control_id == 'portfolio_archive_category' && $radio_setting == 'archive_category' ) {
		return true;
	} elseif ( $control_id == 'portfolio_archive_remove_category_from_cat_widget' && $radio_setting == 'archive_category' ) {
		return true;
	}

	return false;
}

/**
 * Sanitizes select input
 *
 * @param $input , $setting
 *
 * @return string
 */
function hannover_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitizes checkbox input
 *
 * @param $checked
 *
 * @return bool
 */
function hannover_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}