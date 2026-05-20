<?php
/**
 * SmartToolsBlog Theme Functions
 *
 * @package SmartToolsBlog
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Theme constants
define( 'STB_VERSION', '1.0.0' );
define( 'STB_DIR', get_template_directory() );
define( 'STB_URI', get_template_directory_uri() );

// Core includes
require_once STB_DIR . '/inc/setup.php';
require_once STB_DIR . '/inc/enqueue.php';
require_once STB_DIR . '/inc/seo.php';
require_once STB_DIR . '/inc/custom-post-types.php';
require_once STB_DIR . '/inc/image-optimization.php';
require_once STB_DIR . '/inc/theme-options.php';
require_once STB_DIR . '/inc/legal-pages.php';

/**
 * Get estimated reading time for a post.
 *
 * @param int $post_id Post ID.
 * @return int Minutes to read.
 */
function stb_reading_time( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $content  = get_post_field( 'post_content', $post_id );
    $words    = str_word_count( wp_strip_all_tags( $content ) );
    $minutes  = max( 1, ceil( $words / 250 ) );
    return $minutes;
}

/**
 * Get related posts by category.
 *
 * @param int $post_id Post ID.
 * @param int $count   Number of posts.
 * @return WP_Query
 */
function stb_related_posts( $post_id = null, $count = 3 ) {
    $post_id    = $post_id ?: get_the_ID();
    $categories = wp_get_post_categories( $post_id, array( 'fields' => 'ids' ) );

    $args = array(
        'post_type'           => 'post',
        'posts_per_page'      => $count,
        'post__not_in'        => array( $post_id ),
        'category__in'        => $categories,
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
    );

    return new WP_Query( $args );
}

/**
 * Render ad slot.
 *
 * @param string $location Ad slot location.
 */
function stb_ad_slot( $location ) {
    $ad_code = get_theme_mod( "stb_ad_{$location}", '' );
    if ( ! empty( $ad_code ) ) {
        printf( '<div class="stb-ad stb-ad--%s">%s</div>', esc_attr( $location ), $ad_code );
    }
}
