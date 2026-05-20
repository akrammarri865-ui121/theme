<?php
/**
 * 404 Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main content-main--full">

        <section class="error-404">
            <h1 class="error-404__title">404</h1>
            <h2 class="error-404__subtitle"><?php esc_html_e( 'Page Not Found', 'smarttoolsblog' ); ?></h2>
            <p class="error-404__message">
                <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'smarttoolsblog' ); ?>
            </p>
            <div class="error-404__search">
                <?php get_search_form(); ?>
            </div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
                <?php esc_html_e( 'Back to Homepage', 'smarttoolsblog' ); ?>
            </a>
        </section>

    </div>
</div>

<?php get_footer(); ?>
