<?php
/**
 * Archive Template (categories, tags, date, tools)
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main">

        <header class="archive-header">
            <?php
            the_archive_title( '<h1 class="archive-title">', '</h1>' );
            the_archive_description( '<div class="archive-description">', '</div>' );
            ?>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="<?php echo is_post_type_archive( 'tool' ) ? 'tools-grid' : 'blog-grid'; ?>">
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php if ( is_post_type_archive( 'tool' ) || is_tax( 'tool_category' ) ) : ?>
                        <!-- Tool Card -->
                        <article <?php post_class( 'tool-card' ); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="tool-card__image">
                                    <?php the_post_thumbnail( 'stb-card' ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="tool-card__content">
                                <h2 class="tool-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="tool-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="tool-card__btn">
                                    <?php esc_html_e( 'Use Tool', 'smarttoolsblog' ); ?>
                                </a>
                            </div>
                        </article>
                    <?php else : ?>
                        <!-- Blog Post Card -->
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
                                <h2 class="post-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
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
                    <?php endif; ?>

                <?php endwhile; ?>
            </div>

            <nav class="pagination" aria-label="<?php esc_attr_e( 'Posts navigation', 'smarttoolsblog' ); ?>">
                <?php the_posts_pagination(); ?>
            </nav>

        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e( 'Nothing Found', 'smarttoolsblog' ); ?></h2>
                <p><?php esc_html_e( 'No posts matched your criteria.', 'smarttoolsblog' ); ?></p>
            </div>
        <?php endif; ?>

    </div>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
