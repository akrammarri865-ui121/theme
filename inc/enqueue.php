<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end styles and scripts.
 */
function stb_enqueue_assets() {
    // Main stylesheet.
    wp_enqueue_style(
        'stb-main',
        STB_URI . '/assets/css/main.css',
        array(),
        STB_VERSION
    );

    // Main script - deferred, no jQuery dependency.
    wp_enqueue_script(
        'stb-main',
        STB_URI . '/assets/js/main.js',
        array(),
        STB_VERSION,
        array(
            'strategy'  => 'defer',
            'in_footer' => true,
        )
    );

    // Comment reply script.
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'stb_enqueue_assets' );

/**
 * Preload critical assets.
 */
function stb_preload_assets() {
    printf(
        '<link rel="preload" href="%s" as="style">',
        esc_url( STB_URI . '/assets/css/main.css' )
    );
    echo "\n";
}
add_action( 'wp_head', 'stb_preload_assets', 1 );

/**
 * Add defer attribute to non-critical scripts.
 *
 * @param string $tag    Script tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function stb_defer_scripts( $tag, $handle ) {
    $defer_handles = array( 'stb-main' );

    if ( in_array( $handle, $defer_handles, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'stb_defer_scripts', 10, 2 );

/**
 * Remove jQuery migrate for performance.
 *
 * @param WP_Scripts $scripts WP_Scripts instance.
 */
function stb_remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'stb_remove_jquery_migrate' );

/**
 * Add resource hints for performance.
 */
function stb_resource_hints() {
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'stb_resource_hints', 2 );
