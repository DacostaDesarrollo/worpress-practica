<?php
/**
 * Template name: Full Width Alternate
 *
 * Full width Page template
 * @package WPFelix
 */

get_header(); ?>
<div class="grid-container">
    <div class="grid">
        <div id="primary" class="content-area without-sidebar grid-l10 grid-offset-l1 grid-xl8 grid-offset-xl2">
            <main id="main" class="site-main" role="main">
                <?php
                while ( have_posts() ) : the_post();

                    get_template_part( 'template-parts/content', 'page' );

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>

            </main><!-- #main -->
        </div><!-- #primary -->
    </div>
</div>
<?php
get_footer();