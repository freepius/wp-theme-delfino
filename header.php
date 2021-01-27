<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section, the "skip link", the <header> and <nav> sections.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Delfino
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'delfino' ); ?></a>

<header>
	<?php
	$delfino_description = get_bloginfo( 'description', 'display' );

	if ( $delfino_description || is_customize_preview() ) {
		echo "<p>$delfino_description</p>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	is_active_sidebar( 'header-content' ) ? dynamic_sidebar( 'header-content' ) : '';
	?>
</header>

<nav>
	<h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<button aria-controls="main-menu" aria-expanded="false" aria-label="Toggle the main menu">Menu</button>

	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'main_menu',
			'menu_id'        => 'main-menu',
			'container'      => false,
		)
	);
	?>
</nav>
