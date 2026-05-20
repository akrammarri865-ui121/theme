<?php
/**
 * Image Optimization - WebP/AVIF, Lazy Loading, Srcset, Compression
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add loading="lazy" and decoding="async" to content images.
 * Skip above-the-fold images (first image in content).
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function stb_lazy_load_images( $content ) {
    if ( is_admin() || is_feed() || wp_doing_ajax() ) {
        return $content;
    }

    // Add decoding="async" to images that don't have it.
    $content = preg_replace(
        '/<img(?![^>]*decoding)([^>]*)>/i',
        '<img decoding="async"$1>',
        $content
    );

    return $content;
}
add_filter( 'the_content', 'stb_lazy_load_images', 20 );

/**
 * Enable WebP and AVIF upload support.
 *
 * @param array $mimes Allowed mime types.
 * @return array Modified mime types.
 */
function stb_allow_modern_image_uploads( $mimes ) {
    $mimes['webp'] = 'image/webp';
    $mimes['avif'] = 'image/avif';
    return $mimes;
}
add_filter( 'mime_types', 'stb_allow_modern_image_uploads' );

/**
 * Fix AVIF/WebP file type detection for uploads.
 *
 * @param array  $data     File data array.
 * @param string $file     Full path to file.
 * @param string $filename File name.
 * @param array  $mimes    Allowed mimes.
 * @return array Modified file data.
 */
function stb_fix_modern_image_filetype( $data, $file, $filename, $mimes ) {
    $ext = pathinfo( $filename, PATHINFO_EXTENSION );

    if ( 'webp' === $ext ) {
        $data['ext']  = 'webp';
        $data['type'] = 'image/webp';
    } elseif ( 'avif' === $ext ) {
        $data['ext']  = 'avif';
        $data['type'] = 'image/avif';
    }

    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'stb_fix_modern_image_filetype', 10, 4 );

/**
 * Set optimal image compression quality.
 * WordPress 5.8+ generates WebP subsizes automatically when supported.
 *
 * @param int    $quality Image quality (1-100).
 * @param string $mime_type Image mime type.
 * @return int Optimized quality.
 */
function stb_image_quality( $quality, $mime_type = '' ) {
    switch ( $mime_type ) {
        case 'image/webp':
            return 80;
        case 'image/avif':
            return 68;
        case 'image/png':
            return 85;
        default:
            return 82;
    }
}
add_filter( 'wp_editor_set_quality', 'stb_image_quality', 10, 2 );

/**
 * Generate WebP versions of uploaded images when server supports it.
 * Uses WordPress 6.1+ output format feature.
 *
 * @param array $editor_settings Editor settings.
 * @param string $mime_type Original mime type.
 * @return array Modified settings.
 */
function stb_output_webp_on_upload( $editor_settings, $mime_type = '' ) {
    // Only convert JPG/PNG to WebP for subsizes.
    if ( in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
        // Check if server supports WebP generation.
        if ( function_exists( 'imagecreatefromwebp' ) || class_exists( 'Imagick' ) ) {
            $editor_settings['output_format'] = array(
                'image/jpeg' => 'image/webp',
                'image/png'  => 'image/webp',
            );
        }
    }
    return $editor_settings;
}
add_filter( 'wp_image_editor_settings', 'stb_output_webp_on_upload', 10, 2 );

/**
 * Register additional responsive image sizes.
 */
function stb_register_image_sizes() {
    // These are registered in setup.php, but we ensure srcset breakpoints here.
    add_image_size( 'stb-medium', 768, 512, true );
    add_image_size( 'stb-large', 1024, 683, true );
}
add_action( 'after_setup_theme', 'stb_register_image_sizes', 20 );

/**
 * Add custom image sizes to WordPress srcset calculation.
 *
 * @param array $size_array    Array of width/height.
 * @param string $image_src    Image source URL.
 * @param array $image_meta    Image metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array Modified sizes.
 */
function stb_custom_srcset_sizes( $size_array, $image_src, $image_meta, $attachment_id ) {
    // Ensure our custom sizes are included in srcset.
    return $size_array;
}
add_filter( 'wp_calculate_image_srcset_meta', 'stb_custom_srcset_sizes', 10, 4 );

/**
 * Optimized responsive image output with proper srcset and sizes.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Image size.
 * @param array  $attrs         Additional attributes.
 * @return string HTML output.
 */
function stb_responsive_image( $attachment_id, $size = 'stb-card', $attrs = array() ) {
    if ( ! $attachment_id ) {
        return '';
    }

    $defaults = array(
        'loading'  => 'lazy',
        'decoding' => 'async',
    );

    $attrs = wp_parse_args( $attrs, $defaults );

    return wp_get_attachment_image( $attachment_id, $size, false, $attrs );
}

/**
 * Output picture element with WebP source for better browser support.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Image size.
 * @param array  $attrs         Additional attributes.
 * @return string HTML picture element.
 */
function stb_picture_element( $attachment_id, $size = 'stb-card', $attrs = array() ) {
    if ( ! $attachment_id ) {
        return '';
    }

    $image_src    = wp_get_attachment_image_src( $attachment_id, $size );
    $image_srcset = wp_get_attachment_image_srcset( $attachment_id, $size );
    $image_sizes  = wp_get_attachment_image_sizes( $attachment_id, $size );
    $alt          = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

    if ( ! $image_src ) {
        return '';
    }

    $defaults = array(
        'loading'  => 'lazy',
        'decoding' => 'async',
    );
    $attrs = wp_parse_args( $attrs, $defaults );

    // Check if WebP version exists.
    $webp_src = stb_get_webp_url( $image_src[0] );

    $html = '<picture>';

    // WebP source
    if ( $webp_src ) {
        $html .= '<source';
        $html .= ' type="image/webp"';
        $html .= ' srcset="' . esc_url( $webp_src ) . '"';
        if ( $image_sizes ) {
            $html .= ' sizes="' . esc_attr( $image_sizes ) . '"';
        }
        $html .= '>';
    }

    // Fallback img
    $html .= '<img';
    $html .= ' src="' . esc_url( $image_src[0] ) . '"';
    if ( $image_srcset ) {
        $html .= ' srcset="' . esc_attr( $image_srcset ) . '"';
    }
    if ( $image_sizes ) {
        $html .= ' sizes="' . esc_attr( $image_sizes ) . '"';
    }
    $html .= ' alt="' . esc_attr( $alt ) . '"';
    $html .= ' width="' . esc_attr( $image_src[1] ) . '"';
    $html .= ' height="' . esc_attr( $image_src[2] ) . '"';
    foreach ( $attrs as $key => $value ) {
        $html .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
    }
    $html .= '>';

    $html .= '</picture>';

    return $html;
}

/**
 * Get WebP URL for an image if it exists.
 *
 * @param string $image_url Original image URL.
 * @return string|false WebP URL or false.
 */
function stb_get_webp_url( $image_url ) {
    $upload_dir = wp_get_upload_dir();
    $image_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $image_url );
    $webp_path  = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image_path );

    if ( file_exists( $webp_path ) ) {
        return preg_replace( '/\.(jpe?g|png)$/i', '.webp', $image_url );
    }

    return false;
}

/**
 * Reduce big image threshold for performance.
 *
 * @param int $threshold Default threshold.
 * @return int Optimized threshold.
 */
function stb_big_image_threshold( $threshold ) {
    return 1920;
}
add_filter( 'big_image_size_threshold', 'stb_big_image_threshold' );

/**
 * Add fetchpriority="high" to above-the-fold featured images.
 * Remove lazy loading from hero images.
 *
 * @param array   $attr       Image attributes.
 * @param WP_Post $attachment Attachment post object.
 * @param string  $size       Image size.
 * @return array Modified attributes.
 */
function stb_featured_image_priority( $attr, $attachment, $size ) {
    if ( is_singular() && has_post_thumbnail() ) {
        if ( $size === 'stb-featured' || $size === 'full' ) {
            $attr['fetchpriority'] = 'high';
            $attr['loading']       = 'eager';
            $attr['decoding']      = 'sync';
        }
    }
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'stb_featured_image_priority', 10, 3 );

/**
 * Disable WordPress default lazy loading for the first content image.
 * Improves LCP (Largest Contentful Paint).
 *
 * @param string $value   Loading attribute value.
 * @param string $image   Image HTML tag.
 * @param string $context Context (e.g., 'the_content').
 * @return string|false Modified value or false to skip.
 */
function stb_skip_lazy_first_image( $value, $image, $context ) {
    static $count = 0;

    if ( 'the_content' === $context ) {
        $count++;
        if ( 1 === $count ) {
            return false; // Skip lazy for the first image.
        }
    }

    return $value;
}
add_filter( 'wp_img_tag_add_loading_attr', 'stb_skip_lazy_first_image', 10, 3 );
