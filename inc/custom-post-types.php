<?php
/**
 * Custom Post Types - Tools
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Tools custom post type.
 */
function stb_register_post_types() {
    $labels = array(
        'name'                  => _x( 'Tools', 'Post type general name', 'smarttoolsblog' ),
        'singular_name'         => _x( 'Tool', 'Post type singular name', 'smarttoolsblog' ),
        'menu_name'             => _x( 'Tools', 'Admin Menu text', 'smarttoolsblog' ),
        'add_new'               => __( 'Add New Tool', 'smarttoolsblog' ),
        'add_new_item'          => __( 'Add New Tool', 'smarttoolsblog' ),
        'edit_item'             => __( 'Edit Tool', 'smarttoolsblog' ),
        'new_item'              => __( 'New Tool', 'smarttoolsblog' ),
        'view_item'             => __( 'View Tool', 'smarttoolsblog' ),
        'search_items'          => __( 'Search Tools', 'smarttoolsblog' ),
        'not_found'             => __( 'No tools found', 'smarttoolsblog' ),
        'not_found_in_trash'    => __( 'No tools found in Trash', 'smarttoolsblog' ),
        'all_items'             => __( 'All Tools', 'smarttoolsblog' ),
        'archives'              => __( 'Tool Archives', 'smarttoolsblog' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'tools', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-admin-tools',
        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
            'custom-fields',
            'revisions',
        ),
    );

    register_post_type( 'tool', $args );
}
add_action( 'init', 'stb_register_post_types' );

/**
 * Register Tool Category taxonomy.
 */
function stb_register_taxonomies() {
    $labels = array(
        'name'              => _x( 'Tool Categories', 'taxonomy general name', 'smarttoolsblog' ),
        'singular_name'     => _x( 'Tool Category', 'taxonomy singular name', 'smarttoolsblog' ),
        'search_items'      => __( 'Search Tool Categories', 'smarttoolsblog' ),
        'all_items'         => __( 'All Tool Categories', 'smarttoolsblog' ),
        'parent_item'       => __( 'Parent Category', 'smarttoolsblog' ),
        'parent_item_colon' => __( 'Parent Category:', 'smarttoolsblog' ),
        'edit_item'         => __( 'Edit Category', 'smarttoolsblog' ),
        'update_item'       => __( 'Update Category', 'smarttoolsblog' ),
        'add_new_item'      => __( 'Add New Category', 'smarttoolsblog' ),
        'new_item_name'     => __( 'New Category Name', 'smarttoolsblog' ),
        'menu_name'         => __( 'Tool Categories', 'smarttoolsblog' ),
    );

    register_taxonomy( 'tool_category', array( 'tool' ), array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'tool-category' ),
    ) );
}
add_action( 'init', 'stb_register_taxonomies' );

/**
 * Flush rewrite rules on theme activation.
 */
function stb_rewrite_flush() {
    stb_register_post_types();
    stb_register_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'stb_rewrite_flush' );
