<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package WPFelix
 */
get_header(); ?>
<div class="grid-container">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <section class="error-404 not-found text-center">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'wpfelix' ); ?></h1>
                </header><!-- .page-header -->

                <div class="page-content">
                    <div class="grid">
                        <div class="grid-m10 grid-l8 grid-offset-m1 grid-offset-l2">
                            <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wpfelix' ); ?></p>
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </section><!-- .error-404 -->

        </main><!-- #main -->
    </div><!-- #primary -->
</div>
<?php
get_footer();
