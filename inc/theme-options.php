<?php
/**
 * Theme Options - Customizer Settings and Ad Slots
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
        'title'    => __( 'Ad Slots', 'smarttoolsblog' ),
        'priority' => 90,
    ) );

    $ad_slots = array(
        'header'         => __( 'Header Ad', 'smarttoolsblog' ),
        'sidebar'        => __( 'Sidebar Ad', 'smarttoolsblog' ),
        'article_middle' => __( 'Article Middle Ad', 'smarttoolsblog' ),
        'article_end'    => __( 'Article End Ad', 'smarttoolsblog' ),
        'mobile_sticky'  => __( 'Mobile Sticky Ad', 'smarttoolsblog' ),
    );

    foreach ( $ad_slots as $slot => $label ) {
        $wp_customize->add_setting( "stb_ad_{$slot}", array(
            'default'           => '',
            'sanitize_callback' => 'stb_sanitize_ad_code',
            'transport'         => 'postMessage',
        ) );

        $wp_customize->add_control( "stb_ad_{$slot}", array(
            'label'       => $label,
            'description' => __( 'Paste your ad code here.', 'smarttoolsblog' ),
            'section'     => 'stb_ads_section',
            'type'        => 'textarea',
        ) );
    }

    // === Theme Options Section ===
    $wp_customize->add_section( 'stb_theme_options', array(
        'title'    => __( 'Theme Options', 'smarttoolsblog' ),
        'priority' => 30,
    ) );

    // Dark mode default
    $wp_customize->add_setting( 'stb_default_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );

    $wp_customize->add_control( 'stb_default_dark_mode', array(
        'label'   => __( 'Enable Dark Mode by Default', 'smarttoolsblog' ),
        'section' => 'stb_theme_options',
        'type'    => 'checkbox',
    ) );

    // Featured posts category
    $wp_customize->add_setting( 'stb_featured_category', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'stb_featured_category', array(
        'label'   => __( 'Featured Posts Category ID', 'smarttoolsblog' ),
        'section' => 'stb_theme_options',
        'type'    => 'number',
    ) );

    // Posts per page on homepage grid
    $wp_customize->add_setting( 'stb_homepage_posts', array(
        'default'           => 9,
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( 'stb_homepage_posts', array(
        'label'       => __( 'Homepage Posts Count', 'smarttoolsblog' ),
        'section'     => 'stb_theme_options',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 3, 'max' => 24, 'step' => 3 ),
    ) );
}
add_action( 'customize_register', 'stb_customize_register' );

/**
 * Sanitize ad code - allow scripts and iframes.
 *
 * @param string $input Raw ad code.
 * @return string Sanitized ad code.
 */
function stb_sanitize_ad_code( $input ) {
    return $input; // Ad code needs scripts/iframes - only admins can access Customizer.
}
