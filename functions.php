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

function hannover_register_sidebars() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'textdomain' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets in this area will be shown on all normal posts and pages.', 'textdomain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

add_action( 'widgets_init', 'hannover_register_sidebars' );

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
 * @param $heading , $link
 */
function hannover_the_title( $heading, $link ) {
	if ( $link ) {
		the_title( sprintf(
			'<%1$s class="entry-title"><a href="%2$s" rel="bookmark">',
			$heading, esc_url( get_permalink() )
		), sprintf( '</a></%s>', $heading ) );
	} else {
		the_title( sprintf(
			'<%1$s class="entry-title">',
			$heading, esc_url( get_permalink() )
		), sprintf( '</%s>', $heading ) );
	}
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
function hannover_entry_meta() { ?>
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
	$comments_by_type = hannover_get_comments_by_type();
	if ( $comments_by_type['comment'] ) {
		$comment_number = count( $comments_by_type['comment'] ); ?>
		<span class="comments"><?php printf( _nx(
				'%s Comment',
				'%s Comments',
				$comment_number,
				'Label for comment number in entry footer. s=comment number',
				'hannover'
			), '<span>' . number_format_i18n( $comment_number ) . '</span>' ) ?></span>
	<?php }
	if ( $comments_by_type['pings'] ) {
		$trackback_number = count( $comments_by_type['pings'] ); ?>
		<span class="comments"><?php printf( _nx(
				'%s Trackback',
				'%s Trackbacks',
				$trackback_number,
				'Label for trackback number in entry footer. s=trackback number',
				'hannover'
			), '<span>' . number_format_i18n( $trackback_number ) . '</span>' ) ?></span>
	<?php };
}

function hannover_get_comments_by_type() {
	global $wp_query, $post;
	$comment_args               = array(
		'order'   => 'ASC',
		'orderby' => 'comment_date_gmt',
		'status'  => 'approve',
		'post_id' => $post->ID,
	);
	$comments                   = get_comments( $comment_args );
	$wp_query->comments_by_type = separate_comments( $comments );
	$comments_by_type           = &$wp_query->comments_by_type;

	return $comments_by_type;
}

function hannover_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment">
		<header class="comment-meta comment-author vcard clearfix">
			<?php
			echo get_avatar( $comment, 50 ); ?>
			<cite class="fn">
				<?php esc_url( comment_author_link() ); ?>
			</cite>

			<?php printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
				esc_url( get_comment_link( $comment->comment_ID ) ),
				get_comment_time( 'c' ),
				sprintf( _x( '%1$s @ %2$s', '1=date 2=time', 'hannover' ), get_comment_date(), get_comment_time() )
			); ?>
		</header>

		<?php if ( '0' == $comment->comment_approved ) { ?>
			<p class="comment-awaiting-moderation">
				<?php _e( 'Your comment is awaiting moderation.', 'hannover' ); ?>
			</p>
		<?php } ?>

		<div class="comment-content comment">
			<?php comment_text(); ?>
			<?php esc_url( edit_comment_link( __( 'Edit', 'hannover' ), '<p class="edit-link">', '</p>' ) ); ?>
		</div>

		<div class="reply">
			<?php esc_url( comment_reply_link(
				array(
					'reply_text' => __( 'Reply', 'hannover' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				)
			) ); ?>
		</div>
	</article>
	<?php
}

function hannover_trackbacks( $comment ) {
	$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<?php _e( 'Trackback:', 'bornholm' ); ?>
	<?php esc_url( comment_author_link() ); ?>
	<?php esc_url( edit_comment_link( __( '(Edit)', 'bornholm' ), '<span class="edit-link">', '</span>' ) );
}

require get_template_directory() . '/inc/customizer.php';