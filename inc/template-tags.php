<?php
/**
 * Template tags, used in template files.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Displays the entry header.
 *
 * @param string  $thumbnail_size  Size of the post thumbnail.
 * @param string  $headline_elem   Element for the headline.
 * @param boolean $linked_headline If the headline should link to the single
 *                                 view.
 */
function hannover_the_entry_header( $thumbnail_size = 'large', $headline_elem = 'h2', $linked_headline = true ) {
	// Build the markup for the single components.
	$pre_headline_post_thumbnail_markup  = hannover_get_pre_headline_post_thumbnail_markup( $thumbnail_size, $linked_headline );
	$screen_reader_post_thumbnail_markup = hannover_get_screen_reader_post_thumbnail_markup( $thumbnail_size );

	printf(
		'%1$s %2$s %3$s %4$s',
		$pre_headline_post_thumbnail_markup,
		'' !== get_the_title() ? hannover_get_the_title( $headline_elem, $linked_headline ) : '',
		$screen_reader_post_thumbnail_markup,
		! is_page() ? hannover_get_entry_meta() : ''
	);
}

/**
 * Displays post thumbnail markup for display before headline.
 *
 * @param string $thumbnail_size  The thumbnail size.
 * @param bool   $linked_headline If the headline should link to the single
 *                                view.
 *
 * @return string Post thumbnail markup for pre headline output.
 */
function hannover_get_pre_headline_post_thumbnail_markup( $thumbnail_size, $linked_headline = true ) {
	if ( has_post_thumbnail() ) {
		return sprintf(
			'%s<figure class="feature-image" aria-hidden="true">%s</figure>%s',
			true === $linked_headline ? sprintf(
				'<a href="%s" tabindex="-1">',
				get_the_permalink()
			) : '',
			get_the_post_thumbnail( get_the_ID(), $thumbnail_size ),
			true === $linked_headline ? '</a>' : ''
		);
	} else {
		return '';
	} // End if().
}

/**
 * Displays post thumbnail markup for display after headline.
 *
 * @param string $thumbnail_size The thumbnail size.
 *
 * @return string Post thumbnail markup for post headline output.
 */
function hannover_get_screen_reader_post_thumbnail_markup( $thumbnail_size ) {
	if ( has_post_thumbnail() ) {
		return sprintf(
			'<figure class="screen-reader-text">%s</figure>',
			get_the_post_thumbnail( get_the_ID(), $thumbnail_size )
		);
	} else {
		return '';
	} // End if().
}

/**
 * Returns the title of a post.
 *
 * @param string  $heading The heading element to use for wrapping the title.
 * @param boolean $link    True if title should link to the single view,
 *                         othwise false.
 *
 * @return string The formatted HTML string with the title.
 */
function hannover_get_the_title( $heading = 'h2', $link = true ) {
	if ( $link ) {
		return sprintf(
			'<%1$s class="entry-title"><a href="%2$s" rel="bookmark">%3$s</a></%1$s>',
			$heading,
			get_permalink(),
			esc_html( get_the_title() )
		);
	} else {
		return sprintf(
			'<%1$s class="entry-title">%3$s</%1$s>',
			$heading,
			get_permalink(),
			esc_html( get_the_title() )
		);
	}
}

/**
 * Returns unordered list of entry meta data.
 *
 * @return string The entry meta string.
 */
function hannover_get_entry_meta() {
	// Save the meta information.
	$date              = hannover_get_the_date();
	$author            = get_the_author();
	$categories_string = hannover_get_categories_list();
	$tags_string       = hannover_get_tag_list();

	return sprintf(
		'<ul>
<li>%s</li>
%s
%s
</ul>',
		sprintf( /* translators: 1=date; 2=author name*/
			__( '%1$s by %2$s', 'hannover' ),
			$date,
			$author
		),
		'' !== $categories_string ? "<li>$categories_string</li>" : '',
		'' !== $tags_string ? "<li>$tags_string</li>" : ''
	);
}

/**
 * Displays the_content() or the_excerpt() with a more accessible more tag.
 */
function hannover_the_content() {
	// Check if we have an excerpt and display it.
	if ( has_excerpt() ) {
		the_excerpt();
	} else {
		/*
		 * Display the content with an accessible read more link
		 * (if on archive page and more tag is used).
		 */
		the_content(
			sprintf(
				'<span class="screen-reader-text">%s</span><span aria-hidden="true">%s</span>',
				sprintf( /* translators: %s: Title of current post */
					__( 'Continue reading %s', 'hannover' ),
					the_title_attribute( [
						'echo' => false,
					] )
				),
				__( 'Continue reading', 'hannover' )
			)
		);
	} // End if().
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
				[
					'reply_text' => __( 'Reply', 'hannover' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
				]
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
 * Displays post thumbnail or:
 * - first image from gallery if gallery post format.
 * - first image from content if image post format.
 *
 * @param string $size Image size.
 *
 * @return string Img markup or empty string.
 */
function hannover_image_from_gallery_or_image_post( $size ) {
	// Check if we have a post thumbnail and return it.
	if ( has_post_thumbnail() ) {
		return get_the_post_thumbnail( $size );
	} else {
		$img_tag     = '';
		$post_format = get_post_format( get_the_ID() );

		// Check if we have a gallery post.
		if ( 'gallery' === $post_format ) {
			// Get its first image.
			$image   = hannover_get_fist_gallery_image( get_the_ID() );
			$img_tag = wp_get_attachment_image( $image->ID, $size );
		} elseif ( 'image' === $post_format ) {
			$first_img_url = hannover_get_first_image_from_post_content();
			$pattern       = '/-\d+x\d+(\.\w{3,4}$)/i';
			$first_img_url = preg_replace( $pattern, '${1}', $first_img_url );
			$first_img_id  = attachment_url_to_postid( $first_img_url );
			if ( 0 === $first_img_id ) {
				$attachments = hannover_get_post_image_attachments( get_the_ID() );
				if ( empty( $attachments ) ) {
					return '';
				}
				$first_img = array_shift( $attachments );
				$img_tag   = wp_get_attachment_image( $first_img->ID, $size );
			} else {
				$img_tag = wp_get_attachment_image( $first_img_id, $size );
			}
		} else {
			return $img_tag;
		} // End if().

		return $img_tag;
	}
}
