<?php
/**
 * Footer Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;
?>
</main><!-- #main-content -->

<footer id="site-footer" class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-widgets">
            <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                <div class="footer-col">
                    <?php dynamic_sidebar( 'footer-1' ); ?>
                </div>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <div class="footer-col">
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                </div>
            <?php endif; ?>

            <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-col">
                    <?php dynamic_sidebar( 'footer-3' ); ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ( has_nav_menu( 'footer' ) ) : ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'smarttoolsblog' ); ?>">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                ) );
                ?>
            </nav>
        <?php endif; ?>

        <div class="footer-bottom">
            <p class="copyright">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                <?php esc_html_e( 'All rights reserved.', 'smarttoolsblog' ); ?>
            </p>
        </div>
    </div>
</footer>

<?php stb_ad_slot( 'mobile_sticky' ); ?>

<?php wp_footer(); ?>
</body>
</html>
