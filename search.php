<?php
/**
 * Search Results Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main">

        <header class="archive-header">
            <h1 class="archive-title">
                <?php printf( esc_html__( 'Search Results for: %s', 'smarttoolsblog' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?>
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>

            <div class="blog-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class( 'post-card' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__image">
                                <?php the_post_thumbnail( 'stb-thumbnail' ); ?>
                            </a>
                        <?php endif; ?>
                        <div class="post-card__content">
                            <span class="post-card__type">
                                <?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
                            </span>
                            <h2 class="post-card__title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <p class="post-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" aria-label="<?php esc_attr_e( 'Search results navigation', 'smarttoolsblog' ); ?>">
                <?php the_posts_pagination(); ?>
            </nav>

        <?php else : ?>
            <div class="no-results">
                <h2><?php esc_html_e( 'Nothing Found', 'smarttoolsblog' ); ?></h2>
                <p><?php esc_html_e( 'Sorry, no results were found. Please try a different search term.', 'smarttoolsblog' ); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
