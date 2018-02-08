<?php
/**
 * Template Name: Front page with random image
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

// Include header.php.
get_header(); ?>
	<main>
		<article>
			<?php // Get random image from page gallery.
			$images = hannover_get_gallery_images( $post->ID );
			shuffle( $images );
			$first_image = array_shift( $images ); ?>
			<figure>
				<?php if ( ! empty( $first_image ) ) {
					echo wp_get_attachment_image( $first_image->ID, 'large' );
				} // End if().
				if ( ! empty( $first_image->post_excerpt ) ) { ?>
					<figcaption>
						<?php echo $first_image->post_excerpt; ?>
					</figcaption>
				<?php } ?>
			</figure>
		</article>
	</main>
<?php // Include footer.php.
get_footer();
