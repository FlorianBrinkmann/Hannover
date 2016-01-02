<?php
/**
 * Template Name: Front page with slider
 *
 * @version 1.0.6
 */
get_header(); ?>
	<main role="main">
		<?php if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', 'page' );
				if ( comments_open() || get_comments_number() ) {
					comments_template( '', true );
				}
			}
		} else {
			get_template_part( 'template-parts/content', 'none' );
		} ?>
	</main>
<?php get_footer();