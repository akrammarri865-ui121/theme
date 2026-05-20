<?php
/**
 * SEO Functions - Schema, Open Graph, Breadcrumbs
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output Open Graph and Twitter Card meta tags.
 */
function stb_seo_meta() {
    if ( is_singular() ) {
        global $post;
        $title       = get_the_title();
        $description = has_excerpt() ? get_the_excerpt() : wp_trim_words( $post->post_content, 30 );
        $url         = get_permalink();
        $image       = get_the_post_thumbnail_url( $post->ID, 'stb-featured' );
        $site_name   = get_bloginfo( 'name' );

        // Open Graph
        echo '<meta property="og:type" content="article">' . "\n";
        printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
        printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
        printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
        printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site_name ) );

        if ( $image ) {
            printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }

        // Twitter Card
        echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
        printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
        printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );

        if ( $image ) {
            printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
        }
    } elseif ( is_front_page() ) {
        $title       = get_bloginfo( 'name' );
        $description = get_bloginfo( 'description' );
        $url         = home_url( '/' );

        echo '<meta property="og:type" content="website">' . "\n";
        printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
        printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
        printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }
}
add_action( 'wp_head', 'stb_seo_meta', 5 );

/**
 * Output JSON-LD Schema markup.
 */
function stb_schema_markup() {
    $schema = array();

    if ( is_singular( 'post' ) ) {
        global $post;
        $schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => has_excerpt() ? get_the_excerpt() : wp_trim_words( $post->post_content, 30 ),
            'url'           => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author'        => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
            ),
            'publisher'     => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
            ),
        );

        $image = get_the_post_thumbnail_url( $post->ID, 'stb-featured' );
        if ( $image ) {
            $schema['image'] = $image;
        }
    } elseif ( is_singular( 'tool' ) ) {
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'WebApplication',
            'name'        => get_the_title(),
            'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 30 ),
            'url'         => get_permalink(),
            'applicationCategory' => 'UtilityApplication',
        );
    } elseif ( is_front_page() ) {
        $schema = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'WebSite',
            'name'        => get_bloginfo( 'name' ),
            'description' => get_bloginfo( 'description' ),
            'url'         => home_url( '/' ),
            'potentialAction' => array(
                '@type'       => 'SearchAction',
                'target'      => home_url( '/?s={search_term_string}' ),
                'query-input' => 'required name=search_term_string',
            ),
        );
    }

    if ( ! empty( $schema ) ) {
        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        );
    }
}
add_action( 'wp_head', 'stb_schema_markup', 6 );

/**
 * Output breadcrumbs.
 */
function stb_breadcrumbs() {
    if ( is_front_page() ) {
        return;
    }

    $separator = '<span class="breadcrumb-sep" aria-hidden="true">/</span>';
    $items     = array();

    $items[] = sprintf(
        '<a href="%s">%s</a>',
        esc_url( home_url( '/' ) ),
        esc_html__( 'Home', 'smarttoolsblog' )
    );

    if ( is_singular( 'post' ) ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $items[] = sprintf(
                '<a href="%s">%s</a>',
                esc_url( get_category_link( $categories[0]->term_id ) ),
                esc_html( $categories[0]->name )
            );
        }
        $items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_singular( 'tool' ) ) {
        $items[] = sprintf(
            '<a href="%s">%s</a>',
            esc_url( get_post_type_archive_link( 'tool' ) ),
            esc_html__( 'Tools', 'smarttoolsblog' )
        );
        $items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_category() ) {
        $items[] = '<span aria-current="page">' . esc_html( single_cat_title( '', false ) ) . '</span>';
    } elseif ( is_tag() ) {
        $items[] = '<span aria-current="page">' . esc_html( single_tag_title( '', false ) ) . '</span>';
    } elseif ( is_search() ) {
        $items[] = '<span aria-current="page">' . esc_html__( 'Search Results', 'smarttoolsblog' ) . '</span>';
    } elseif ( is_post_type_archive( 'tool' ) ) {
        $items[] = '<span aria-current="page">' . esc_html__( 'Tools', 'smarttoolsblog' ) . '</span>';
    } elseif ( is_page() ) {
        $items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_404() ) {
        $items[] = '<span aria-current="page">' . esc_html__( '404', 'smarttoolsblog' ) . '</span>';
    }

    // Schema breadcrumb markup
    $schema_items = array();
    foreach ( $items as $index => $item ) {
        $schema_items[] = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'item'     => $item,
        );
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'smarttoolsblog' ) . '">';
    echo implode( $separator, $items );
    echo '</nav>';
}
