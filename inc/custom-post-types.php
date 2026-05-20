<?php
/**
 * Custom Post Types - Tools with Meta Boxes
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
        'featured_image'        => __( 'Tool Image', 'smarttoolsblog' ),
        'set_featured_image'    => __( 'Set tool image', 'smarttoolsblog' ),
        'remove_featured_image' => __( 'Remove tool image', 'smarttoolsblog' ),
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
            'author',
        ),
        'template'           => array(
            array( 'core/paragraph', array(
                'placeholder' => __( 'Describe this tool or add calculator content...', 'smarttoolsblog' ),
            ) ),
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
 * Register tool meta box for additional fields.
 */
function stb_tool_meta_boxes() {
    add_meta_box(
        'stb_tool_details',
        __( 'Tool Details', 'smarttoolsblog' ),
        'stb_tool_details_callback',
        'tool',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'stb_tool_meta_boxes' );

/**
 * Tool details meta box callback.
 *
 * @param WP_Post $post Current post object.
 */
function stb_tool_details_callback( $post ) {
    wp_nonce_field( 'stb_tool_meta_nonce', 'stb_tool_nonce' );

    $cta_url     = get_post_meta( $post->ID, '_stb_tool_cta_url', true );
    $cta_text    = get_post_meta( $post->ID, '_stb_tool_cta_text', true );
    $tool_version = get_post_meta( $post->ID, '_stb_tool_version', true );
    $tool_rating = get_post_meta( $post->ID, '_stb_tool_rating', true );
    $tool_type   = get_post_meta( $post->ID, '_stb_tool_type', true );
    ?>
    <style>
        .stb-meta-row { margin-bottom: 15px; }
        .stb-meta-row label { display: block; font-weight: 600; margin-bottom: 4px; }
        .stb-meta-row input, .stb-meta-row select { width: 100%; max-width: 500px; padding: 6px 10px; }
        .stb-meta-row .description { color: #666; font-size: 12px; margin-top: 4px; }
    </style>
    <div class="stb-meta-row">
        <label for="stb_tool_type"><?php esc_html_e( 'Tool Type', 'smarttoolsblog' ); ?></label>
        <select id="stb_tool_type" name="stb_tool_type">
            <option value=""><?php esc_html_e( 'Select type...', 'smarttoolsblog' ); ?></option>
            <option value="calculator" <?php selected( $tool_type, 'calculator' ); ?>><?php esc_html_e( 'Calculator', 'smarttoolsblog' ); ?></option>
            <option value="converter" <?php selected( $tool_type, 'converter' ); ?>><?php esc_html_e( 'Converter', 'smarttoolsblog' ); ?></option>
            <option value="generator" <?php selected( $tool_type, 'generator' ); ?>><?php esc_html_e( 'Generator', 'smarttoolsblog' ); ?></option>
            <option value="checker" <?php selected( $tool_type, 'checker' ); ?>><?php esc_html_e( 'Checker / Validator', 'smarttoolsblog' ); ?></option>
            <option value="ai-tool" <?php selected( $tool_type, 'ai-tool' ); ?>><?php esc_html_e( 'AI Tool', 'smarttoolsblog' ); ?></option>
            <option value="other" <?php selected( $tool_type, 'other' ); ?>><?php esc_html_e( 'Other', 'smarttoolsblog' ); ?></option>
        </select>
    </div>
    <div class="stb-meta-row">
        <label for="stb_tool_cta_url"><?php esc_html_e( 'CTA Button URL', 'smarttoolsblog' ); ?></label>
        <input type="url" id="stb_tool_cta_url" name="stb_tool_cta_url" value="<?php echo esc_url( $cta_url ); ?>" placeholder="https://example.com">
        <p class="description"><?php esc_html_e( 'External link for the call-to-action button (optional).', 'smarttoolsblog' ); ?></p>
    </div>
    <div class="stb-meta-row">
        <label for="stb_tool_cta_text"><?php esc_html_e( 'CTA Button Text', 'smarttoolsblog' ); ?></label>
        <input type="text" id="stb_tool_cta_text" name="stb_tool_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" placeholder="<?php esc_attr_e( 'Try This Tool', 'smarttoolsblog' ); ?>">
    </div>
    <div class="stb-meta-row">
        <label for="stb_tool_version"><?php esc_html_e( 'Tool Version', 'smarttoolsblog' ); ?></label>
        <input type="text" id="stb_tool_version" name="stb_tool_version" value="<?php echo esc_attr( $tool_version ); ?>" placeholder="1.0.0">
    </div>
    <div class="stb-meta-row">
        <label for="stb_tool_rating"><?php esc_html_e( 'Rating (1-5)', 'smarttoolsblog' ); ?></label>
        <input type="number" id="stb_tool_rating" name="stb_tool_rating" value="<?php echo esc_attr( $tool_rating ); ?>" min="1" max="5" step="0.1" placeholder="4.5">
    </div>
    <?php
}

/**
 * Save tool meta box data.
 *
 * @param int $post_id Post ID.
 */
function stb_save_tool_meta( $post_id ) {
    // Verify nonce
    if ( ! isset( $_POST['stb_tool_nonce'] ) || ! wp_verify_nonce( $_POST['stb_tool_nonce'], 'stb_tool_meta_nonce' ) ) {
        return;
    }

    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize and save fields
    $fields = array(
        '_stb_tool_cta_url'  => 'esc_url_raw',
        '_stb_tool_cta_text' => 'sanitize_text_field',
        '_stb_tool_version'  => 'sanitize_text_field',
        '_stb_tool_rating'   => 'stb_sanitize_rating',
        '_stb_tool_type'     => 'sanitize_text_field',
    );

    // Map POST keys to meta keys
    $post_keys = array(
        '_stb_tool_cta_url'  => 'stb_tool_cta_url',
        '_stb_tool_cta_text' => 'stb_tool_cta_text',
        '_stb_tool_version'  => 'stb_tool_version',
        '_stb_tool_rating'   => 'stb_tool_rating',
        '_stb_tool_type'     => 'stb_tool_type',
    );

    foreach ( $fields as $meta_key => $sanitize_fn ) {
        $post_key = $post_keys[ $meta_key ];
        if ( isset( $_POST[ $post_key ] ) ) {
            $value = call_user_func( $sanitize_fn, wp_unslash( $_POST[ $post_key ] ) );
            update_post_meta( $post_id, $meta_key, $value );
        }
    }
}
add_action( 'save_post_tool', 'stb_save_tool_meta' );

/**
 * Sanitize rating value (1-5).
 *
 * @param mixed $value Input value.
 * @return string Sanitized rating.
 */
function stb_sanitize_rating( $value ) {
    $value = floatval( $value );
    if ( $value < 1 ) return '';
    if ( $value > 5 ) return '5';
    return number_format( $value, 1 );
}

/**
 * Add custom columns to Tools admin list.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function stb_tool_admin_columns( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        $new_columns[ $key ] = $value;
        if ( 'title' === $key ) {
            $new_columns['tool_type'] = __( 'Type', 'smarttoolsblog' );
            $new_columns['tool_rating'] = __( 'Rating', 'smarttoolsblog' );
        }
    }
    return $new_columns;
}
add_filter( 'manage_tool_posts_columns', 'stb_tool_admin_columns' );

/**
 * Render custom column content for Tools.
 *
 * @param string $column  Column name.
 * @param int    $post_id Post ID.
 */
function stb_tool_admin_column_content( $column, $post_id ) {
    switch ( $column ) {
        case 'tool_type':
            $type = get_post_meta( $post_id, '_stb_tool_type', true );
            echo $type ? esc_html( ucfirst( str_replace( '-', ' ', $type ) ) ) : '&mdash;';
            break;
        case 'tool_rating':
            $rating = get_post_meta( $post_id, '_stb_tool_rating', true );
            echo $rating ? esc_html( $rating . '/5' ) : '&mdash;';
            break;
    }
}
add_action( 'manage_tool_posts_custom_column', 'stb_tool_admin_column_content', 10, 2 );

/**
 * Flush rewrite rules on theme activation.
 */
function stb_rewrite_flush() {
    stb_register_post_types();
    stb_register_taxonomies();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'stb_rewrite_flush' );
