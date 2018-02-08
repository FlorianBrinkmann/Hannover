<?php
/**
 * Template Name: Page without Sidebar
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

// Include header.php.
get_header(); ?>
	<main>
		<?php // Check if we have posts.
		if ( have_posts() ) {
			// Loop the posts.
			while ( have_posts() ) {
				// Setup post.
				the_post();

				// Include content-page.php.
				get_template_part( 'template-parts/content', 'page' );

				// Check if comments are open or we already have comments
				// and include comments.php.
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				} // End if().
			} // End while().
		} else {
			// We do not have posts, so we include the content-none.php.
			get_template_part( 'template-parts/content', 'none' );
		} // End if(). ?>
	</main>
<?php // Include footer.php.
get_footer();
