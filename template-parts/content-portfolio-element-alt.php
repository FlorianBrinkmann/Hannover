<?php
/**
 * Alternative template for portfolio elements in the overview templates
 *
 * @version 1.0
 */
?>
<article <?php post_class( 'element-alt' ); ?> id="post-<?php the_ID(); ?>">
	<a href="<?php the_permalink(); ?>">
		<?php hannover_image_from_gallery_or_image_post( 'medium_large', $post ); ?>
	</a>
	<div>
		<a href="<?php the_permalink(); ?>">
			<h2><?php the_title(); ?></h2>
		</a>
		<?php if ( has_excerpt() ) {
			the_excerpt();
		} ?>
	</div>
</article>