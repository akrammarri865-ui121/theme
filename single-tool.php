<?php
/**
 * Single Tool Template - Visually distinct from blog posts
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main content-main--full">

        <?php while ( have_posts() ) : the_post(); ?>

            <article <?php post_class( 'single-tool' ); ?> itemscope itemtype="https://schema.org/WebApplication">

                <header class="single-tool__header">
                    <?php
                    $tool_cats = get_the_terms( get_the_ID(), 'tool_category' );
                    if ( ! empty( $tool_cats ) && ! is_wp_error( $tool_cats ) ) :
                    ?>
                        <div class="single-tool__categories">
                            <?php foreach ( $tool_cats as $tcat ) : ?>
                                <span class="tool-category-badge">
                                    <?php echo esc_html( $tcat->name ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h1 class="single-tool__title" itemprop="name"><?php the_title(); ?></h1>

                    <?php if ( has_excerpt() ) : ?>
                        <p class="single-tool__description" itemprop="description">
                            <?php echo esc_html( get_the_excerpt() ); ?>
                        </p>
                    <?php endif; ?>

                    <?php
                    $cta_url   = get_post_meta( get_the_ID(), '_stb_tool_cta_url', true );
                    $cta_text  = get_post_meta( get_the_ID(), '_stb_tool_cta_text', true );
                    if ( $cta_url ) :
                    ?>
                        <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--primary btn--lg single-tool__cta" target="_blank" rel="noopener noreferrer">
                            <?php echo esc_html( $cta_text ?: __( 'Try This Tool', 'smarttoolsblog' ) ); ?>
                        </a>
                    <?php endif; ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <figure class="single-tool__featured-image">
                        <?php the_post_thumbnail( 'stb-featured', array(
                            'itemprop'      => 'image',
                            'fetchpriority' => 'high',
                        ) ); ?>
                    </figure>
                <?php endif; ?>

                <?php stb_ad_slot( 'header' ); ?>

                <div class="single-tool__content" itemprop="description">
                    <?php the_content(); ?>
                </div>

                <?php stb_ad_slot( 'article_end' ); ?>

                <?php
                // Tool meta info
                $tool_version = get_post_meta( get_the_ID(), '_stb_tool_version', true );
                $tool_rating  = get_post_meta( get_the_ID(), '_stb_tool_rating', true );
                ?>
                <?php if ( $tool_version || $tool_rating ) : ?>
                    <aside class="single-tool__meta-box">
                        <h3><?php esc_html_e( 'Tool Details', 'smarttoolsblog' ); ?></h3>
                        <dl class="single-tool__details">
                            <?php if ( $tool_version ) : ?>
                                <dt><?php esc_html_e( 'Version', 'smarttoolsblog' ); ?></dt>
                                <dd><?php echo esc_html( $tool_version ); ?></dd>
                            <?php endif; ?>
                            <?php if ( $tool_rating ) : ?>
                                <dt><?php esc_html_e( 'Rating', 'smarttoolsblog' ); ?></dt>
                                <dd><?php echo esc_html( $tool_rating ); ?>/5</dd>
                            <?php endif; ?>
                            <dt><?php esc_html_e( 'Updated', 'smarttoolsblog' ); ?></dt>
                            <dd>
                                <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>">
                                    <?php echo esc_html( get_the_modified_date() ); ?>
                                </time>
                            </dd>
                        </dl>
                    </aside>
                <?php endif; ?>

            </article>

            <!-- Related Tools -->
            <?php
            $related_tools = new WP_Query( array(
                'post_type'      => 'tool',
                'posts_per_page' => 3,
                'post__not_in'   => array( get_the_ID() ),
                'no_found_rows'  => true,
                'tax_query'      => ! empty( $tool_cats ) ? array(
                    array(
                        'taxonomy' => 'tool_category',
                        'field'    => 'term_id',
                        'terms'    => wp_list_pluck( $tool_cats, 'term_id' ),
                    ),
                ) : array(),
            ) );

            if ( $related_tools->have_posts() ) :
            ?>
            <section class="related-tools" aria-label="<?php esc_attr_e( 'Related Tools', 'smarttoolsblog' ); ?>">
                <h2 class="section-title"><?php esc_html_e( 'Related Tools', 'smarttoolsblog' ); ?></h2>
                <div class="tools-grid">
                    <?php while ( $related_tools->have_posts() ) : $related_tools->the_post(); ?>
                        <article <?php post_class( 'tool-card' ); ?>>
                            <?php if ( has_post_thumbnail() ) : ?>
                                <a href="<?php the_permalink(); ?>" class="tool-card__image">
                                    <?php the_post_thumbnail( 'stb-card' ); ?>
                                </a>
                            <?php endif; ?>
                            <div class="tool-card__content">
                                <h3 class="tool-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="tool-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="tool-card__btn">
                                    <?php esc_html_e( 'Use Tool', 'smarttoolsblog' ); ?>
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php
            wp_reset_postdata();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer(); ?>
