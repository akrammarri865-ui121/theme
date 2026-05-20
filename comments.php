<?php
/**
 * Comments Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

// Don't load if accessed directly.
if ( post_password_required() ) {
    return;
}
?>

<section id="comments" class="comments-area" aria-label="<?php esc_attr_e( 'Comments', 'smarttoolsblog' ); ?>">

    <?php if ( have_comments() ) : ?>

        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            printf(
                /* translators: %s: comment count */
                esc_html( _n( '%s Comment', '%s Comments', $comment_count, 'smarttoolsblog' ) ),
                esc_html( number_format_i18n( $comment_count ) )
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'stb_comment_callback',
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation( array(
            'prev_text' => esc_html__( '&larr; Older Comments', 'smarttoolsblog' ),
            'next_text' => esc_html__( 'Newer Comments &rarr;', 'smarttoolsblog' ),
        ) );
        ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'smarttoolsblog' ); ?></p>
    <?php endif; ?>

    <?php
    comment_form( array(
        'class_form'    => 'comment-form',
        'title_reply'   => esc_html__( 'Leave a Comment', 'smarttoolsblog' ),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'smarttoolsblog' ) . '</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
    ) );
    ?>

</section>

<?php
/**
 * Custom comment callback for cleaner markup.
 *
 * @param WP_Comment $comment Comment object.
 * @param array      $args    Arguments.
 * @param int        $depth   Depth.
 */
function stb_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item' ); ?>>
        <article class="comment-body">
            <header class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar( $comment, 48, '', '', array( 'class' => 'comment-avatar' ) ); ?>
                    <div class="comment-author-info">
                        <cite class="fn"><?php comment_author_link(); ?></cite>
                        <time datetime="<?php comment_time( 'c' ); ?>">
                            <?php
                            printf(
                                /* translators: %1$s: date, %2$s: time */
                                esc_html__( '%1$s at %2$s', 'smarttoolsblog' ),
                                esc_html( get_comment_date() ),
                                esc_html( get_comment_time() )
                            );
                            ?>
                        </time>
                    </div>
                </div>
            </header>

            <div class="comment-content">
                <?php if ( '0' === $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation">
                        <?php esc_html_e( 'Your comment is awaiting moderation.', 'smarttoolsblog' ); ?>
                    </p>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>

            <footer class="comment-actions">
                <?php
                comment_reply_link( array_merge( $args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="reply">',
                    'after'     => '</span>',
                ) ) );
                edit_comment_link( esc_html__( 'Edit', 'smarttoolsblog' ), '<span class="edit-link">', '</span>' );
                ?>
            </footer>
        </article>
    <?php
}
