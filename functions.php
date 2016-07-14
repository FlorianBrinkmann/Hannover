<?php
/**
 * Functions file
 */

/**
 * Load translation from translate.WordPress.org if available
 */
function hannover_load_translation() {
	if ( ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX' ) || ! hannover_is_login_page() || ! hannover_is_wp_comments_post() ) {
		load_theme_textdomain( 'hannover' );
	}
}

add_action( 'after_setup_theme', 'hannover_load_translation' );

/**
 * Check if we are on the login page
 *
 * @return bool
 */
function hannover_is_login_page() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/**
 * Check if we are on the wp-comments-post.php
 *
 * @return bool
 */
function hannover_is_wp_comments_post() {
	return in_array( $GLOBALS['pagenow'], array( 'wp-comments-post.php' ) );
}

/**
 * Set content width to 845 px
 */
if ( ! isset( $content_width ) ) {
	$content_width = 845;
}

/**
 * Set width of large image size to 845 px
 */
update_option( 'large_size_w', 845 );

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
			/* translators: Name of menu position in the header */
			'primary' => __( 'Primary Menu', 'hannover' ),
			/* translators: Name of menu position in the footer */
			'footer'  => __( 'Footer Menu', 'hannover' ),
			/* translators: Name of menu position for social media icons */
			'social'  => __( 'Social Menu', 'hannover' ),
		)
	);
}

add_action( 'init', 'hannover_register_menus' );

/**
 * Register sidebar
 */
function hannover_register_sidebars() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'hannover' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets in this area will be shown on all normal posts and pages.', 'hannover' ),
		'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
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

	wp_enqueue_script( 'hannover-lightbox', get_template_directory_uri() . '/js/lightbox.js', array( 'jquery' ), false, true );

	wp_enqueue_script( 'hannover-menu', get_template_directory_uri() . '/js/menu.js', array( 'jquery' ), false, true );

	if ( has_nav_menu( 'social' ) ) {
		wp_enqueue_script( 'hannover-svg4everybody', get_template_directory_uri() . '/js/svg4everybody.js', array( 'jquery' ), false, true );
	}

	wp_localize_script( 'hannover-menu', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'hannover' ),
		'collapse' => __( 'collapse child menu', 'hannover' ),
	) );

	/**
	 * Adds slider script to footer if front page template with slidre is displayed or user
	 * wants to show all galleries as slider.
	 * Localizes script with strings for next and previous button and slider options from the customizer
	 */
	global $post;
	if ( $post ) {
		$galleries_as_slider = get_theme_mod( 'galleries_as_slider' );
		$page_template       = get_page_template_slug( $post->ID );
		if ( $page_template == 'page-templates/slider-front-page.php' || $galleries_as_slider == 'checked' ) {
			wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl-carousel.css' );
			wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl-carousel.js', array( 'jquery' ), false, true );
			$slider_autoplay      = get_theme_mod( 'slider_autoplay' );
			$slider_autoplay_time = get_theme_mod( 'slider_autoplay_time', 3000 );
			$params               = array(
				'autoplay'        => $slider_autoplay,
				'autoplayTimeout' => $slider_autoplay_time,
				'prev'            => __( 'Previous Slide', 'hannover' ),
				'next'            => __( 'Next Slide', 'hannover' ),

			);
			wp_localize_script( 'owl-carousel', 'OwlParams', $params );
		}
	}

	wp_enqueue_style( 'hannover-style', get_template_directory_uri() . '/css/hannover.css', array(), null );

	wp_enqueue_style( 'hannover-fonts', '//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic', array(), null );
}

add_action( 'wp_enqueue_scripts', 'hannover_scripts_styles' );

/**
 * Displays date and time of a post
 *
 * @return void
 */
function hannover_the_date() {
	/* translators: 1=date, 2=time */
	printf( __( '%1$s @ %2$s', 'hannover' ),
		get_the_date(),
		get_the_time()
	);
}

/**
 * Displays the title of a post
 *
 * @param $heading , $link
 *
 * @return void
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
 *
 * @return void
 */
function hannover_the_content() {
	/* translators: text for the more tag. s= title */
	the_content(
		sprintf(
			__( 'Continue reading "%s"', 'hannover' ),
			esc_html( get_the_title() )
		)
	);
}

/**
 * Displays the author, categories, tags and number for comments and trackbacks
 *
 * @return void
 */
function hannover_entry_meta() { ?>
	<span class="author"><?php /* translators: name of the author in entry footer. s=author name */
		printf( __( 'Author %s', 'hannover' ),
			'<span>' . get_the_author() . '</span>' ) ?></span>
	<?php if ( get_the_category() ) { ?>
		<span class="categories"><?php /* translators: Label for category list in entry footer. s=categories */
			printf( _n(
				'Category %s',
				'Categories %s',
				count( get_the_category() ),
				'hannover'
			), /* translators: term delimiter */
				'<span>' . get_the_category_list( __( ', ', 'hannover' ) ) . '</span>' ) ?></span>
	<?php }
	if ( get_the_tags() ) { ?>
		<span class="tags"><?php /* translators: Label for tag list in entry footer. s=tags */
			printf( _n(
				'Tag %s',
				'Tags %s',
				count( get_the_tags() ),
				'hannover'
			), /* translators: term delimiter */
				'<span>' . get_the_tag_list( '', __( ', ', 'hannover' ) ) . '</span>' ) ?></span>
	<?php }
	$comments_by_type = hannover_get_comments_by_type();
	if ( $comments_by_type['comment'] ) {
		$comment_number = count( $comments_by_type['comment'] ); ?>
		<span class="comments"><?php /* translators: Label for comment number in entry footer. s=comment number */
			printf( _n(
				'%s Comment',
				'%s Comments',
				$comment_number,
				'hannover'
			), '<span>' . number_format_i18n( $comment_number ) . '</span>' ) ?></span>
	<?php }
	if ( $comments_by_type['pings'] ) {
		$trackback_number = count( $comments_by_type['pings'] ); ?>
		<span class="trackbacks"><?php /* translators: Label for trackback number in entry footer. s=trackback number */
			printf( _n(
				'%s Trackback',
				'%s Trackbacks',
				$trackback_number,
				'hannover'
			), '<span>' . number_format_i18n( $trackback_number ) . '</span>' ) ?></span>
	<?php };
}

/**
 * Removes the archive and portfolio category from the category widget if selected in the customizer
 *
 * @param $cat_args
 *
 * @return array
 */
function hannover_filter_category_widget( $cat_args ) {
	$use_portfolio_category            = get_theme_mod( 'portfolio_from_category' );
	$archive_type                      = get_theme_mod( 'portfolio_archive' );
	$exclude_portfolio_cat_from_widget = get_theme_mod( 'portfolio_remove_category_from_cat_widget' );
	$exclude_archive_cat_from_widget   = get_theme_mod( 'portfolio_archive_remove_category_from_cat_widget' );
	$exclude                           = '';
	if ( $exclude_portfolio_cat_from_widget == 'checked' && $use_portfolio_category == 'checked' ) {
		$portfolio_category = get_theme_mod( 'portfolio_category' );
		$exclude            = $portfolio_category;
	}
	if ( $exclude_archive_cat_from_widget == 'checked' && $archive_type == 'archive_category' ) {
		$archive_category = get_theme_mod( 'portfolio_archive_category' );
		if ( $exclude != '' ) {
			$exclude .= ', ' . $archive_category;
		} else {
			$exclude = $archive_category;
		}
	}
	$cat_args['exclude'] = $exclude;

	return $cat_args;
}

add_filter( 'widget_categories_args', 'hannover_filter_category_widget' );

/**
 * Function to exclude the portfolio elements from the main query if chosen in the customizer
 *
 * @param $query
 */
function hannover_exlude_portfolio_elements_from_blog( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$exclude_portfolio_elements = get_theme_mod( 'exclude_portfolio_elements_from_blog' );
		if ( $exclude_portfolio_elements == 'checked' ) {
			$use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
			$portfolio_category     = get_theme_mod( 'portfolio_category' );
			if ( $use_portfolio_category == 'checked' && $portfolio_category != '' ) {
				$query->set( 'cat', "-$portfolio_category" );
			} else {
				$tax_query = array(
					array(
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => array( 'post-format-gallery', 'post-format-image' ),
						'operator' => 'NOT IN'
					)
				);
				$query->set( 'tax_query', $tax_query );
			}
		}
	}
}

add_action( 'pre_get_posts', 'hannover_exlude_portfolio_elements_from_blog' );

/**
 * Gets the comments seperated by type
 *
 * @return array
 */
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

/**
 * Callback function for displaying the comment list
 *
 * @param $comment , $args, $depth
 *
 * @return void
 */
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
				/* translators: 1=date 2=time */
				sprintf( __( '%1$s @ %2$s', 'hannover' ), get_comment_date(), get_comment_time() )
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

/**
 * Callback function for displaying the trackback list
 *
 * @param $comment
 *
 * @return void
 */
function hannover_trackbacks( $comment ) { ?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	<?php _e( 'Trackback:', 'hannover' ); ?>
	<?php comment_author_link(); ?>
	<?php edit_comment_link( __( '(Edit)', 'hannover' ), '<span class="edit-link">', '</span>' );
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

/**
 * Get first image from post content with regular expression
 *
 * @return string
 */
function hannover_get_first_image_from_post_content() {
	global $post;
	$first_img = '';
	$output    = preg_match( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
	if ( ! empty( $matches[1] ) ) {
		$first_img = $matches[1];
	}

	return $first_img;
}

/**
 * Returns the first image from the post content for a image post
 * and the first image from the gallery for a gallery post.
 *
 * @param $size , $post
 *
 * @return void
 */
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
			$pattern       = '/-\d+x\d+(\.\w{3,4}$)/i';
			$first_img_url = preg_replace( $pattern, '${1}', $first_img_url );
			$first_img_id  = attachment_url_to_postid( $first_img_url );
			if ( $first_img_id == 0 ) {
				$attachments = get_children( array(
					'post_parent'    => $post->ID,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'ASC',
					'orderby'        => 'menu_order',
				) );
				$first_img   = array_shift( $attachments );
				$img_tag     = wp_get_attachment_image( $first_img->ID, $size );
			} else {
				$img_tag = wp_get_attachment_image( $first_img_id, $size );
			}
		}
		echo $img_tag;
	}
}

/**
 * Returns the query args for the page templates
 *
 * @param $post
 * @param string $template name of page template. Possible values: portfolio, portfolio-archive, portfolio-category-page
 *
 * @return array
 */
function hannover_page_template_query_args( $post, $template = 'portfolio' ) {
	$archive_type           = get_theme_mod( 'portfolio_archive' );
	$archive_category       = get_theme_mod( 'portfolio_archive_category' );
	$use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
	$portfolio_category     = get_theme_mod( 'portfolio_category' );
	if ( $template == 'portfolio' ) {
		$elements_per_page = get_theme_mod( 'portfolio_elements_per_page', 0 );
	} elseif ( $template == 'portfolio-archive' ) {
		$elements_per_page = get_theme_mod( 'portfolio_archive_elements_per_page', 0 );
	} else {
		$elements_per_page                = get_theme_mod( "portfolio_category_page_elements_per_page_$post->ID", 0 );
		$portfolio_category_page_category = get_theme_mod( "portfolio_category_page_$post->ID" );
	}
	$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$tax_query = array( 'relation' => 'AND' );
	if ( $elements_per_page == 0 ) {
		$elements_per_page = - 1;
	}

	$tax_query[] = array(
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => array(
			'post-format-gallery',
			'post-format-image'
		),
	);
	if ( $template != 'portfolio-archive' && $archive_type == 'archive_category' ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( $archive_category ),
			'operator' => 'NOT IN'
		);
	}
	if ( $template == 'portfolio-archive' && $archive_category !== '' && $archive_type == 'archive_category' ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( $archive_category ),
		);
	}
	if ( $template == 'portfolio-category-page' ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( $portfolio_category_page_category ),
		);
	}
	if ( $use_portfolio_category == 'checked' && $portfolio_category !== '' ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => array( $portfolio_category ),
		);
	}
	$args = array(
		'posts_per_page' => $elements_per_page,
		'paged'          => $paged,
		'tax_query'      => $tax_query
	);

	return $args;
}

/**
 * Removes the page jump after clicking on a read more link
 *
 * @param $link
 *
 * @return mixed
 */
function hannover_remove_more_link_scroll( $link ) {
	$link = preg_replace( '/#more-[0-9]+/', '', $link );

	return $link;
}

add_filter( 'the_content_more_link', 'hannover_remove_more_link_scroll' );

require_once get_template_directory() . '/inc/customizer.php';

require_once get_template_directory() . '/inc/class-hannover-social-menu-walker.php';