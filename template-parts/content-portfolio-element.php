<?php
/**
 * Default template for portfolio elements in the overview templates
 *
 * @version 1.0
 */
?>
<article <?php post_class( 'element' ); ?> id="post-<?php the_ID(); ?>">
	<a href="<?php the_permalink(); ?>">
		<h2 class="screen-reader-text"><?php the_title(); ?></h2>
		<?php hannover_image_from_gallery_or_image_post( 'thumbnail', $post ); ?>
	</a>
</article>