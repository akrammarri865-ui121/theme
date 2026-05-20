<?php
/**
 * Template Name: Tools Archive Page
 * Description: Custom page template for displaying all tools in a grid layout.
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main content-main--full">

        <header class="tools-archive-header">
            <h1 class="tools-archive-header__title">
                <?php echo esc_html( get_the_title() ); ?>
            </h1>
            <?php if ( has_excerpt() ) : ?>
                <p class="tools-archive-header__description">
                    <?php echo esc_html( get_the_excerpt() ); ?>
                </p>
            <?php endif; ?>
        </header>

        <?php
        // Tool category filter
        $tool_categories = get_terms( array(
            'taxonomy'   => 'tool_category',
            'hide_empty' => true,
        ) );

        if ( ! empty( $tool_categories ) && ! is_wp_error( $tool_categories ) ) :
        ?>
        <nav class="tools-filter" aria-label="<?php esc_attr_e( 'Filter tools by category', 'smarttoolsblog' ); ?>">
            <button class="tools-filter__btn active" data-filter="all">
                <?php esc_html_e( 'All Tools', 'smarttoolsblog' ); ?>
            </button>
            <?php foreach ( $tool_categories as $tcat ) : ?>
                <button class="tools-filter__btn" data-filter="<?php echo esc_attr( $tcat->slug ); ?>">
                    <?php echo esc_html( $tcat->name ); ?>
                </button>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <?php stb_ad_slot( 'header' ); ?>

        <?php
        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
        $tools_query = new WP_Query( array(
            'post_type'      => 'tool',
            'posts_per_page' => 12,
            'paged'          => $paged,
            'orderby'        => 'date',
            'order'          => 'DESC',
        ) );

        if ( $tools_query->have_posts() ) :
        ?>
        <div class="tools-grid tools-grid--page">
            <?php while ( $tools_query->have_posts() ) : $tools_query->the_post(); ?>
                <?php
                $tool_terms = get_the_terms( get_the_ID(), 'tool_category' );
                $term_slugs = '';
                if ( ! empty( $tool_terms ) && ! is_wp_error( $tool_terms ) ) {
                    $term_slugs = implode( ' ', wp_list_pluck( $tool_terms, 'slug' ) );
                }
                $cta_url  = get_post_meta( get_the_ID(), '_stb_tool_cta_url', true );
                $cta_text = get_post_meta( get_the_ID(), '_stb_tool_cta_text', true );
                ?>
                <article <?php post_class( 'tool-card' ); ?> data-categories="<?php echo esc_attr( $term_slugs ); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="tool-card__image">
                            <?php the_post_thumbnail( 'stb-card' ); ?>
                        </a>
                    <?php else : ?>
                        <a href="<?php the_permalink(); ?>" class="tool-card__image tool-card__image--placeholder">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                    <div class="tool-card__content">
                        <?php if ( ! empty( $tool_terms ) && ! is_wp_error( $tool_terms ) ) : ?>
                            <span class="tool-card__category">
                                <?php echo esc_html( $tool_terms[0]->name ); ?>
                            </span>
                        <?php endif; ?>
                        <h2 class="tool-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <?php if ( has_excerpt() ) : ?>
                            <p class="tool-card__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <?php endif; ?>
                        <div class="tool-card__actions">
                            <a href="<?php the_permalink(); ?>" class="tool-card__btn">
                                <?php esc_html_e( 'Use Tool', 'smarttoolsblog' ); ?>
                            </a>
                            <?php if ( $cta_url ) : ?>
                                <a href="<?php echo esc_url( $cta_url ); ?>" class="tool-card__btn tool-card__btn--outline" target="_blank" rel="noopener">
                                    <?php echo esc_html( $cta_text ?: __( 'Visit', 'smarttoolsblog' ) ); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <nav class="pagination" aria-label="<?php esc_attr_e( 'Tools navigation', 'smarttoolsblog' ); ?>">
            <?php
            echo paginate_links( array(
                'total'   => $tools_query->max_num_pages,
                'current' => $paged,
            ) );
            ?>
        </nav>
        <?php
        else :
        ?>
            <div class="no-results">
                <h2><?php esc_html_e( 'No Tools Found', 'smarttoolsblog' ); ?></h2>
                <p><?php esc_html_e( 'Check back soon for new tools and calculators.', 'smarttoolsblog' ); ?></p>
            </div>
        <?php
        endif;
        wp_reset_postdata();
        ?>

    </div>
</div>

<?php get_footer(); ?>
