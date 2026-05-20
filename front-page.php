<?php
/**
 * Front Page Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container">

    <?php
    // Featured Posts Section
    $featured_cat = get_theme_mod( 'stb_featured_category', '' );
    if ( $featured_cat ) :
        $featured_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'cat'            => absint( $featured_cat ),
            'no_found_rows'  => true,
        ) );

        if ( $featured_query->have_posts() ) :
    ?>
    <section class="featured-posts" aria-label="<?php esc_attr_e( 'Featured Posts', 'smarttoolsblog' ); ?>">
        <h2 class="section-title"><?php esc_html_e( 'Featured', 'smarttoolsblog' ); ?></h2>
        <div class="featured-grid">
            <?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
                <article class="featured-card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="featured-card__image">
                            <?php the_post_thumbnail( 'stb-card', array( 'loading' => 'eager' ) ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="featured-card__content">
                        <span class="featured-card__category">
                            <?php echo esc_html( get_the_category()[0]->name ?? '' ); ?>
                        </span>
                        <h3 class="featured-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <time class="featured-card__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
        endif;
        wp_reset_postdata();
    endif;
    ?>

    <!-- Blog Grid -->
    <section class="blog-grid-section" aria-label="<?php esc_attr_e( 'Latest Posts', 'smarttoolsblog' ); ?>">
        <h2 class="section-title"><?php esc_html_e( 'Latest Posts', 'smarttoolsblog' ); ?></h2>

        <?php
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $posts_count = get_theme_mod( 'stb_homepage_posts', 9 );

        $blog_query = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => $posts_count,
            'paged'          => $paged,
        ) );

        if ( $blog_query->have_posts() ) :
        ?>
        <div class="blog-grid">
            <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
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
            <?php
            echo paginate_links( array(
                'total'   => $blog_query->max_num_pages,
                'current' => $paged,
            ) );
            ?>
        </nav>
        <?php
        endif;
        wp_reset_postdata();
        ?>
    </section>

</div>

<?php get_footer(); ?>
