<?php
/**
 * Template file for displaying the sidebar
 *
 * @version 1.0
 */
if ( is_active_sidebar( 'sidebar-1' ) ) { ?>
	<aside id="secondary" role="complementary">
		<h2 class="screen-reader-text">
			<?php _ex( 'Sidebar', 'screen reader text for the sidebar', 'hannover' ) ?></h2>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
<?php }