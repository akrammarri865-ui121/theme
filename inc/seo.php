<?php
/**
 * SEO Functions - Schema JSON-LD, Open Graph, Twitter Cards, Breadcrumbs
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
        $description = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '' );
        $url         = get_permalink();
        $image       = get_the_post_thumbnail_url( $post->ID, 'stb-featured' );
        $site_name   = get_bloginfo( 'name' );
        $og_type     = ( 'tool' === get_post_type() ) ? 'website' : 'article';

        // Open Graph
        printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $og_type ) );
        printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
        printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
        printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
        printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $site_name ) );

        if ( $image ) {
            printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
            echo '<meta property="og:image:width" content="1200">' . "\n";
            echo '<meta property="og:image:height" content="630">' . "\n";
        }

        if ( 'article' === $og_type ) {
            printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( 'c' ) ) );
            printf( '<meta property="article:modified_time" content="%s">' . "\n", esc_attr( get_the_modified_date( 'c' ) ) );
            printf( '<meta property="article:author" content="%s">' . "\n", esc_attr( get_the_author() ) );
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
        printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( $title ) );
        echo '<meta name="twitter:card" content="summary">' . "\n";
    }
}
add_action( 'wp_head', 'stb_seo_meta', 5 );

/**
 * Output JSON-LD Schema markup.
 * Outputs multiple schema types as a @graph for richer structured data.
 */
function stb_schema_markup() {
    $graph = array();

    // Always include Organization schema.
    $org_schema = array(
        '@type' => 'Organization',
        '@id'   => home_url( '/#organization' ),
        'name'  => get_bloginfo( 'name' ),
        'url'   => home_url( '/' ),
    );

    $custom_logo_id = get_theme_mod( 'custom_logo' );
    if ( $custom_logo_id ) {
        $logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
        if ( $logo_url ) {
            $org_schema['logo'] = array(
                '@type'      => 'ImageObject',
                'url'        => $logo_url,
                'contentUrl' => $logo_url,
            );
        }
    }
    $graph[] = $org_schema;

    // WebSite schema (always).
    $website_schema = array(
        '@type'           => 'WebSite',
        '@id'             => home_url( '/#website' ),
        'name'            => get_bloginfo( 'name' ),
        'description'     => get_bloginfo( 'description' ),
        'url'             => home_url( '/' ),
        'publisher'       => array( '@id' => home_url( '/#organization' ) ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'        => 'EntryPoint',
                'urlTemplate'  => home_url( '/?s={search_term_string}' ),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
    $graph[] = $website_schema;

    // Page-specific schemas.
    if ( is_singular( 'post' ) ) {
        global $post;
        $description = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '' );

        $article_schema = array(
            '@type'            => 'Article',
            '@id'              => get_permalink() . '#article',
            'headline'         => get_the_title(),
            'description'      => $description,
            'url'              => get_permalink(),
            'datePublished'    => get_the_date( 'c' ),
            'dateModified'     => get_the_modified_date( 'c' ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id'   => get_permalink(),
            ),
            'author'           => array(
                '@type' => 'Person',
                'name'  => get_the_author(),
                'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
            ),
            'publisher'        => array( '@id' => home_url( '/#organization' ) ),
            'isPartOf'         => array( '@id' => home_url( '/#website' ) ),
            'wordCount'        => str_word_count( wp_strip_all_tags( $post->post_content ) ),
        );

        $image = get_the_post_thumbnail_url( $post->ID, 'stb-featured' );
        if ( $image ) {
            $article_schema['image'] = array(
                '@type'  => 'ImageObject',
                'url'    => $image,
                'width'  => 1200,
                'height' => 630,
            );
        }

        $graph[] = $article_schema;

    } elseif ( is_singular( 'tool' ) ) {
        global $post;
        $description = has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '' );
        $tool_type   = get_post_meta( get_the_ID(), '_stb_tool_type', true );
        $tool_rating = get_post_meta( get_the_ID(), '_stb_tool_rating', true );
        $tool_version = get_post_meta( get_the_ID(), '_stb_tool_version', true );

        $tool_schema = array(
            '@type'               => 'WebApplication',
            '@id'                 => get_permalink() . '#tool',
            'name'                => get_the_title(),
            'description'         => $description,
            'url'                 => get_permalink(),
            'applicationCategory' => $tool_type ? ucfirst( str_replace( '-', ' ', $tool_type ) ) : 'UtilityApplication',
            'operatingSystem'     => 'All',
            'offers'              => array(
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'USD',
            ),
            'publisher'           => array( '@id' => home_url( '/#organization' ) ),
            'isPartOf'            => array( '@id' => home_url( '/#website' ) ),
        );

        if ( $tool_version ) {
            $tool_schema['softwareVersion'] = sanitize_text_field( $tool_version );
        }

        if ( $tool_rating ) {
            $tool_schema['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => floatval( $tool_rating ),
                'bestRating'  => 5,
                'worstRating' => 1,
                'ratingCount' => 1,
            );
        }

        $image = get_the_post_thumbnail_url( $post->ID, 'stb-featured' );
        if ( $image ) {
            $tool_schema['image'] = $image;
        }

        $graph[] = $tool_schema;

    } elseif ( is_singular( 'page' ) ) {
        $page_schema = array(
            '@type'            => 'WebPage',
            '@id'              => get_permalink() . '#webpage',
            'name'             => get_the_title(),
            'url'              => get_permalink(),
            'datePublished'    => get_the_date( 'c' ),
            'dateModified'     => get_the_modified_date( 'c' ),
            'isPartOf'         => array( '@id' => home_url( '/#website' ) ),
        );
        $graph[] = $page_schema;
    }

    // Output the graph.
    if ( ! empty( $graph ) ) {
        $schema_output = array(
            '@context' => 'https://schema.org',
            '@graph'   => $graph,
        );

        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $schema_output, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT )
        );
    }
}
add_action( 'wp_head', 'stb_schema_markup', 6 );

/**
 * Output proper JSON-LD BreadcrumbList schema.
 */
function stb_breadcrumb_schema() {
    if ( is_front_page() ) {
        return;
    }

    $items = array();
    $position = 1;

    // Home
    $items[] = array(
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => __( 'Home', 'smarttoolsblog' ),
        'item'     => home_url( '/' ),
    );

    if ( is_singular( 'post' ) ) {
        $categories = get_the_category();
        if ( ! empty( $categories ) ) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $categories[0]->name,
                'item'     => get_category_link( $categories[0]->term_id ),
            );
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_singular( 'tool' ) ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => __( 'Tools', 'smarttoolsblog' ),
            'item'     => get_post_type_archive_link( 'tool' ),
        );
        $tool_cats = get_the_terms( get_the_ID(), 'tool_category' );
        if ( ! empty( $tool_cats ) && ! is_wp_error( $tool_cats ) ) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position++,
                'name'     => $tool_cats[0]->name,
                'item'     => get_term_link( $tool_cats[0] ),
            );
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_category() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => single_cat_title( '', false ),
            'item'     => get_category_link( get_queried_object_id() ),
        );
    } elseif ( is_tag() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => single_tag_title( '', false ),
            'item'     => get_tag_link( get_queried_object_id() ),
        );
    } elseif ( is_post_type_archive( 'tool' ) ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => __( 'Tools', 'smarttoolsblog' ),
            'item'     => get_post_type_archive_link( 'tool' ),
        );
    } elseif ( is_page() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif ( is_search() ) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => __( 'Search Results', 'smarttoolsblog' ),
            'item'     => get_search_link(),
        );
    }

    if ( count( $items ) > 1 ) {
        $breadcrumb_schema = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $items,
        );

        printf(
            '<script type="application/ld+json">%s</script>' . "\n",
            wp_json_encode( $breadcrumb_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
        );
    }
}
add_action( 'wp_head', 'stb_breadcrumb_schema', 7 );

/**
 * Output visual breadcrumbs navigation.
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
        // Show parent pages.
        global $post;
        if ( $post->post_parent ) {
            $ancestors = get_post_ancestors( $post->ID );
            $ancestors = array_reverse( $ancestors );
            foreach ( $ancestors as $ancestor ) {
                $items[] = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( get_permalink( $ancestor ) ),
                    esc_html( get_the_title( $ancestor ) )
                );
            }
        }
        $items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_404() ) {
        $items[] = '<span aria-current="page">' . esc_html__( '404 - Page Not Found', 'smarttoolsblog' ) . '</span>';
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'smarttoolsblog' ) . '">';
    echo '<ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">';
    foreach ( $items as $index => $item ) {
        printf(
            '<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">%s%s<meta itemprop="position" content="%d"></li>',
            $item,
            ( $index < count( $items ) - 1 ) ? $separator : '',
            $index + 1
        );
    }
    echo '</ol>';
    echo '</nav>';
}
