<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<header class="entry-header">
		<?php hannover_the_title( 'h1' ); ?>
	</header>
	<div class="entry-content">
		<?php hannover_the_content(); ?>
	</div>
</article>