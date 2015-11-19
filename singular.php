<?php get_header(); ?>
	<main role="main">
		<?php while ( have_posts() ) {
			the_post();
			if ( is_singular( 'page' ) ) {
				get_template_part( 'template-parts/content', 'page' );
			} else {
				get_template_part( 'template-parts/content', 'single' );
			}
		}
		if ( comments_open() || get_comments_number() ) {
			comments_template( '', true );
		} ?>
	</main>
<?php get_sidebar();
get_footer();