<?php
/**
 * Main template file
 *
 * @version 1.0.6
 */
get_header(); ?>
	<main role="main">
		<?php $exclude_portfolio_elements = get_theme_mod( 'exclude_portfolio_elements_from_blog' );
		if ( $exclude_portfolio_elements == 'checked' ) {
			$use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
			$portfolio_category     = get_theme_mod( 'portfolio_category' );
			$paged                  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			if ( $use_portfolio_category == 'checked' && $portfolio_category !== '' ) {
				$args = array(
					'category__not_in' => array( $portfolio_category ),
					'paged'            => $paged,
				);
			} else {
				$args = array(
					'paged'     => $paged,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-gallery', 'post-format-image' ),
							'operator' => 'NOT IN'
						),
					)
				);
			}
			$index_query = new WP_Query( $args );
			$temp_query  = $wp_query;
			$wp_query    = null;
			$wp_query    = $index_query;
			if ( $index_query->have_posts() ) {
				if ( is_home() && ! is_front_page() ) { ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
				<?php }
				while ( $index_query->have_posts() ) {
					$index_query->the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				}
			} else {
				get_template_part( 'template-parts/content', 'none' );
			}
			wp_reset_postdata();
			the_posts_pagination( array( 'type' => 'list' ) );
			$wp_query = null;
			$wp_query = $temp_query;
		} else {
			if ( have_posts() ) {
				if ( is_home() && ! is_front_page() ) { ?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
				<?php }
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/content', get_post_format() );
				}
			} else {
				get_template_part( 'template-parts/content', 'none' );
			}
			the_posts_pagination( array( 'type' => 'list' ) );
		} ?>
	</main>
<?php get_sidebar();
get_footer();