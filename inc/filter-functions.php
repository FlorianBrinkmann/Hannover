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
 * @param array $cat_args Array with category args.
 *
 * @return array
 */
function hannover_filter_category_widget( $cat_args ) {
	// Get the theme mods.
	$theme_mods                        = get_theme_mods();
	$use_portfolio                     = $theme_mods['portfolio_page']['active'];
	$use_portfolio_category            = $theme_mods['portfolio_page']['from_category'];
	$use_archive                       = $theme_mods['portfolio_archive']['active'];
	$exclude_portfolio_cat_from_widget = $theme_mods['portfolio_page']['remove_category_from_cat_list'];
	$exclude_archive_cat_from_widget   = $theme_mods['portfolio_archive']['remove_category_from_cat_widget'];
	$exclude                           = [];

	// Check if the portfolio feature is active.
	if ( 1 === absint( $use_portfolio ) ) {
		// Check if we should exclude the portfolio category.
		if ( true === $use_portfolio_category && true === $exclude_portfolio_cat_from_widget ) {
			// Get category and add to exclude array.
			$portfolio_category = $theme_mods['portfolio_page']['category'];
			if ( '' !== $portfolio_category ) {
				array_push( $exclude, $portfolio_category );
			}
		}

		// Check if we should exclude the archive category.
		if ( true === $use_archive && true === $exclude_archive_cat_from_widget ) {
			$archive_category = $theme_mods['portfolio_archive']['category'];
			if ( '' !== $archive_category ) {
				array_push( $exclude, $archive_category );
			}
		}

		// Check if the array is not empty.
		if ( ! empty( $exclude ) ) {
			$cat_args['exclude'] = $exclude;
		}
	}

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
