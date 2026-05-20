<?php
/**
 * Theme Setup
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function stb_setup() {
    // Make theme available for translation.
    load_theme_textdomain( 'smarttoolsblog', STB_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails.
    add_theme_support( 'post-thumbnails' );

    // Custom image sizes.
    add_image_size( 'stb-featured', 1200, 630, true );
    add_image_size( 'stb-card', 600, 400, true );
    add_image_size( 'stb-thumbnail', 300, 200, true );

    // Register navigation menus.
    register_nav_menus( array(
        'primary'  => esc_html__( 'Primary Menu', 'smarttoolsblog' ),
        'footer'   => esc_html__( 'Footer Menu', 'smarttoolsblog' ),
        'mobile'   => esc_html__( 'Mobile Menu', 'smarttoolsblog' ),
    ) );

    // HTML5 markup support.
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ) );

    // Custom logo support.
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Wide and full alignment support.
    add_theme_support( 'align-wide' );

    // Editor styles.
    add_theme_support( 'editor-styles' );
}
add_action( 'after_setup_theme', 'stb_setup' );

/**
 * Register widget areas.
 */
function stb_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'smarttoolsblog' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here.', 'smarttoolsblog' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 1', 'smarttoolsblog' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Footer column 1.', 'smarttoolsblog' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 2', 'smarttoolsblog' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Footer column 2.', 'smarttoolsblog' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer 3', 'smarttoolsblog' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Footer column 3.', 'smarttoolsblog' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'stb_widgets_init' );
