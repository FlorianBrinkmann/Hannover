<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( is_singular() && pings_open() ) { ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php }
	wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="header">
	<div class="site-branding">
		<?php if ( get_header_image() ) {
			if ( is_front_page() && is_home() ) { ?>
				<h1 class="logo"><img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>"></h1>
			<?php } else {
				if ( ! is_front_page() ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php } ?>
				<img class="logo" src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>">
				<?php if ( ! is_front_page() ) { ?>
					</a >
				<?php }
			}
		} else {
			if ( is_front_page() && is_home() ) { ?>
				<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
			<?php } else {
				if ( ! is_front_page() ) { ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php } ?>
				<p class="site-title"><?php bloginfo( 'name' ); ?></p>
				<?php if ( ! is_front_page() ) { ?>
					</a >
				<?php }
			}
		}
		$description = get_bloginfo( 'description', 'display' );
		if ( $description ) { ?>
			<p class="site-description"><?php echo $description; ?></p>
		<?php } ?>
	</div>
	<?php if ( has_nav_menu( 'primary' ) ) { ?>
		<button id="menu-toggle" class="menu-toggle"><?php _e( 'Menu', 'hannover' ); ?></button>
		<nav>
			<h2 class="screen-reader-text">
				<?php _ex( 'Main navigation', 'hidden screen reader headline for the main navigation', 'hannover' ); ?>
			</h2>
			<?php wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'primary-menu',
					'container'      => ''
				)
			); ?>
		</nav>
	<?php } ?>
</header>
<div id="content">