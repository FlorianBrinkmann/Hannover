<?php
/**
 * Customizer callback functions.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

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
