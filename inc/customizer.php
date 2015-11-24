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
}

add_action( 'customize_register', 'hannover_customize_register' );

function hannover_use_portfolio_category_callback( $control ) {
	if ( $control->manager->get_setting( 'portfolio_from_category' )->value() == 'checked' ) {
		return true;
	} else {
		return false;
	}
}

function hannover_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function hannover_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}