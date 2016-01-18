<?php
/**
 * Template Name: Front page with slider
 *
 * @version 1.0.8
 */
get_header(); ?>
	<main role="main">
		<?php if ( have_posts() ) {
			while ( have_posts() ) {
				the_post(); ?>
				<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
					<div class="entry-content">
						<?php hannover_the_content();
						wp_link_pages(); ?>
					</div>
				</article>
				<?php if ( comments_open() || get_comments_number() ) {
					comments_template( '', true );
				}
			}
		} else {
			get_template_part( 'template-parts/content', 'none' );
		} ?>
	</main>
<?php get_footer();