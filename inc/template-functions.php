<?php
/**
 * Functions that are not called from the template files
 * and cannot be grouped together into another file.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Load translation from translate.WordPress.org if available
 */
function hannover_load_translation() {
	if ( ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX' ) || ! hannover_is_login_page() || ! hannover_is_wp_comments_post() ) {
		load_theme_textdomain( 'hannover' );
	} // End if().
}

/**
 * Check if we are on the login page
 *
 * @return bool
 */
function hannover_is_login_page() {
	return in_array( $GLOBALS['pagenow'], [
		'wp-login.php',
		'wp-register.php',
	], true );
}

/**
 * Check if we are on the wp-comments-post.php
 *
 * @return bool
 */
function hannover_is_wp_comments_post() {
	return in_array( $GLOBALS['pagenow'], [ 'wp-comments-post.php' ], true );
}

/**
 * Set the content width.
 */
function hannover_set_content_width() {
	// Set the content width to 845px.
	$content_width = 845;

	/**
	 * Make the content width filterable.
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'hannover_content_width', $content_width );
}

/**
 * Adds theme support for feed links, custom logo, html5, post formats,
 * post thumbnails and the title tag
 */
function hannover_add_theme_support() {
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-formats', [
		'aside',
		'link',
		'gallery',
		'status',
		'quote',
		'image',
		'video',
		'audio',
		'chat',
	] );

	add_theme_support( 'html5', [
		'comment-list',
		'comment-form',
		'search-form',
		'gallery',
		'caption',
	] );

	add_theme_support( 'post-thumbnails' );

	add_theme_support( 'custom-logo' );
}

/**
 * Register menu locations.
 */
function hannover_register_menus() {
	register_nav_menus(
		[
			/* translators: Name of menu position in the header */
			'primary' => __( 'Primary Menu', 'hannover' ),
			/* translators: Name of menu position in the footer */
			'footer'  => __( 'Footer Menu', 'hannover' ),
		]
	);
}

/**
 * Register sidebar.
 */
function hannover_register_sidebars() {
	register_sidebar( [
		'name'          => __( 'Main Sidebar', 'hannover' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Widgets in this area will be shown on all normal posts and pages.', 'hannover' ),
		'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	] );
}

/**
 * Adds the scripts and styles.
 */
function hannover_scripts_styles() {
	// Check if we need the comment reply script and include it.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Include lightbox script.
	wp_enqueue_script( 'hannover-script', get_theme_file_uri( 'assets/js/bundle.js' ), [], false, true );

	wp_localize_script( 'hannover-script', 'screenReaderText', [
		'expand'   => __( 'expand child menu', 'hannover' ),
		'collapse' => __( 'collapse child menu', 'hannover' ),
	] );

	/*
	 * Adds slider script to footer if front page template with slider is displayed or user
	 * wants to show all galleries as slider.
	 * Localizes script with strings for next and previous button and slider options from the customizer
	 */
	global $post;
	if ( $post ) {
		$galleries_as_slider = get_theme_mod( 'galleries_as_slider' );
		$page_template       = get_page_template_slug( $post->ID );
		if ( 'page-templates/slider-front-page.php' === $page_template || 'checked' === $galleries_as_slider ) {
			wp_enqueue_style( 'owl-carousel', get_theme_file_uri( 'assets/css/owl-carousel.css' ) );
			wp_enqueue_script( 'owl-carousel', get_theme_file_uri( 'assets/js/owl-carousel.js' ), false, true );
			$slider_autoplay      = get_theme_mod( 'slider_autoplay' );
			$slider_autoplay_time = get_theme_mod( 'slider_autoplay_time', 3000 );
			$params               = [
				'autoplay'        => $slider_autoplay,
				'autoplayTimeout' => $slider_autoplay_time,
				'prev'            => __( 'Previous Slide', 'hannover' ),
				'next'            => __( 'Next Slide', 'hannover' ),

			];
			wp_localize_script( 'owl-carousel', 'OwlParams', $params );
		} // End if().
	} // End if().

	// Check if SCRIPT_DEBUG is defined and enabled.
	if ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ) {
		$suffix = '.css';
	} else {
		$suffix = '.min.css';
	}

	// Check if RTL.
	if ( is_rtl() ) {
		// Enqueue RTL stylesheet.
		wp_enqueue_style( 'hannover-style', get_theme_file_uri( "assets/css/hannover-rtl$suffix" ) );
	} else {
		// Enqueue default stylesheet.
		wp_enqueue_style( 'hannover-style', get_theme_file_uri( "assets/css/hannover$suffix" ) );
	} // End if().

	wp_enqueue_style( 'hannover-fonts', '//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic,700italic', [], null );
}

/**
 * Returns date and time of a post.
 *
 * @return string The date.
 */
function hannover_get_the_date() {
	/* translators: 1=date */
	return sprintf( '<a href="%s" %s>%s</a>',
		get_the_permalink(),
		'' !== get_the_title() ? 'tabindex="-1"' : '',
		get_the_date()
	);
}

/**
 * Returns list of categories for a post.
 *
 * @return string Categories list or empty string.
 */
function hannover_get_categories_list() {
	// Get array of post categories.
	$categories = get_the_category();

	// Check if we have categories in the array. Otherwise return empty string.
	if ( ! empty( $categories ) ) {
		// Build the markup.
		$categories_markup = sprintf( /* translators: 1=category label; 2=category list */
			__( '%1$s: %2$s', 'hannover' ),

			// Display singular or plural label based on the category count.
			_n(
				'Category',
				'Categories',
				count( $categories ),
				'hannover'
			), /* translators: term delimiter */
			get_the_category_list( __( ', ', 'hannover' ) )
		);

		return $categories_markup;
	} else {
		return '';
	} // End if().
}

/**
 * Returns list of tags for a post.
 *
 * @return string Tag list or empty string.
 */
function hannover_get_tag_list() {
	// Get tag array.
	$tags = get_the_tags();

	// Check if we have a tag array, otherwise return empty string.
	if ( is_array( $tags ) ) {
		// Build the markup.
		$tags_markup = sprintf( /* translators: 1=tag label; 2=tag list */
			__( '%1$s: %2$s', 'hannover' ),

			// Display singular or plural label based on tag count.
			_n(
				'Tag',
				'Tags',
				count( $tags ),
				'hannover'
			), /* translators: term delimiter */
			get_the_tag_list( '', __( ', ', 'hannover' ) )
		);

		return $tags_markup;
	} else {
		return '';
	}
}

/**
 * Function to exclude the portfolio elements from the main query if chosen in
 * the customizer
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
				$tax_query = [
					[
						'taxonomy' => 'post_format',
						'field'    => 'slug',
						'terms'    => [
							'post-format-gallery',
							'post-format-image',
						],
						'operator' => 'NOT IN',
					],
				];
				$query->set( 'tax_query', $tax_query );
			}
		}
	}
}

/**
 * Gets the comments seperated by type
 *
 * @return array
 */
function hannover_get_comments_by_type() {
	global $wp_query, $post;
	$comment_args               = [
		'order'   => 'ASC',
		'orderby' => 'comment_date_gmt',
		'status'  => 'approve',
		'post_id' => $post->ID,
	];
	$comments                   = get_comments( $comment_args );
	$wp_query->comments_by_type = separate_comments( $comments );
	$comments_by_type           = &$wp_query->comments_by_type;

	return $comments_by_type;
}

/**
 * Fetch first gallery image post object from a post.
 *
 * @param int $post_id ID of the post.
 *
 * @return array
 */
function hannover_get_fist_gallery_image( $post_id ) {
	$post = get_post( $post_id );

	// That is no post or the post is empty.
	if ( ! $post || empty( $post->post_content ) ) {
		return [];
	}

	$galleries = get_post_galleries( $post, false );
	if ( empty( $galleries ) ) {
		return [];
	}

	$ids = [];
	foreach ( $galleries as $gallery ) {
		if ( ! empty( $gallery['ids'] ) ) {
			$ids = array_merge( $ids, explode( ',', $gallery['ids'] ) );
		}
	}

	$ids = array_unique( $ids );
	if ( empty( $ids ) ) {
		$attachments = hannover_get_post_image_attachments( $post_id );
		if ( ! empty( $attachments ) ) {
			return array_shift( $attachments );
		}

		return [];
	}

	$images = get_post( array_shift( $ids ) );

	if ( null === $images ) {
		return [];
	}

	return $images;
}

/**
 * Returns the image attachments of a post.
 *
 * @param int $post_id ID of the post.
 *
 * @return array
 */
function hannover_get_post_image_attachments( $post_id ) {
	$attachments = get_children( [
		'post_parent'    => $post_id,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
	] );
	if ( empty( $attachments ) ) {
		return [];
	}

	return $attachments;
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
