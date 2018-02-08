<?php
/**
 * Functions file
 */

// Include file with add_action() calls.
require_once locate_template( '/inc/actions.php' );

// Include file with add_filter() calls.
require_once locate_template( '/inc/filters.php' );

// Include customizer register file.
require_once locate_template( '/inc/customizer/register.php' );

// Include file with functions called from template files.
require_once locate_template( 'inc/template-tags.php' );

// Include file with template functions.
require_once locate_template( 'inc/template-functions.php' );
