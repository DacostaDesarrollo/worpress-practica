<?php
/**
 * The template for displaying the footer.
 * 
 * @package WPFelix
 */

?>
    </div><!-- #content -->
    <?php
    $intro = get_theme_mod( 'footer_intro' );
    $copyright = get_theme_mod( 'footer_copyright' );

    if ( ( is_home() && ! get_theme_mod( 'lwr_area_home_disabled', false ) ) ||
         ( is_archive() && ! get_theme_mod( 'lwr_area_archive_disabled', false ) ) ||
         ( is_singular( 'post' ) && ! get_theme_mod( 'lwr_area_post_disabled', false ) ) ||
         ( is_page() && ! get_theme_mod( 'lwr_area_page_disabled', false ) ) )
    {
        if ( is_active_sidebar( 'sidebar-lower-c1' ) || is_active_sidebar( 'sidebar-lower-c2' ) || is_active_sidebar( 'sidebar-lower-c3' ) )
        {
            ob_start();
            $columns = 0;

            echo '<div class="grid-container">
                <div class="inner">';

                if ( is_active_sidebar( 'sidebar-lower-c1' ) )
                {
                    $columns++;
                    echo '<div class="lwa-col">';
                    dynamic_sidebar( 'sidebar-lower-c1' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'sidebar-lower-c2' ) )
                {
                    $columns++;
                    echo '<div class="lwa-col">';
                    dynamic_sidebar( 'sidebar-lower-c2' );
                    echo '</div>';
                }
                if ( is_active_sidebar( 'sidebar-lower-c3' ) )
                {
                    $columns++;
                    echo '<div class="lwa-col">';
                    dynamic_sidebar( 'sidebar-lower-c3' );
                    echo '</div>';
                }
            echo '</div>
                </div>';

            $output = ob_get_clean();
            if ( ! empty( $output ) )
            {
                printf( '<div id="lower" class="lower-widget-area lower-widget-area-c%1$s">%2$s</div>', esc_attr( $columns ), $output );
            }
        }
    }

    if ( is_active_sidebar( 'sidebar-footer-insta' ) )
    {
        echo '<div id="lower-alt" class="lower-alt-widget-area">';
            dynamic_sidebar( 'sidebar-footer-insta' );
        echo '</div>';
    }
    ?>
    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="site-info">
            <div class="grid-container">
                <div class="grid">
                    <div class="grid-l3 grid-m8 grid-s10 grid-offset-l0 grid-offset-m2 grid-offset-s1">
                        <?php
                        $footer_logo = wp_get_attachment_image_src( get_theme_mod( 'footer_logo', 0 ), 'full' );

                        if ( is_customize_preview() )
                        {
                            if ( $footer_logo )
                            {
                                printf(
                                    '<div class="footer-logo"><a href="%1$s" class="footer-logo-link"><img class="footer-logo-img" src="%2$s" alt="footer-logo"/></a></div>',
                                    esc_url( home_url( '/' ) ),
                                    esc_url( $footer_logo[0] )
                                );
                            }
                            else
                            {
                                printf(
                                    '<div class="footer-logo"><a href="%1$s" class="footer-logo-link" style="display:none;"><img class="footer-logo-img" alt="footer-logo"/></a></div>',
                                    esc_url( home_url( '/' ) )
                                );
                            }
                        }
                        else
                        {
                            if ( $footer_logo )
                            {
                                printf(
                                    '<div class="footer-logo"><a href="%1$s" class="footer-logo-link"><img class="footer-logo-img" src="%2$s" alt="footer-logo"/></a></div>',
                                    esc_url( home_url( '/' ) ),
                                    esc_url( $footer_logo[0] )
                                );
                            }
                        }
                        ?>
                    </div>
                    <div class="grid-l6 text-center">
                        <?php
                        if ( is_customize_preview() )
                        {
                            if ( $intro || $copyright )
                            {
                                echo '<div class="footer-text-container">';

                                echo    '<div class="footer-intro">';
                                if ( $intro )
                                {
                                    echo wp_kses( $intro, wpfelix_global_kses( 'blockquote' ) );
                                }
                                echo    '</div>'; // .footer-intro

                                echo    '<div class="footer-copyright">';
                                if ( $copyright )
                                {
                                    echo wp_kses( $copyright, wpfelix_global_kses( 'blockquote' ) );
                                }
                                echo    '</div>'; // .ooter-copyright

                                echo '</div>'; // .footer-text-container
                            }
                        }
                        else
                        {
                            if ( $intro || $copyright )
                            {
                                echo '<div class="footer-text-container">';

                                if ( $intro )
                                {
                                    echo '<div class="footer-intro">';
                                    echo wp_kses( $intro, wpfelix_global_kses( 'blockquote' ) );
                                    echo '</div>'; // .footer-intro
                                }
                                
                                if ( $copyright )
                                {
                                    echo '<div class="footer-copyright">';
                                    echo wp_kses( $copyright, wpfelix_global_kses( 'blockquote' ) );
                                    echo'</div>'; // .ooter-copyright
                                }

                                echo '</div>'; // .footer-text-container
                            }
                        }
                        ?>
                    </div>
                    <div class="grid-l3 grid-m8 grid-s10 grid-offset-l0 grid-offset-m2 grid-offset-s1">
                        <?php
                        if ( get_theme_mod( 'footer_show_social', true ) )
                        {
                            wpfelix_social_markup();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div><!-- .site-info -->
    </footer><!-- #colophon -->
    <?php if ( get_theme_mod( 'back_to_top', true ) ) : ?>
    <a href="javascript:void(0)" id="backtotop" class="back-to-top-link"><i class="fa fa-angle-up"></i></a>
    <?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
