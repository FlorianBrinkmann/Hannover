<?php
/**
 * Functions that filter something.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Removes the archive and portfolio category from the category widget if
 * selected in the customizer
 *
 * @param $cat_args
 *
 * @return array
 */
function hannover_filter_category_widget( $cat_args ) {
	$use_portfolio_category            = get_theme_mod( 'portfolio_from_category' );
	$archive_type                      = get_theme_mod( 'portfolio_archive' );
	$exclude_portfolio_cat_from_widget = get_theme_mod( 'portfolio_remove_category_from_cat_widget' );
	$exclude_archive_cat_from_widget   = get_theme_mod( 'portfolio_archive_remove_category_from_cat_widget' );
	$exclude                           = '';
	if ( $exclude_portfolio_cat_from_widget == 'checked' && $use_portfolio_category == 'checked' ) {
		$portfolio_category = get_theme_mod( 'portfolio_category' );
		$exclude            = $portfolio_category;
	}
	if ( $exclude_archive_cat_from_widget == 'checked' && $archive_type == 'archive_category' ) {
		$archive_category = get_theme_mod( 'portfolio_archive_category' );
		if ( $exclude != '' ) {
			$exclude .= ', ' . $archive_category;
		} else {
			$exclude = $archive_category;
		}
	}
	$cat_args['exclude'] = $exclude;

	return $cat_args;
}

/**
 * Removes the page jump after clicking on a read more link.
 *
 * @param $link
 *
 * @return mixed
 */
function hannover_remove_more_link_scroll( $link ) {
	$link = preg_replace( '/#more-[0-9]+/', '', $link );

	return $link;
}
