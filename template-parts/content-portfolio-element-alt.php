<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<a href="<?php the_permalink(); ?>">
		<?php hannover_image_from_gallery_or_image_post( 'thumbnail', $post ); ?>
	</a>
</article>