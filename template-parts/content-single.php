<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php the_post_thumbnail( 'large' );
		hannover_the_title( 'h1', false );
		hannover_the_date(); ?>
	</header>
	<div class="entry-content">
		<?php hannover_the_content(); ?>
	</div>
	<footer>
		<p><?php hannover_entry_meta() ?></p>
	</footer>
</article>