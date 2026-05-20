<?php
/**
 * Theme Options - Customizer Settings, Ad Slots, Security
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function stb_customize_register( $wp_customize ) {

    // === Ad Slots Section ===
    $wp_customize->add_section( 'stb_ads_section', array(
        'title'       => __( 'Ad Slots (AdSense)', 'smarttoolsblog' ),
        'description' => __( 'Configure ad placements. Paste your AdSense or ad network code into each slot.', 'smarttoolsblog' ),
        'priority'    => 90,
    ) );

    $ad_slots = array(
        'header'         => array(
            'label' => __( 'Header Ad', 'smarttoolsblog' ),
            'desc'  => __( 'Displays below the site header on all pages.', 'smarttoolsblog' ),
        ),
        'sidebar'        => array(
            'label' => __( 'Sidebar Ad', 'smarttoolsblog' ),
            'desc'  => __( 'Displays at the top of the sidebar widget area.', 'smarttoolsblog' ),
        ),
        'article_middle' => array(
            'label' => __( 'In-Content Ad', 'smarttoolsblog' ),
            'desc'  => __( 'Displays in the middle of article content.', 'smarttoolsblog' ),
        ),
        'article_end'    => array(
            'label' => __( 'After Post Ad', 'smarttoolsblog' ),
            'desc'  => __( 'Displays after the article content, before related posts.', 'smarttoolsblog' ),
        ),
        'mobile_sticky'  => array(
            'label' => __( 'Mobile Sticky Ad', 'smarttoolsblog' ),
            'desc'  => __( 'Fixed ad at the bottom of mobile screens. Hidden on desktop.', 'smarttoolsblog' ),
        ),
    );

    foreach ( $ad_slots as $slot => $config ) {
        $wp_customize->add_setting( "stb_ad_{$slot}", array(
            'default'           => '',
            'sanitize_callback' => 'stb_sanitize_ad_code',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( "stb_ad_{$slot}", array(
            'label'       => $config['label'],
            'description' => $config['desc'],
            'section'     => 'stb_ads_section',
            'type'        => 'textarea',
        ) );
    }

    // === Theme Options Section ===
    $wp_customize->add_section( 'stb_theme_options', array(
        'title'       => __( 'SmartToolsBlog Options', 'smarttoolsblog' ),
        'description' => __( 'General theme settings and configuration.', 'smarttoolsblog' ),
        'priority'    => 30,
    ) );

    // Dark mode default
    $wp_customize->add_setting( 'stb_default_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'stb_default_dark_mode', array(
        'label'       => __( 'Enable Dark Mode by Default', 'smarttoolsblog' ),
        'description' => __( 'When checked, the theme loads in dark mode for first-time visitors.', 'smarttoolsblog' ),
        'section'     => 'stb_theme_options',
        'type'        => 'checkbox',
    ) );

    // Featured posts category
    $wp_customize->add_setting( 'stb_featured_category', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'stb_featured_category', array(
        'label'       => __( 'Featured Posts Category', 'smarttoolsblog' ),
        'description' => __( 'Select a category for the featured section on the homepage.', 'smarttoolsblog' ),
        'section'     => 'stb_theme_options',
        'type'        => 'select',
        'choices'     => stb_get_category_choices(),
    ) );

    // Posts per page on homepage grid
    $wp_customize->add_setting( 'stb_homepage_posts', array(
        'default'           => 9,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'stb_homepage_posts', array(
        'label'       => __( 'Homepage Posts Count', 'smarttoolsblog' ),
        'description' => __( 'Number of posts shown in the homepage grid.', 'smarttoolsblog' ),
        'section'     => 'stb_theme_options',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 3, 'max' => 24, 'step' => 3 ),
    ) );

    // Google Fonts URL
    $wp_customize->add_setting( 'stb_google_font_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( 'stb_google_font_url', array(
        'label'       => __( 'Google Fonts URL', 'smarttoolsblog' ),
        'description' => __( 'Paste a Google Fonts embed URL. Leave empty to use system fonts (recommended for speed).', 'smarttoolsblog' ),
        'section'     => 'stb_theme_options',
        'type'        => 'url',
    ) );

    // == Social Media Section ==
    $wp_customize->add_section( 'stb_social_section', array(
        'title'    => __( 'Social Media', 'smarttoolsblog' ),
        'priority' => 35,
    ) );

    $social_networks = array(
        'twitter'   => __( 'Twitter/X Username', 'smarttoolsblog' ),
        'facebook'  => __( 'Facebook Page URL', 'smarttoolsblog' ),
        'instagram' => __( 'Instagram URL', 'smarttoolsblog' ),
        'youtube'   => __( 'YouTube Channel URL', 'smarttoolsblog' ),
    );

    foreach ( $social_networks as $network => $label ) {
        $sanitize = ( 'twitter' === $network ) ? 'sanitize_text_field' : 'esc_url_raw';

        $wp_customize->add_setting( "stb_social_{$network}", array(
            'default'           => '',
            'sanitize_callback' => $sanitize,
        ) );

        $wp_customize->add_control( "stb_social_{$network}", array(
            'label'   => $label,
            'section' => 'stb_social_section',
            'type'    => ( 'twitter' === $network ) ? 'text' : 'url',
        ) );
    }
}
add_action( 'customize_register', 'stb_customize_register' );

/**
 * Get categories as choices for Customizer dropdown.
 *
 * @return array Category ID => Name pairs.
 */
function stb_get_category_choices() {
    $choices = array( 0 => __( '— Select Category —', 'smarttoolsblog' ) );
    $categories = get_categories( array( 'hide_empty' => false ) );

    foreach ( $categories as $category ) {
        $choices[ $category->term_id ] = esc_html( $category->name );
    }

    return $choices;
}

/**
 * Sanitize ad code - restricted to users with unfiltered_html capability.
 * Uses wp_kses with allowed ad-related HTML tags and attributes.
 *
 * @param string $input Raw ad code.
 * @return string Sanitized ad code.
 */
function stb_sanitize_ad_code( $input ) {
    // Only users with unfiltered_html capability can save raw ad code.
    if ( ! current_user_can( 'unfiltered_html' ) ) {
        return wp_kses_post( $input );
    }

    // Allowed HTML for ad code (scripts, iframes, ins, etc.)
    $allowed_html = array(
        'script' => array(
            'async'           => true,
            'src'             => true,
            'type'            => true,
            'crossorigin'     => true,
            'data-ad-client'  => true,
            'data-ad-slot'    => true,
            'data-ad-format'  => true,
            'data-full-width-responsive' => true,
        ),
        'ins' => array(
            'class'           => true,
            'style'           => true,
            'data-ad-client'  => true,
            'data-ad-slot'    => true,
            'data-ad-format'  => true,
            'data-full-width-responsive' => true,
            'data-ad-layout'  => true,
            'data-ad-layout-key' => true,
        ),
        'iframe' => array(
            'src'             => true,
            'width'           => true,
            'height'          => true,
            'frameborder'     => true,
            'scrolling'       => true,
            'style'           => true,
            'loading'         => true,
            'allow'           => true,
            'allowfullscreen' => true,
        ),
        'div' => array(
            'id'    => true,
            'class' => true,
            'style' => true,
        ),
        'a' => array(
            'href'   => true,
            'target' => true,
            'rel'    => true,
            'class'  => true,
        ),
        'img' => array(
            'src'     => true,
            'alt'     => true,
            'width'   => true,
            'height'  => true,
            'loading' => true,
            'class'   => true,
        ),
    );

    return wp_kses( $input, $allowed_html );
}

/**
 * Add theme support info panel to Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function stb_customize_info_panel( $wp_customize ) {
    $wp_customize->add_section( 'stb_theme_info', array(
        'title'       => __( 'Theme Info', 'smarttoolsblog' ),
        'description' => sprintf(
            '<p><strong>SmartToolsBlog</strong> v%s</p><p>%s</p><p><strong>%s</strong> 1200×900px (4:3 ratio)</p>',
            esc_html( STB_VERSION ),
            esc_html__( 'A lightweight, SEO-optimized multipurpose theme.', 'smarttoolsblog' ),
            esc_html__( 'Screenshot dimensions:', 'smarttoolsblog' )
        ),
        'priority'    => 200,
    ) );

    // Dummy setting for the info section to appear.
    $wp_customize->add_setting( 'stb_info_placeholder', array(
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'stb_info_placeholder', array(
        'label'       => __( 'Screenshot Support', 'smarttoolsblog' ),
        'description' => __( 'Add a screenshot.png file (1200×900px) to the theme root folder. WordPress will display it in Appearance > Themes.', 'smarttoolsblog' ),
        'section'     => 'stb_theme_info',
        'type'        => 'hidden',
    ) );
}
add_action( 'customize_register', 'stb_customize_info_panel' );
