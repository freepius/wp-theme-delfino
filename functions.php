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
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	]);
}
add_action( 'widgets_init', 'delfino_widgets_init' );


/**
 * Enqueue scripts and styles.
 */
function delfino_scripts() {
	wp_enqueue_style( 'delfino-style', get_stylesheet_uri(), [], DELFINO_VERSION );
	wp_enqueue_script( 'delfino-main', get_template_directory_uri() . '/js/main.js', [], DELFINO_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'delfino_scripts' );
