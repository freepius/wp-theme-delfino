<?php
/**
 * Delfino functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Delfino
 */

if ( ! defined( 'DELFINO_VERSION' ) ) {
	define( 'DELFINO_VERSION', '1.0.0' );
}


if ( ! function_exists( 'delfino_setup' ) ) :
	function delfino_setup() {
		load_theme_textdomain( 'delfino', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );

		register_nav_menus([
			'main_menu' => esc_html__( 'Main menu', 'delfino' ),
		]);

		add_theme_support('html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		]);
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
	register_sidebar([
		'name'          => esc_html__( 'Header content', 'delfino' ),
		'id'            => 'header-content',
		'description'   => esc_html__( 'Add your header content here.', 'delfino' ),
		'before_sidebar' => '<section id="%1$s">',
		'after_sidebar' => '</section>',
	]);
}
add_action( 'widgets_init', 'delfino_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function delfino_scripts() {
	$uri = get_template_directory_uri();

	wp_enqueue_style( 'delfino-style', get_stylesheet_uri(), [], DELFINO_VERSION );
	wp_enqueue_script( 'delfino-main', "$uri/js/main.js", [], DELFINO_VERSION, true );

	// javascript for the home page 'infinite scroll' effect
	if (is_home()) {
		wp_enqueue_script( 'delfino-infinite-scroll', "$uri/js/infinite-scroll.js", [], DELFINO_VERSION, true );
		wp_localize_script( 'delfino-infinite-scroll', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'delfino_scripts' );


/**
 * Manage Ajax request for the home page 'infinite scroll' effect.
 */
function delfino_infinite_scroll() {
	if (! isset($_GET['offset'])) {
		wp_die();
	}

	$query = new WP_Query([
		'cat' => 1, // 'home' category
		'offset' => (int) $_GET['offset'],
	]);

	while ($query->have_posts()) {
		$query->the_post();
		get_template_part( 'template-parts/content', get_post_type() );
	}

	wp_die();
}
add_action( 'wp_ajax_delfino_infinite_scroll', 'delfino_infinite_scroll' );
add_action( 'wp_ajax_nopriv_delfino_infinite_scroll', 'delfino_infinite_scroll' );


/**
 * For home page, query only the 'home' category (cat = 1)
 */
function delfino_get_home_posts($query) {
	if ($query->is_home() && $query->is_main_query()) {
		$query->set('cat', 1);
	}
}
add_action( 'pre_get_posts', 'delfino_get_home_posts' );
