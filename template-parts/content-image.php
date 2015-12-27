<?php
/**
 * Template part for image posts
 *
 * @version 1.0
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php hannover_image_from_gallery_or_image_post( 'large', $post );
		hannover_the_title( 'h2', true ); ?>
	</header>
	<div class="entry-content">
		<?php if ( has_excerpt() ) {
			the_excerpt();
		} ?>
	</div>
	<footer>
		<p><a href="<?php the_permalink(); ?>"
		      class="entry-date"><?php hannover_the_date(); ?></a>
			<?php hannover_entry_meta() ?></p>
	</footer>
</article>