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

/**
 * Displays date and time of a post
 */
function hannover_the_date() {
	printf( _x(
		'%1$s @ %2$s', '1=date, 2=time', 'hannover' ),
		get_the_date(),
		get_the_time()
	);
}

/**
 * Displays the title of a post
 *
 * @param $heading
 */
function hannover_the_title( $heading ) {
	the_title( sprintf(
		'<%1$s class="entry-title"><a href="%2$s" rel="bookmark">',
		$heading, esc_url( get_permalink() )
	), sprintf( '</a></%s>', $heading ) );
}

/**
 * Displays the_content with a more accessible more tag
 */
function hannover_the_content() {
	the_content(
		sprintf(
			_x( 'Continue reading “%s”',
				'text for the more tag. s= title',
				'hannover'
			),
			esc_html( get_the_title() )
		)
	);
}

/**
 * Displays the author, categories, tags and number for comments and trackbacks
 */
function bornholm_entry_meta() { ?>
	<span class="author"><?php printf( _x(
			'Author %s',
			'name of the author in entry footer. s=author name',
			'hannover'
		), '<span>' . get_the_author() . '</span>' ) ?></span>
	<?php if ( get_the_category() ) { ?>
		<span class="categories"><?php printf( _nx(
				'Category %s',
				'Categories %s',
				count( get_the_category() ),
				'Label for category list in entry footer. s=categories',
				'hannover'
			), '<span>' . get_the_category_list( _x( ', ', 'term delimiter', 'hannover' ) ) . '</span>' ) ?></span>
	<?php }
	if ( get_the_tags() ) { ?>
		<span class="tags"><?php printf( _nx(
				'Tag %s',
				'Tags %s',
				count( get_the_tags() ),
				'Label for tag list in entry footer. s=tags',
				'hannover'
			), '<span>' . get_the_tag_list( '', _x( ', ', 'term delimiter', 'hannover' ) ) . '</span>' ) ?></span>
	<?php }
	if ( hannover_get_comment_count() > 0 ) { ?>
		<span class="comments"><?php printf( _nx(
				'%s Comment',
				'%s Comments',
				hannover_get_comment_count(),
				'Label for comment number in entry footer. s=comment number',
				'hannover'
			), '<span>' . number_format_i18n( hannover_get_comment_count() ) . '</span>' ) ?></span>
	<?php }
	if ( hannover_get_trackback_count() > 0 ) { ?>
		<span class="comments"><?php printf( _nx(
				'%s Trackback',
				'%s Trackbacks',
				hannover_get_trackback_count(),
				'Label for trackback number in entry footer. s=trackback number',
				'hannover'
			), '<span>' . number_format_i18n( hannover_get_trackback_count() ) . '</span>' ) ?></span>
	<?php };
}

/**
 * Returns the number of comments for a post
 *
 * @return int
 */
function hannover_get_comment_count() {
	global $post;
	$the_post_id = $post->ID;
	global $wpdb;
	$co_number = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type = %s
AND comment_post_ID = %d AND comment_approved = %d", ' ', $the_post_id, 1 ) );

	return $co_number;
}

/**
 * Returns the Number of trackbacks for a post
 *
 * @return int
 */
function hannover_get_trackback_count() {
	global $post;
	$the_post_id = $post->ID;
	global $wpdb;
	$tb_number = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_type != %s
AND comment_post_ID = %d AND comment_approved = %d", ' ', $the_post_id, 1 ) );

	return $tb_number;
}