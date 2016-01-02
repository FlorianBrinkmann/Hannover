<?php
/**
 * Template for displaying search results
 *
 * @version 1.0.6
 */
get_header(); ?>
	<main role="main">
		<?php if ( have_posts() ) { ?>
			<header class="page-header">
				<h1><?php printf( __( 'Search Results for: %s', 'hannover' ), esc_html( get_search_query() ) ); ?></h1>
			</header>
			<?php while ( have_posts() ) {
				the_post();
				get_template_part( 'template-parts/content', get_post_format() );
			}
		} else { ?>
			<header class="page-header">
				<h1><?php printf( __( 'Nothing found for "%s"', 'hannover' ), esc_html( get_search_query() ) ); ?></h1>
			</header>
			<?php get_search_form();
		}
		the_posts_pagination( array( 'type' => 'list' ) ); ?>
	</main>
<?php get_sidebar();
get_footer();