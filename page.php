<?php
/**
 * Page Template
 *
 * @package SmartToolsBlog
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="container content-area">
    <div class="content-main content-main--full">

        <?php while ( have_posts() ) : the_post(); ?>

            <article <?php post_class( 'single-page' ); ?>>
                <header class="single-page__header">
                    <h1 class="single-page__title"><?php the_title(); ?></h1>
                </header>

                <div class="single-page__content">
                    <?php the_content(); ?>
                </div>
            </article>

            <?php
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer(); ?>
