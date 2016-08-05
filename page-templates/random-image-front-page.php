<?php
/**
 * Template Name: Front page with random image
 *
 * @version 1.0
 */
get_header(); ?>
	<main role="main">
		<article>
			<?php $images = hannover_get_gallery_images( $post->ID );
			shuffle( $images );
			$first_image = array_shift( $images ); ?>
			<figure>
				<?php if ( ! empty( $first_image ) ) {
					echo wp_get_attachment_image( $first_image->ID, 'large' );
				}
				if ( ! empty( $first_image->post_excerpt ) ) { ?>
					<figcaption>
						<?php echo $first_image->post_excerpt; ?>
					</figcaption>
				<?php } ?>
			</figure>
		</article>
	</main>
<?php get_footer();