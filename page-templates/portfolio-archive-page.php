<?php
/**
 * Template Name: Portfolio archive page
 */
get_header(); ?>
	<main role="main">
		<header>
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
		<?php $use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
		$portfolio_category           = get_theme_mod( 'portfolio_category' );
		$archive_type                 = get_theme_mod( 'portfolio_archive' );
		$archive_category             = get_theme_mod( 'portfolio_archive_category' );
		$elements_per_page            = get_theme_mod( 'portfolio_archive_elements_per_page', 0 );
		$paged                        = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$args                         = array();
		if ( $elements_per_page == 0 ) {
			$elements_per_page = - 1;
		}
		if ( $archive_category !== '' && $archive_type == 'archive_category' ) {
			if ( $use_portfolio_category == 'checked' && $portfolio_category !== '' ) {
				$args = array(
					'posts_per_page' => $elements_per_page,
					'paged'          => $paged,
					'tax_query'      => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => array( $portfolio_category ),
						),
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => array( $archive_category ),
						),
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array(
								'post-format-gallery',
								'post-format-image'
							),
						),
					)
				);
			} else {
				$args = array(
					'posts_per_page' => $elements_per_page,
					'tax_query'      => array(
						'relation' => 'AND',
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => array( $archive_category ),
						),
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array(
								'post-format-gallery',
								'post-format-image'
							),
						),
					),
					'paged'          => $paged
				);
			}
		}
		$archive_query = new WP_Query( $args );
		$temp_query    = $wp_query;
		$wp_query      = null;
		$wp_query      = $archive_query;
		if ( $archive_query->have_posts() ) {
			while ( $archive_query->have_posts() ) {
				$archive_query->the_post();
				get_template_part( 'template-parts/content', 'portfolio-element' );
			}
		}
		wp_reset_postdata();
		the_posts_pagination( array( 'type' => 'list' ) );
		$wp_query = null;
		$wp_query = $temp_query; ?>
	</main>
<?php get_footer();