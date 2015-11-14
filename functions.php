<?php
function hannover_register_menus() {
	register_nav_menus(
		array(
			'primary' => _x( 'Primary Menu', 'Name of menu position in the header', 'hannover' )
		)
	);
}

add_action( 'init', 'hannover_register_menus' );