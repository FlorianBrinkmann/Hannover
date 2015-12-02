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
 * Adds the scripts and styles to the header
 */
function hannover_scripts_styles() {
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'hannover_scripts_styles' );

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

function hannover_comments( $comment, $args, $depth ) { ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment">
		<header class="comment-meta comment-author vcard clearfix">
			<?php echo get_avatar( $comment, 50 ); ?>
			<cite class="fn">
				<?php comment_author_link(); ?>
			</cite>

			<?php printf(
				'<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
				get_comment_link( $comment->comment_ID ),
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
			<?php edit_comment_link( __( 'Edit', 'hannover' ), '<p class="edit-link">', '</p>' ); ?>
		</div>

		<div class="reply">
			<?php comment_reply_link(
				array(
					'reply_text' => __( 'Reply', 'hannover' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				)
			); ?>
		</div>
	</article>
	<?php
}

function hannover_trackbacks( $comment ) { ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<?php _e( 'Trackback:', 'bornholm' ); ?>
	<?php comment_author_link(); ?>
	<?php edit_comment_link( __( '(Edit)', 'bornholm' ), '<span class="edit-link">', '</span>' );
}

function hannover_image_and_gallery_posts_array() {
	$tax_query_array = array(
		'relation' => 'OR',
		array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => 'post-format-gallery'
		),
		array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => 'post-format-image'
		)
	);

	return $tax_query_array;
}


/**
 * Fetch image post objects for all gallery images in a post.
 *
 * @param $post_id
 *
 * @return array
 */
function hannover_get_gallery_images( $post_id ) {

	$post = get_post( $post_id );

	// Den Beitrag gibt es nicht, oder er ist leer.
	if ( ! $post || empty ( $post->post_content ) ) {
		return array();
	}

	$galleries = get_post_galleries( $post, false );
	if ( empty ( $galleries ) ) {
		return array();
	}
	$ids = array();
	foreach ( $galleries as $gallery ) {
		if ( ! empty ( $gallery['ids'] ) ) {
			$ids = array_merge( $ids, explode( ',', $gallery['ids'] ) );
		}
	}
	$ids = array_unique( $ids );
	if ( empty ( $ids ) ) {
		$attachments = get_children( array(
			'post_parent'    => $post_id,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		) );
		if ( empty ( $attachments ) ) {
			return array();
		}
	}

	$images = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'orderby'        => 'post__in',
			'numberposts'    => 999,
			'include'        => $ids
		)
	);
	if ( ! $images && ! $attachments ) {
		return array();
	} elseif ( ! $images ) {
		$images = $attachments;
	}

	return $images;
}

function hannover_get_first_image_from_post_content() {
	global $post;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output    = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	$first_img = $matches[1][0];

	if ( empty( $first_img ) ) {
		$first_img = "/path/to/default.png";
	}

	return $first_img;
}

function hannover_image_from_gallery_or_image_post( $size, $post ) {
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( $size );
	} else {
		$post_format = get_post_format( $post );
		if ( $post_format == 'gallery' ) {
			$images  = hannover_get_gallery_images( $post->ID );
			$image   = array_shift( $images );
			$img_tag = wp_get_attachment_image( $image->ID, $size );
		} elseif ( $post_format == 'image' ) {
			$first_img_url = hannover_get_first_image_from_post_content();
			$first_img_id  = attachment_url_to_postid( $first_img_url );
			if ( $first_img_id == 0 ) {
				$pattern       = '/-\d+x\d+(\.\w{3,4}$)/i';
				$first_img_url = preg_replace( $pattern, '${1}', $first_img_url );
				$first_img_id  = attachment_url_to_postid( $first_img_url );
			}
			$img_tag = wp_get_attachment_image( $first_img_id, $size );
		}
		echo $img_tag;
	}
}

require get_template_directory() . '/inc/customizer.php';