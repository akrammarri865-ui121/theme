<?php
/**
 * Main Index Template (fallback)
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main">

        <?php if ( have_posts() ) : ?>

            <div class="blog-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__image">
                                <?php the_post_thumbnail( 'stb-card' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="post-card__content">
                            <span class="post-card__category">
                                <?php echo esc_html( get_the_category()[0]->name ?? '' ); ?>
                            </span>
                            <h3 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="post-card__meta">
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                                <span class="post-card__reading-time">
                                    <?php printf( esc_html__( '%d min read', 'smarttoolsblog' ), stb_reading_time() ); ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'smarttoolsblog' ); ?>">
                <?php the_posts_pagination(); ?>
            </nav>

        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e( 'Nothing Found', 'smarttoolsblog' ); ?></h2>
                <p><?php esc_html_e( 'It seems we can\'t find what you\'re looking for.', 'smarttoolsblog' ); ?></p>
            </div>
        <?php endif; ?>

    </div>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
