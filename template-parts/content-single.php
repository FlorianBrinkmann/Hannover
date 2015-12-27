<?php
/**
 * Template part for single view
 *
 * @version 1.0
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php the_post_thumbnail( 'large' );
		hannover_the_title( 'h1', false ); ?>
		<span><?php hannover_the_date(); ?></span>
	</header>
	<div class="entry-content">
		<?php hannover_the_content();
		wp_link_pages(); ?>
	</div>
	<footer>
		<p><?php hannover_entry_meta() ?></p>
	</footer>
</article>