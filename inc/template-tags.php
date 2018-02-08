<?php
/**
 * Template tags, used in template files.
 *
 * @version 2.0.0
 *
 * @package Hannover
 */

/**
 * Displays date and time of a post.
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
 * Displays the title of a post.
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
 * Displays the_content with a more accessible more tag.
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
 * Displays the author, categories, tags and number for comments and trackbacks.
 *
 * @return void
 */
function hannover_entry_meta() { ?>
	<span
		class="author"><?php /* translators: name of the author in entry footer. s=author name */
		printf( __( 'Author %s', 'hannover' ),
			'<span>' . get_the_author() . '</span>' ) ?></span>
	<?php if ( get_the_category() ) { ?>
		<span
			class="categories"><?php /* translators: Label for category list in entry footer. s=categories */
			printf( _n(
				'Category %s',
				'Categories %s',
				count( get_the_category() ),
				'hannover'
			), /* translators: term delimiter */
				'<span>' . get_the_category_list( __( ', ', 'hannover' ) ) . '</span>' ) ?></span>
	<?php }
	if ( get_the_tags() ) { ?>
		<span
			class="tags"><?php /* translators: Label for tag list in entry footer. s=tags */
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
		<span
			class="comments"><?php /* translators: Label for comment number in entry footer. s=comment number */
			printf( _n(
				'%s Comment',
				'%s Comments',
				$comment_number,
				'hannover'
			), '<span>' . number_format_i18n( $comment_number ) . '</span>' ) ?></span>
	<?php }
	if ( $comments_by_type['pings'] ) {
		$trackback_number = count( $comments_by_type['pings'] ); ?>
		<span
			class="trackbacks"><?php /* translators: Label for trackback number in entry footer. s=trackback number */
			printf( _n(
				'%s Trackback',
				'%s Trackbacks',
				$trackback_number,
				'hannover'
			), '<span>' . number_format_i18n( $trackback_number ) . '</span>' ) ?></span>
	<?php };
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
				$attachments = get_children( [
					'post_parent'    => $post->ID,
					'post_status'    => 'inherit',
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'ASC',
					'orderby'        => 'menu_order',
				] );
				$first_img   = array_shift( $attachments );
				$img_tag     = wp_get_attachment_image( $first_img->ID, $size );
			} else {
				$img_tag = wp_get_attachment_image( $first_img_id, $size );
			}
		}
		echo $img_tag;
	}
}
