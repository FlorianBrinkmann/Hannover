<?php
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
			'sanitize_callback' => 'hannover_sanitize_positive_int'
		)
	);

	$wp_customize->add_control(
		'portfolio_elements_per_page', array(
			'label'    =>
				__( 'Number of portfolio elements to show on one page (0 to show all elements on one page).', 'hannover' ),
			'type'     => 'number',
			'section'  => 'portfolio_elements',
			'settings' => 'portfolio_elements_per_page',
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
			'label'    => __( 'Choose how you want to select portfolio elements for the archive.', 'hannover' ),
			'type'     => 'radio',
			'section'  => 'portfolio_archive',
			'settings' => 'portfolio_archive',
			'choices'  => array(
				'no_archive'       => 'No archive',
				'archive_category' => 'Archive category',
				'auto_archive'     => 'Automatic archive less recent elements'
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
		'portfolio_auto_archive', array(
			'default'           => 3,
			'sanitize_callback' => 'hannover_sanitize_positive_int'
		)
	);

	$wp_customize->add_control(
		'portfolio_auto_archive', array(
			'label'           => __( 'Number of recent elements which will NOT be archived.', 'hannover' ),
			'type'            => 'number',
			'section'         => 'portfolio_archive',
			'settings'        => 'portfolio_auto_archive',
			'active_callback' => 'hannover_choice_callback'
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
}

add_action( 'customize_register', 'hannover_customize_register' );

function hannover_use_portfolio_category_callback( $control ) {
	if ( $control->manager->get_setting( 'portfolio_from_category' )->value() == 'checked' ) {
		return true;
	} else {
		return false;
	}
}

function hannover_choice_callback( $control ) {
	$radio_setting = $control->manager->get_setting( 'portfolio_archive' )->value();
	$control_id    = $control->id;
	if ( $control_id == 'portfolio_archive_category' && $radio_setting == 'archive_category' ) {
		return true;
	}
	if ( $control_id == 'portfolio_auto_archive' && $radio_setting == 'auto_archive' ) {
		return true;
	}

	return false;
}

function hannover_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function hannover_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

function hannover_sanitize_positive_int( $number, $setting ) {
	$number = absint( $number );

	return ( $number > 0 ? $number : $setting->default );
}