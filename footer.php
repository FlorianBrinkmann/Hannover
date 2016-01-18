<?php
/**
 * Template file for the footer
 *
 * @version 1.0.8
 */
?>
</div>
<footer id="footer">
	<?php if ( has_nav_menu( 'social' ) ) {
		wp_nav_menu(
			array(
				'theme_location' => 'social',
				'menu_class'     => 'social-menu',
				'container'      => '',
				'walker'         => new Hannover_Social_Menu_Walker(),
				'depth'          => 1
			)
		);
	}
	if ( has_nav_menu( 'footer' ) ) { ?>
		<nav>
			<h2 class="screen-reader-text">
				<?php /* translators: hidden screen reader headline for the footer navigation */
				_e( 'Footer navigation', 'hannover' ); ?>
			</h2>
			<?php wp_nav_menu(
				array(
					'theme_location' => 'footer',
					'menu_class'     => 'footer-menu',
					'container'      => ''
				)
			); ?>
		</nav>
	<?php } ?>
	<p class="theme-author"><?php _e( 'Theme: Hannover by <a rel="nofollow" href="https://florianbrinkmann.de">Florian Brinkmann</a>', 'hannover' ) ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>