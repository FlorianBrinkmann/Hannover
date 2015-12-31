<?php
/**
 * Template Name: Page without Sidebar
 *
 * @version 1.0.4
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
		} ?>
	</main>
<?php get_footer();