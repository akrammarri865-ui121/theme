<?php
/**
 * Enqueue Scripts and Styles - Performance Optimized
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue front-end styles and scripts.
 */
function stb_enqueue_assets() {
    // Main stylesheet - critical CSS.
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

    // Comment reply script - only when needed.
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Dequeue jQuery on front-end if not needed by plugins.
    if ( ! is_admin() ) {
        // Only dequeue if no other scripts depend on it.
        global $wp_scripts;
        $jquery_needed = false;
        if ( isset( $wp_scripts->registered ) ) {
            foreach ( $wp_scripts->registered as $script ) {
                if ( isset( $script->deps ) && in_array( 'jquery', $script->deps, true ) && 'jquery-migrate' !== $script->handle ) {
                    $jquery_needed = true;
                    break;
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'stb_enqueue_assets' );

/**
 * Preload critical assets in head.
 */
function stb_preload_assets() {
    // Preload main CSS.
    printf(
        '<link rel="preload" href="%s" as="style">' . "\n",
        esc_url( STB_URI . '/assets/css/main.css' )
    );

    // Preload main JS (deferred).
    printf(
        '<link rel="modulepreload" href="%s">' . "\n",
        esc_url( STB_URI . '/assets/js/main.js' )
    );
}
add_action( 'wp_head', 'stb_preload_assets', 1 );

/**
 * Resource hints for external domains.
 */
function stb_resource_hints() {
    // DNS prefetch for common ad/analytics domains.
    $domains = array(
        '//fonts.googleapis.com',
        '//pagead2.googlesyndication.com',
        '//www.googletagmanager.com',
    );

    foreach ( $domains as $domain ) {
        printf( '<link rel="dns-prefetch" href="%s">' . "\n", esc_url( $domain ) );
    }

    // Preconnect for fonts.
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'stb_resource_hints', 2 );

/**
 * Optimize script loading with defer/async attributes.
 *
 * @param string $tag    Full script tag.
 * @param string $handle Script handle.
 * @param string $src    Script source URL.
 * @return string Modified script tag.
 */
function stb_optimize_script_loading( $tag, $handle, $src ) {
    // Skip in admin.
    if ( is_admin() ) {
        return $tag;
    }

    // Scripts to defer (non-critical).
    $defer_scripts = array( 'stb-main', 'comment-reply' );

    if ( in_array( $handle, $defer_scripts, true ) ) {
        // Avoid double defer.
        if ( strpos( $tag, 'defer' ) === false ) {
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'stb_optimize_script_loading', 10, 3 );

/**
 * Optimize style loading - add media attribute for non-critical CSS.
 *
 * @param string $tag    Full link tag.
 * @param string $handle Style handle.
 * @param string $href   Stylesheet URL.
 * @param string $media  Media type.
 * @return string Modified tag.
 */
function stb_optimize_style_loading( $tag, $handle, $href, $media ) {
    if ( is_admin() ) {
        return $tag;
    }

    // Non-critical styles - load asynchronously.
    $async_styles = array( 'wp-block-library' );

    if ( in_array( $handle, $async_styles, true ) ) {
        $tag = str_replace(
            "media='all'",
            "media='print' onload=\"this.media='all'\"",
            $tag
        );
        // Add noscript fallback.
        $tag .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>' . "\n";
    }

    return $tag;
}
add_filter( 'style_loader_tag', 'stb_optimize_style_loading', 10, 4 );

/**
 * Remove jQuery Migrate for front-end performance.
 *
 * @param WP_Scripts $scripts Scripts object.
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
 * Remove unnecessary WordPress head items for cleaner output.
 */
function stb_cleanup_head() {
    // Remove WP emoji scripts.
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

    // Remove RSD link.
    remove_action( 'wp_head', 'rsd_link' );

    // Remove Windows Live Writer manifest.
    remove_action( 'wp_head', 'wlwmanifest_link' );

    // Remove short link.
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );

    // Remove REST API link from head (still accessible).
    remove_action( 'wp_head', 'rest_output_link_wp_head' );

    // Remove oEmbed discovery links.
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

    // Remove generator meta tag.
    remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'stb_cleanup_head' );

/**
 * Disable WP embed script on front-end.
 */
function stb_disable_embed_script() {
    if ( ! is_admin() ) {
        wp_deregister_script( 'wp-embed' );
    }
}
add_action( 'wp_footer', 'stb_disable_embed_script' );

/**
 * Optimize Google Fonts loading with font-display swap.
 * Use system font stack by default - only load Google Fonts if configured.
 */
function stb_google_fonts() {
    $font_url = get_theme_mod( 'stb_google_font_url', '' );
    if ( empty( $font_url ) ) {
        return;
    }

    // Add display=swap parameter.
    if ( strpos( $font_url, 'display=' ) === false ) {
        $font_url = add_query_arg( 'display', 'swap', $font_url );
    }

    printf(
        '<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n",
        esc_url( $font_url )
    );
    printf(
        '<noscript><link rel="stylesheet" href="%s"></noscript>' . "\n",
        esc_url( $font_url )
    );
}
add_action( 'wp_head', 'stb_google_fonts', 3 );

/**
 * Add critical inline CSS for above-the-fold rendering.
 */
function stb_critical_inline_css() {
    ?>
    <style id="stb-critical-css">
        body{margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif}
        .site-header{position:sticky;top:0;z-index:1000;background:var(--color-bg,#fff);border-bottom:1px solid var(--color-border,#e2e8f0)}
        .header-inner{display:flex;align-items:center;justify-content:space-between;max-width:1200px;margin:0 auto;padding:1rem;min-height:64px}
        .container{max-width:1200px;margin:0 auto;padding:0 1rem}
    </style>
    <?php
}
add_action( 'wp_head', 'stb_critical_inline_css', 0 );
