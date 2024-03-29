<?php
/**
 * Delfino functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Delfino
 */

// Version is also to update in: style.css, readme.txt, package.json.
if ( ! defined( 'DELFINO_VERSION' ) ) {
	define( 'DELFINO_VERSION', '1.1.5' );
}


if ( ! function_exists( 'delfino_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function delfino_setup() {
		load_theme_textdomain( 'delfino', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );

		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		add_theme_support(
			'post-formats',
			array(
				'gallery',
			)
		);

		register_nav_menus(
			array(
				'main_menu' => esc_html__( 'Main menu', 'delfino' ),
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'delfino_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function delfino_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'delfino_content_width', 640 );
}
add_action( 'after_setup_theme', 'delfino_content_width', 0 );


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function delfino_widgets_init() {
	register_sidebar(
		array(
			'name'           => esc_html__( 'Header content', 'delfino' ),
			'id'             => 'header-content',
			'description'    => esc_html__( 'Add your header content here.', 'delfino' ),
			'before_sidebar' => '<section id="%1$s">',
			'after_sidebar'  => '</section>',
		)
	);
}
add_action( 'widgets_init', 'delfino_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function delfino_scripts() {
	require_once __DIR__ . '/vendor/autoload.php';
	$enqueue = new \WPackio\Enqueue( 'delfino', 'dist', DELFINO_VERSION, 'theme', false );

	// Enqeue the style.css file.
	wp_enqueue_style( 'delfino-style', get_stylesheet_uri(), array(), DELFINO_VERSION );

	// Main css and javascript (used in all theme pages).
	$enqueue->enqueue( 'app', 'main', array() );

	// Javascript for the home page 'infinite scroll' effect.
	if ( is_home() ) {
		$assets = $enqueue->enqueue( 'app', 'infinite-scroll', array() );
		$url    = wp_nonce_url( admin_url( 'admin-ajax.php' ), 'delfino_infinite_scroll' );

		wp_localize_script( $assets['js'][0]['handle'], 'ajaxurl', $url );
	}

	// Javascript for the gallery posts management.
	if ( is_single() && has_post_format( 'gallery' ) ) {
		$enqueue->enqueue( 'app', 'gallery', array() );
	}
}
add_action( 'wp_enqueue_scripts', 'delfino_scripts' );


/**
 * Manage Ajax request for the home page 'infinite scroll' effect.
 */
function delfino_infinite_scroll() {
	check_ajax_referer( 'delfino_infinite_scroll' );

	if ( ! isset( $_GET['offset'] ) ) {
		wp_die();
	}

	$query = new WP_Query(
		array(
			'cat'    => 1, // 'home' category
			'offset' => (int) $_GET['offset'],
		)
	);

	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'inc/template-parts/content', get_post_type() );
	}

	wp_die();
}
add_action( 'wp_ajax_delfino_infinite_scroll', 'delfino_infinite_scroll' );
add_action( 'wp_ajax_nopriv_delfino_infinite_scroll', 'delfino_infinite_scroll' );


/**
 * -> For home page, query only the 'home' category (cat = 1).
 * -> For archive page (including the category page), no posts limit.
 *
 * @param WP_Query $query The current running query.
 */
function delfino_get_posts( $query ) {
	if ( ! $query->is_main_query() ) {
		return;
	}

	$query->set( 'posts_per_archive_page', -1 );

	if ( $query->is_home() ) {
		$query->set( 'cat', 1 );
	}
}
add_action( 'pre_get_posts', 'delfino_get_posts' );


/**
 * For a gallery post, add a `body` class to activate the default view.
 *
 * @param array $classes An array of body class names.
 */
function delfino_gallery_activate_default_view( array $classes ): array {
	if ( is_single() && has_post_format( 'gallery' ) ) {
		$classes[] = 'gallery-view-index';
	}

	return $classes;
}
add_filter( 'body_class', 'delfino_gallery_activate_default_view' );

/**
 * For a gallery post in the 'Portraits' category, display its 'index' view in full page.
 */
add_filter(
	'body_class',
	fn ( array $classes ): array => in_category( 'Portraits' )
	? array_merge( $classes, array( 'gallery-full-page-index' ) )
	: $classes
);
