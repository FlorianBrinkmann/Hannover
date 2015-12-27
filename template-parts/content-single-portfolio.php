<?php
/**
 * Template part for single view of portfolio elements
 *
 * @version 1.0
 */
?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php hannover_the_title( 'h1', false ); ?>
		<span><?php hannover_the_date(); ?></span>
	</header>
	<div class="entry-content">
		<?php hannover_the_content();
		wp_link_pages(); ?>
	</div>
</article>