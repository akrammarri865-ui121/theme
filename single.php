<?php
/**
 * Single Post Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="reading-progress" aria-hidden="true">
    <div class="reading-progress__bar" id="reading-progress-bar"></div>
</div>

<div class="container content-area">
    <div class="content-main">

        <?php while ( have_posts() ) : the_post(); ?>

            <article <?php post_class( 'single-post' ); ?> itemscope itemtype="https://schema.org/Article">

                <header class="single-post__header">
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) :
                    ?>
                        <div class="single-post__categories">
                            <?php foreach ( $categories as $cat ) : ?>
                                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="category-badge">
                                    <?php echo esc_html( $cat->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="single-post__title" itemprop="headline"><?php the_title(); ?></h1>

                    <div class="single-post__meta">
                        <span class="single-post__author" itemprop="author">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>
                            <?php the_author(); ?>
                        </span>
                        <time class="single-post__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <span class="single-post__reading-time">
                            <?php printf( esc_html__( '%d min read', 'smarttoolsblog' ), stb_reading_time() ); ?>
                        </span>
                    </div>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="single-post__featured-image">
                        <?php the_post_thumbnail( 'stb-featured', array( 'itemprop' => 'image', 'fetchpriority' => 'high' ) ); ?>
                    </figure>
                <?php endif; ?>

                <div class="single-post__content" itemprop="articleBody">
                    <?php
                    // Split content for mid-article ad
                    $content = apply_filters( 'the_content', get_the_content() );
                    $parts   = explode( '</p>', $content );
                    $midpoint = (int) ceil( count( $parts ) / 2 );

                    for ( $i = 0; $i < count( $parts ); $i++ ) {
                        echo $parts[ $i ] . '</p>';
                        if ( $i === $midpoint ) {
                            stb_ad_slot( 'article_middle' );
                        }
                    }
                    ?>
                </div>

                <?php stb_ad_slot( 'article_end' ); ?>

                <footer class="single-post__footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) :
                    ?>
                        <div class="single-post__tags">
                            <?php foreach ( $tags as $tag ) : ?>
                                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag-badge">
                                    #<?php echo esc_html( $tag->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </footer>

                <!-- Author Box -->
                <aside class="author-box">
                    <div class="author-box__avatar">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
                    </div>
                    <div class="author-box__info">
                        <h3 class="author-box__name">
                            <?php the_author(); ?>
                        </h3>
                        <?php if ( get_the_author_meta( 'description' ) ) : ?>
                            <p class="author-box__bio">
                                <?php echo esc_html( get_the_author_meta( 'description' ) ); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </aside>

            </article>

            <!-- Related Posts -->
            <?php
            $related = stb_related_posts();
            if ( $related->have_posts() ) :
            ?>
            <section class="related-posts" aria-label="<?php esc_attr_e( 'Related Posts', 'smarttoolsblog' ); ?>">
                <h2 class="section-title"><?php esc_html_e( 'Related Posts', 'smarttoolsblog' ); ?></h2>
                <div class="related-posts__grid">
                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <article class="post-card post-card--small">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="post-card__image">
                                    <?php the_post_thumbnail( 'stb-thumbnail' ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="post-card__content">
                                <h3 class="post-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </time>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php
            wp_reset_postdata();
            endif;
            ?>

            <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>

    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>
