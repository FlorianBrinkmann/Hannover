<?php if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) {
		if ( ! empty( $comments_by_type['comment'] ) ) { ?>
			<h2 id="comments-title">
				<?php printf( _nx(
					'%1$s Comment on “%2$s”',
					'%1$s Comments on “%2$s”',
					hannover_get_comment_count(),
					'Title for comment list. 1=comment number, 2=post title',
					'hannover'
				), number_format_i18n( hannover_get_comment_count() ), get_the_title() ) ?>
			</h2>

			<ol class="commentlist">
				<?php wp_list_comments( array(
					'callback' => 'hannover_comments',
					'style'    => 'ol',
					'type'     => 'comment'
				) ); ?>
			</ol>
		<?php }
		if ( ! empty( $comments_by_type['pings'] ) ) { ?>
			<h2 id="trackbacks-title">
				<?php printf( _nx(
					'%1$s Trackback on “%2$s”',
					'%1$s Trackbacks on “%2$s”',
					hannover_get_trackback_count(),
					'Title for trackback list. 1=trackback number, 2=post title',
					'hannover'
				), number_format_i18n( hannover_get_trackback_count() ), get_the_title() ) ?>
			</h2>

			<ol class="commentlist">
				<?php wp_list_comments( array(
					'callback'   => 'hannover_trackbacks',
					'type'       => 'pings',
					'short_ping' => true,
				) ); ?>
			</ol>
		<?php }
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<nav id="comment-nav-below" class="navigation" role="navigation">
				<h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'bornholm' ); ?></h2>

				<div
					class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'bornholm' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'bornholm' ) ); ?></div>
			</nav>
		<?php }
		if ( ! comments_open() && get_comments_number() ) { ?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'bornholm' ); ?></p>
		<?php }

	}
	comment_form( array( 'comment_notes_after' => '', 'label_submit' => __( 'Submit Comment', 'bornholm' ) ) ); ?>
</div>