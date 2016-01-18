<?php
/**
 * Template file for displaying the sidebar
 *
 * @version 1.0.8
 */
if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
	<aside id="secondary" role="complementary">
		<h2 class="screen-reader-text">
			<?php /* translators: screen reader text for the sidebar */
			_e( 'Sidebar', 'hannover' ) ?></h2>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
<?php }