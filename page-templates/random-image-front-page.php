<?php
/**
 * Template Name: Front page with random image
 */
get_header(); ?>
	<main role="main">
		<?php $images = hannover_get_gallery_images( $post->ID );
		shuffle( $images );
		$first_image = array_shift( $images ); ?>
		<figure>
			<?php if ( ! empty( $first_image ) ) {
				echo wp_get_attachment_image( $first_image->ID, 'large' );
			}
			if ( ! empty( $first_image->post_title ) ) { ?>
				<figcaption>
					<?php echo $first_image->post_title; ?>
				</figcaption>
			<?php } ?>
		</figure>
	</main>
<?php get_footer();