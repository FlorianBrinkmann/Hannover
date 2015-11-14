<?php
/**
 * Adds theme support for feed links, custom head, html5, post formats, post thumbnails and the title tag
 */
function hannover_add_theme_support() {
	add_theme_support( 'custom-header' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-formats', array(
		'aside',
		'link',
		'gallery',
		'status',
		'quote',
		'image',
		'video',
		'audio',
		'chat'
	) );
	add_theme_support( 'html5', array(
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
	) );
	add_theme_support( 'post-thumbnails' );
}

add_action( 'after_setup_theme', 'hannover_add_theme_support' );

/**
 * Register Menus
 */
function hannover_register_menus() {
	register_nav_menus(
		array(
			'primary' => _x( 'Primary Menu', 'Name of menu position in the header', 'hannover' )
		)
	);
}

add_action( 'init', 'hannover_register_menus' );