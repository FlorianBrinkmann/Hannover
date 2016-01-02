<?php
/**
 * Template Name: Portfolio archive page
 *
 * @version 1.0.6
 */
get_header(); ?>
	<main role="main">
		<header>
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
		<?php $args         = hannover_page_template_query_args( $post, 'portfolio-archive' );
		$archive_query      = new WP_Query( $args );
		$temp_query         = $wp_query;
		$wp_query           = null;
		$wp_query           = $archive_query;
		$archive_alt_layout = get_theme_mod( 'portfolio_archive_alt_layout' );
		if ( $archive_query->have_posts() ) {
			while ( $archive_query->have_posts() ) {
				$archive_query->the_post();
				if ( $archive_alt_layout == 'checked' ) {
					get_template_part( 'template-parts/content', 'portfolio-element-alt' );
				} else {
					get_template_part( 'template-parts/content', 'portfolio-element' );
				}
			}
		} else {
			get_template_part( 'template-parts/content', 'none' );
		}
		wp_reset_postdata();
		the_posts_pagination( array( 'type' => 'list' ) );
		$wp_query = null;
		$wp_query = $temp_query; ?>
	</main>
<?php get_footer();