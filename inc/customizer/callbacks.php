<?php
/**
 * Customizer callback functions.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes
 * `$checked` as a boolean value, either TRUE or FALSE.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 */
function hannover_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Drop-down Pages sanitization callback example.
 *
 * - Sanitization: dropdown-pages
 * - Control: dropdown-pages
 *
 * Sanitization callback for 'dropdown-pages' type controls. This callback
 * sanitizes `$page_id` as an absolute integer, and then validates that $input
 * is the ID of a published page.
 *
 * @see  absint() https://developer.wordpress.org/reference/functions/absint/
 * @see  get_post_status()
 *       https://developer.wordpress.org/reference/functions/get_post_status/
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @param int                  $page_id Page ID.
 * @param WP_Customize_Setting $setting Setting instance.
 *
 * @return int|string Page ID if the page is published; otherwise, the setting
 *                    default.
 */
function hannover_sanitize_dropdown_pages( $page_id, $setting ) {
	// Ensure $input is an absolute integer.
	$page_id = absint( $page_id );

	// If $page_id is an ID of a published page, return it; otherwise, return the default.
	return ( 'publish' === get_post_status( $page_id ) ? $page_id : $setting->default );
}


/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback
 * sanitizes `$input` as a slug, and then validates `$input` against the
 * choices defined for the control.
 *
 * @see  sanitize_key()
 *       https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see  $wp_customize->get_control()
 *       https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php#L21
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 *
 * @return string Sanitized slug if it is a valid choice; otherwise, the
 *                setting default.
 */
function hannover_sanitize_select( $input, $setting ) {

	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
