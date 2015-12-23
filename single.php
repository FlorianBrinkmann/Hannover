<?php get_header();
$use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
$portfolio_post         = false;
if ( $use_portfolio_category == 'checked' ) {
	$portfolio_category = get_theme_mod( 'portfolio_category' );
	if ( has_category( $portfolio_category, $post ) ) {
		$portfolio_post = true;
	}
} else {
	$format = get_post_format( $post_id );
	if ( $format == 'gallery' || $format == 'image' ) {
		$portfolio_post = true;
	}
} ?>
	<main role="main">
		<?php while ( have_posts() ) {
			the_post();
			if ( $portfolio_post ) {
				get_template_part( 'template-parts/content-single-portfolio' );
			} else {
				get_template_part( 'template-parts/content-single', get_post_format() );
				the_post_navigation();
			}
			if ( comments_open() || get_comments_number() ) {
				comments_template( '', true );
			}
		} ?>
	</main>
<?php if ( ! $portfolio_post ) {
	get_sidebar();
}
get_footer();