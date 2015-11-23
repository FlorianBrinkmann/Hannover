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
			'section'  => 'portfolio',
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
			'label'    => __( 'Portfolio category', 'hannover' ),
			'type'     => 'select',
			'section'  => 'portfolio',
			'settings' => 'portfolio_category',
			'choices'  => $category_array
		)
	);


	$wp_customize->add_section(
		'portfolio', array(
			'title' => __( 'Portfolio', 'hannover' ),
		)
	);
}

add_action( 'customize_register', 'hannover_customize_register' );

function hannover_sanitize_select( $input, $setting ) {
	$input   = sanitize_key( $input );
	$choices = $setting->manager->get_control( $setting->id )->choices;

	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function hannover_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}