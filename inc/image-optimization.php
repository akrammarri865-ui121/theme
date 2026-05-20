<?php
/**
 * Image Optimization - WebP, Lazy Loading, Srcset
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add loading="lazy" and decoding="async" to images.
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function stb_lazy_load_images( $content ) {
    if ( is_admin() || is_feed() ) {
        return $content;
    }

    // Add decoding async to images that don't already have it.
    $content = preg_replace(
        '/<img(?![^>]*decoding)([^>]*)>/i',
        '<img decoding="async"$1>',
        $content
    );

    return $content;
}
add_filter( 'the_content', 'stb_lazy_load_images', 20 );

/**
 * Enable WebP upload support.
 *
 * @param array $mimes Allowed mime types.
 * @return array Modified mime types.
 */
function stb_allow_webp_upload( $mimes ) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter( 'mime_types', 'stb_allow_webp_upload' );

/**
 * Enable WebP image editing support.
 *
 * @param array $editors Image editors.
 * @return array Image editors.
 */
function stb_webp_image_editor( $editors ) {
    // WordPress 5.8+ supports WebP natively.
    return $editors;
}
add_filter( 'wp_image_editors', 'stb_webp_image_editor' );

/**
 * Set default image quality for compression.
 *
 * @param int $quality Image quality.
 * @return int Modified quality.
 */
function stb_image_quality( $quality ) {
    return 82;
}
add_filter( 'wp_editor_set_quality', 'stb_image_quality' );

/**
 * Output WebP source in picture element (helper function).
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Image size.
 * @return string HTML output.
 */
function stb_responsive_image( $attachment_id, $size = 'stb-card' ) {
    if ( ! $attachment_id ) {
        return '';
    }

    $image_src  = wp_get_attachment_image_src( $attachment_id, $size );
    $image_srcset = wp_get_attachment_image_srcset( $attachment_id, $size );
    $image_sizes  = wp_get_attachment_image_sizes( $attachment_id, $size );
    $alt          = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

    if ( ! $image_src ) {
        return '';
    }

    $html = '<img';
    $html .= ' src="' . esc_url( $image_src[0] ) . '"';

    if ( $image_srcset ) {
        $html .= ' srcset="' . esc_attr( $image_srcset ) . '"';
    }

    if ( $image_sizes ) {
        $html .= ' sizes="' . esc_attr( $image_sizes ) . '"';
    }

    $html .= ' alt="' . esc_attr( $alt ) . '"';
    $html .= ' loading="lazy"';
    $html .= ' decoding="async"';
    $html .= ' width="' . esc_attr( $image_src[1] ) . '"';
    $html .= ' height="' . esc_attr( $image_src[2] ) . '"';
    $html .= '>';

    return $html;
}

/**
 * Disable big image size threshold (WordPress auto-scales to 2560px).
 * Keep it reasonable for performance.
 *
 * @param int $threshold Threshold value.
 * @return int Modified threshold.
 */
function stb_big_image_threshold( $threshold ) {
    return 1920;
}
add_filter( 'big_image_size_threshold', 'stb_big_image_threshold' );

/**
 * Add fetchpriority="high" to above-the-fold featured images.
 *
 * @param array $attr Image attributes.
 * @param WP_Post $attachment Attachment post object.
 * @param string $size Image size.
 * @return array Modified attributes.
 */
function stb_featured_image_priority( $attr, $attachment, $size ) {
    if ( is_singular() && has_post_thumbnail() && $size === 'stb-featured' ) {
        $attr['fetchpriority'] = 'high';
        $attr['loading']       = 'eager';
        unset( $attr['loading'] );
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'stb_featured_image_priority', 10, 3 );
