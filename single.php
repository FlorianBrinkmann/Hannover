<?php
/**
 * Template file for displaying a single post
 *
 * @version 1.0.6
 */
get_header();
$portfolio_post = false;
$format         = get_post_format( $post );
if ( $format == 'gallery' || $format == 'image' ) {
	$use_portfolio_category = get_theme_mod( 'portfolio_from_category' );
	if ( $use_portfolio_category == 'checked' ) {
		$portfolio_category = get_theme_mod( 'portfolio_category' );
		if ( has_category( $portfolio_category, $post ) ) {
			$portfolio_post = true;
		}
	} else {
		$portfolio_post = true;
	}
} ?>
	<main role="main"<?php if ( $portfolio_post ) {
		echo ' class="portfolio-element"';
	} ?>>
		<?php if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				if ( $portfolio_post ) {
					$exclude_portfolio_elements = get_theme_mod( 'exclude_portfolio_elements_from_blog' );
					get_template_part( 'template-parts/content-single-portfolio' );
					if ( $exclude_portfolio_elements != 'checked' ) {
						the_post_navigation();
					}
				} else {
					get_template_part( 'template-parts/content-single', get_post_format() );
					the_post_navigation();
				}
				if ( comments_open() || get_comments_number() ) {
					comments_template( '', true );
				}
			}
		} else {
			get_template_part( 'template-parts/content', 'none' );
		} ?>
	</main>
<?php if ( ! $portfolio_post ) {
	get_sidebar();
}
get_footer();