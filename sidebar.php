<?php
/**
 * Sidebar Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="sidebar" class="sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'smarttoolsblog' ); ?>">
    <?php stb_ad_slot( 'sidebar' ); ?>
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
