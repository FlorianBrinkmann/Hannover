<?php
/**
 * Comment template
 *
 * @version 1.0.8
 */
if ( post_password_required() ) {
	return;
} ?>
<div id="comments" class="comments-area">
	<?php if ( have_comments() ) {
		if ( ! empty( $comments_by_type['comment'] ) ) {
			$comment_number = count( $comments_by_type['comment'] ); ?>
			<h2 id="comments-title">
				<?php /* translators: Title for comment list. 1=comment number, 2=post title */
				printf( _n(
					'%1$s Comment on "%2$s"',
					'%1$s Comments on "%2$s"',
					$comment_number,
					'hannover'
				), number_format_i18n( $comment_number ),
					get_the_title() ) ?>
			</h2>

			<ol class="commentlist">
				<?php wp_list_comments( array(
					'callback' => 'hannover_comments',
					'style'    => 'ol',
					'type'     => 'comment'
				) ); ?>
			</ol>
		<?php }
		if ( ! empty( $comments_by_type['pings'] ) ) {
			$trackback_number = count( $comments_by_type['pings'] ); ?>
			<h2 id="trackbacks-title">
				<?php /* translators: Title for trackback list. 1=trackback number, 2=post title */
				printf( _n(
					'%1$s Trackback on "%2$s"',
					'%1$s Trackbacks on "%2$s"',
					$trackback_number,
					'hannover'
				), number_format_i18n( $trackback_number ), get_the_title() ) ?>
			</h2>

			<ol class="commentlist">
				<?php wp_list_comments( array(
					'callback' => 'hannover_trackbacks',
					'type'     => 'pings',
				) ); ?>
			</ol>
		<?php }
		the_comments_navigation();
		if ( ! comments_open() && get_comments_number() ) { ?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'hannover' ); ?></p>
		<?php }
	}
	comment_form( array( 'label_submit' => __( 'Submit Comment', 'hannover' ) ) ); ?>
</div>