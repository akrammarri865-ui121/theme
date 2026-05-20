<?php
/**
 * Header Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
    <?php esc_html_e( 'Skip to content', 'smarttoolsblog' ); ?>
</a>

<header id="site-header" class="site-header" role="banner">
    <?php stb_ad_slot( 'header' ); ?>

    <div class="container header-inner">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-title" rel="home">
                    <?php bloginfo( 'name' ); ?>
                </a>
            <?php endif; ?>
        </div>

        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'smarttoolsblog' ); ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => false,
                'depth'          => 2,
            ) );
            ?>
        </nav>

        <div class="header-actions">
            <button class="theme-toggle" id="theme-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'smarttoolsblog' ); ?>">
                <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
                <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>

            <button class="mobile-menu-toggle" id="mobile-menu-toggle" aria-label="<?php esc_attr_e( 'Toggle menu', 'smarttoolsblog' ); ?>" aria-expanded="false">
                <span class="hamburger"></span>
            </button>
        </div>
    </div>

    <nav class="mobile-navigation" id="mobile-navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'smarttoolsblog' ); ?>" hidden>
        <?php
        wp_nav_menu( array(
            'theme_location' => 'mobile',
            'menu_id'        => 'mobile-menu',
            'container'      => false,
            'fallback_cb'    => false,
        ) );
        ?>
    </nav>
</header>

<?php if ( ! is_front_page() ) : ?>
    <div class="breadcrumbs-wrapper">
        <div class="container">
            <?php stb_breadcrumbs(); ?>
        </div>
    </div>
<?php endif; ?>

<main id="main-content" class="site-main" role="main">
