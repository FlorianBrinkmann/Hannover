<?php
/**
 * Template Name: Portfolio page
 */
get_header(); ?>
	<main role="main">
		<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
		<?php $use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
		$portfolio_category           = get_theme_mod( 'portfolio_category' );
		$elements_per_page            = get_theme_mod( 'portfolio_elements_per_page', 0 );
		$tax_query_array              = hannover_image_and_gallery_posts_array();
		if ( $elements_per_page == 0 ) {
			$elements_per_page = - 1;
		}
		if ( $use_portfolio_category == 'checked' && $portfolio_category !== '' ) {
			$args = array(
				'posts_per_page' => $elements_per_page,
				'tax_query'      => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => array( $portfolio_category ),
					),
					$tax_query_array
				)
			);
		} else {
			$args = array(
				'posts_per_page' => $elements_per_page,
				'tax_query'      => $tax_query_array
			);
		}
		$paged           = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$portfolio_query = new WP_Query( $args );
		$temp_query      = $wp_query;
		$wp_query        = null;
		$wp_query        = $portfolio_query;
		if ( $portfolio_query->have_posts() ) {
			while ( $portfolio_query->have_posts() ) {
				$portfolio_query->the_post();
				get_template_part( 'template-parts/content', 'portfolio-element' );
			}
		}
		wp_reset_postdata();
		the_posts_pagination( array( 'type' => 'list' ) );
		$wp_query = null;
		$wp_query = $temp_query; ?>
	</main>
<?php get_footer();