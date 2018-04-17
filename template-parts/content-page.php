<?php
/**
 * Template part for pages.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

?>
<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php hannover_the_entry_header( 'large', 'h1', false ); ?>
	</header>
	<div class="entry-content">
		<?php hannover_the_content();
		wp_link_pages(); ?>
	</div>
</article>
