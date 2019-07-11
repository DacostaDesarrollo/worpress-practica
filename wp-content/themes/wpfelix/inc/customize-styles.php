<?php
/**
 * Customize styles
 *
 * @package WPFelix
 */

function wpfelix_color_scheme_css()
{
    $color_scheme_option = get_theme_mod( 'color_scheme', 'default' );

    $color_scheme = wpfelix_get_color_scheme();

    $colors = array(
        'background_color'                      => get_theme_mod( 'background_color',                   $color_scheme[0] ),
        'background_color_alt'                  => get_theme_mod( 'background_color_alt',               $color_scheme[1] ),
        'text_color'                            => get_theme_mod( 'text_color',                         $color_scheme[2] ),
        'text_strong_color'                     => get_theme_mod( 'text_strong_color',                  $color_scheme[3] ),
        'text_weak_color'                       => get_theme_mod( 'text_weak_color',                    $color_scheme[4] ),
        'accent_color'                          => get_theme_mod( 'accent_color',                       $color_scheme[5] ),
        'border_color'                          => get_theme_mod( 'border_color',                       $color_scheme[6] ),
        'button_text_color'                     => get_theme_mod( 'button_text_color',                  $color_scheme[7] ),
        'button_hover_text_color'               => get_theme_mod( 'button_hover_text_color',            $color_scheme[8] ),
        'button_hover_background_color'         => get_theme_mod( 'button_hover_background_color',      $color_scheme[9] ),
        'background_color_header'               => get_theme_mod( 'background_color_header',            $color_scheme[10] ),
        'background_color_navbar'               => get_theme_mod( 'background_color_navbar',            $color_scheme[11] ),
        'border_color_navbar'                   => get_theme_mod( 'border_color_navbar',                $color_scheme[12] ),
        'link_color_navbar'                     => get_theme_mod( 'link_color_navbar',                  $color_scheme[13] ),
        'link_active_color_navbar'              => get_theme_mod( 'link_active_color_navbar',           $color_scheme[14] ),
        'border_color_navsub'                   => get_theme_mod( 'border_color_navsub',                $color_scheme[15] ),
        'background_color_navsub'               => get_theme_mod( 'background_color_navsub',            $color_scheme[16] ),
        'link_color_navsub'                     => get_theme_mod( 'link_color_navsub',                  $color_scheme[17] ),
        'link_active_color_navsub'              => get_theme_mod( 'link_active_color_navsub',           $color_scheme[18] ),
        'link_active_background_color_navsub'   => get_theme_mod( 'link_active_background_color_navsub', $color_scheme[19] ),
        'text_color_category'                   => get_theme_mod( 'text_color_category',                $color_scheme[20] ),
        'border_color_category'                 => get_theme_mod( 'border_color_category',              $color_scheme[21] ),
        'background_color_category'             => get_theme_mod( 'background_color_category',          $color_scheme[22] ),
        'text_color_widget_title'               => get_theme_mod( 'text_color_widget_title',            $color_scheme[23] ),
        'border_color_widget_title'             => get_theme_mod( 'border_color_widget_title',          $color_scheme[24] ),
        'background_color_widget_title'         => get_theme_mod( 'background_color_widget_title',      $color_scheme[25] ),
        'background_color_footer'               => get_theme_mod( 'background_color_footer',            $color_scheme[26] ),
        'text_color_footer'                     => get_theme_mod( 'text_color_footer',                  $color_scheme[27] ),
        'text_strong_color_footer'              => get_theme_mod( 'text_strong_color_footer',           $color_scheme[28] ),
        'text_weak_color_footer'                => get_theme_mod( 'text_weak_color_footer',             $color_scheme[29] ),
        'border_color_footer'                   => get_theme_mod( 'border_color_footer',                $color_scheme[30] )
    );

    if ( 'default' === $color_scheme_option )
    {
        $index = 0;

        foreach ( $colors as $key => $color )
        {
            if ( $colors[ $key ] == $color_scheme[ $index ] )
            {
                $colors[ $key ] = '';
            }

            $index++;
        }
    }

    $css_output = wpfelix_customizer_css_output( $colors );

    wp_add_inline_style( 'wpfelix-theme', wpfelix_css_minifier ( $css_output ) );
}
add_action( 'wp_enqueue_scripts', 'wpfelix_color_scheme_css' );



function wpfelix_customizer_css_output( $colors )
{
    $colors = wp_parse_args( $colors, array(
        'background_color'                      => '',
        'background_color_alt'                  => '',
        'text_color'                            => '',
        'text_strong_color'                     => '',
        'text_weak_color'                       => '',
        'accent_color'                          => '',
        'border_color'                          => '',
        'button_text_color'                     => '',
        'button_hover_text_color'               => '',
        'button_hover_background_color'         => '',
        'background_color_header'               => '',
        'background_color_navbar'               => '',
        'border_color_navbar'                   => '',
        'link_color_navbar'                     => '',
        'link_active_color_navbar'              => '',
        'border_color_navsub'                   => '',
        'background_color_navsub'               => '',
        'link_color_navsub'                     => '',
        'link_active_color_navsub'              => '',
        'link_active_background_color_navsub'   => '',
        'text_color_category'                   => '',
        'border_color_category'                 => '',
        'background_color_category'             => '',
        'text_color_widget_title'               => '',
        'border_color_widget_title'             => '',
        'background_color_widget_title'         => '',
        'background_color_footer'               => '',
        'text_color_footer'                     => '',
        'text_strong_color_footer'              => '',
        'text_weak_color_footer'                => '',
        'border_color_footer'                   => ''
    ) );

    if ( $colors['background_color'] && ! strpos( $colors['background_color'], '#' ) )
    {
        $colors['background_color'] = '#' . $colors['background_color'];
    }

    ob_start();

    if ( $colors['text_color'] )
    {
        echo <<<CSS
        body {
            color: {$colors['text_color']};
        }
CSS;
    }

    if ( $colors['background_color'] )
    {
        echo <<<CSS
        body {
            background-color: {$colors['background_color']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Background Alternate
    --------------------------------------------------------------*/

    if ( $colors['border_color'] )
    {
        echo <<<CSS
        .form-field {
            border-color: {$colors['border_color']};
        }
CSS;
    }

    if ( $colors['background_color_alt'] )
    {
        echo <<<CSS
        .form-field,
        .post-list-oe .entry-brief,
        .entry-link a {
            background-color: {$colors['background_color_alt']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Borders
    --------------------------------------------------------------*/

    if ( $colors['border_color'] )
    {
        echo <<<CSS
        .blockquote:before, blockquote:after  {
            border-bottom-color: {$colors['border_color']};
        }

        ul.entry-meta-below,
        .tag-links a,
        .widget_tag_cloud.use-theme-style .tagcloud a,
        .page-links > a,
        .page-links > span {
            border-color: {$colors['border_color']};
        }

        .special-title .left-border:before,
        .special-title .right-border:before,
        .comment-reply-title .left-border:before,
        .comment-reply-title .right-border:before,
        .widget_recent_entries ul li + li,
        .widget_recent_comments ul li + li,
        .widget_categories ul li + li,
        .widget_archive ul li + li,
        .widget_categories ul ul:before,
        .widget_rss ul li + li,
        .widget_wpfelix_recent_posts ul li + li,
        ul.entry-meta-below > li + li,
        .post-list + .post-list,
        .post-search + .post-search,
        .comment-list li.comment > ul.children .comment-author-image:before,
        .comment-list li.comment > ol.children .comment-author-image:before,
        .site-footer,
        .lower-alt-widget-area,
        .lead {
            border-top-color: {$colors['border_color']};
        }
        .lead {
            border-bottom-color: {$colors['border_color']};
        }

        .comment-list li.comment:before {
            border-left-color: {$colors['border_color']};
        }

        @media(min-width: 768px) {
            ul.entry-meta-below > li + li {
                border-left-color: {$colors['border_color']};
            }
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Highlight
    --------------------------------------------------------------*/

    if ( $colors['text_strong_color'] )
    {
        echo <<<CSS
        h1, h2, h3, h4, h5, h6,
        .list-social li a,
        blockquote:before, blockquote:after,
        cite,
        .search-submit,
        .posts-navigation .nav-previous a,
        .posts-navigation .nav-next a,
        .post-navigation .nav-previous a,
        .post-navigation .nav-next a,
        .comment-navigation .nav-previous a,
        .comment-navigation .nav-next a,
        .widget_recent_entries ul li a,
        .widget_recent_comments ul li a,
        .widget_categories ul li a,
        .widget_archive ul li a,
        .widget_recent_comments .comment-author-link,
        .widget_meta ul li a, .widget_nav_menu ul li a,
        .widget_pages ul li a,
        .widget_rss .rsswidget,
        .widget_tag_cloud .tagcloud a,
        .lower-alt-widget-area .widget_wpfelix_instagram .wpfelix-insta-usr > a:hover,
        .lower-alt-widget-area .widget_wpfelix_instagram .wpfelix-insta-usr > a:focus,
        ul.entry-meta-above a,
        ul.entry-share-links a,
        ul.entry-meta-below a,
        .tag-links a:hover,
        .tag-links a:focus,
        a#cancel-comment-reply-link,
        .comment-reply-link,
        .lead,
        .dropcap,
        .page-links > a,
        .color-highlight {
            color: {$colors['text_strong_color']};
        }

        #page:before,
        .widget_newsletterwidget,
        .widget_wpfelix_latest_tweets .owl-dot:hover,
        .widget_wpfelix_latest_tweets .owl-dot.active,
        .entry-icon {
            background-color: {$colors['text_strong_color']};
        }

        .search-field::-webkit-input-placeholder {
            color: {$colors['text_strong_color']};
        }
        .search-field:-moz-placeholder {
            color: {$colors['text_strong_color']};
        }
        .search-field::-moz-placeholder {
            color: {$colors['text_strong_color']};
        }
        .search-field:-ms-input-placeholder {
            color: {$colors['text_strong_color']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Muted
    --------------------------------------------------------------*/
    
    if ( $colors['text_weak_color'] )
    {
        echo <<<CSS
        ::-webkit-input-placeholder {
            color: {$colors['text_weak_color']};
        }
        :-moz-placeholder {
            color: {$colors['text_weak_color']};
        }
        ::-moz-placeholder {
            color: {$colors['text_weak_color']};
        }
        -ms-input-placeholder {
            color: {$colors['text_weak_color']};
        }

        .widget_recent_entries ul li,
        .widget_recent_comments ul li,
        .widget_categories ul li,
        .widget_archive ul li,
        .widget_rss .rss-date,
        .widget_tag_cloud.use-theme-style .tagcloud a,
        .widget_wpfelix_popular_posts .entry-posted-on,
        .widget_wpfelix_recent_posts .entry-posted-on,
        .widget_newsletterwidget .newsletter-email,
        .widget_wpfelix_latest_tweets .tweet-at,
        .widget_wpfelix_latest_tweets .owl-dot,
        .widget_text.footer-text-menu a,
        ul.entry-meta-above,
        ul.entry-meta-below,
        .tag-links a,
        .entry-related .entry-posted-on,
        .comment-list .comment-date,
        .site-footer .footer-copyright,
        .wp-caption-text,
        .color-muted {
            color: {$colors['text_weak_color']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Accent color
    --------------------------------------------------------------*/

    if ( $colors['accent_color'] )
    {
        echo <<<CSS
        a {
            color: {$colors['accent_color']};
        }
        ::-moz-selection {
            background-color: {$colors['accent_color']};
        }
        ::selection {
            background-color: {$colors['accent_color']};
        }
        
        .sticky .entry-sticky-icon,
        .site-page-title:after,
        .widget_wpfelix_instagram .wpfelix-insta-usr > a:hover,
        .widget_wpfelix_instagram .wpfelix-insta-usr > a:focus,
        .widget_newsletterwidget .newsletter-submit,
        .entry-link a:hover,
        .entry-link a:focus,
        a.back-to-top-link:hover,
        a.back-to-top-link:focus {
            background-color: {$colors['accent_color']};
        }

        .sidenav-container .search-field,
        .special-title .title-text,
        .comment-reply-title .title-text,
        .byuser .comment-author-image img,
        .bypostauthor .comment-author-image img,
        .widget-title,
        .widget_tag_cloud.use-theme-style .tagcloud a:hover,
        .widget_tag_cloud.use-theme-style .tagcloud a:focus,
        .widget_newsletterwidget .newsletter-submit,
        ul.side-menu li.hover > .submenu-toggle,
        ul.side-menu li .submenu-toggle:hover,
        .tag-links a:hover, .tag-links a:focus,
        .page-links > a:hover,
        .page-links > a:focus,
        .page-links > span,
        .byuser .comment-main:before,
        .bypostauthor .comment-main:before {
            border-color: {$colors['accent_color']};
        }

        .widget_newsletterwidget .widget-title:after {
            border-bottom-color: {$colors['accent_color']};
        }

        .list-social li a:hover,
        .list-social li a:focus,
        blockquote,
        .search-submit:hover,
        .search-submit:focus,
        .posts-navigation .nav-previous a:hover,
        .posts-navigation .nav-previous a:focus,
        .posts-navigation .nav-next a:hover,
        .posts-navigation .nav-next a:focus,
        .post-navigation .nav-previous a:hover,
        .post-navigation .nav-previous a:focus,
        .post-navigation .nav-next a:hover,
        .post-navigation .nav-next a:focus,
        .comment-navigation .nav-previous a:hover,
        .comment-navigation .nav-previous a:focus,
        .comment-navigation .nav-next a:hover,
        .comment-navigation .nav-next a:focus,
        .entry-title a:hover, .entry-title a:focus,
        .widget_recent_entries ul li a:hover,
        .widget_recent_entries ul li a:focus,
        .widget_recent_comments ul li a:hover,
        .widget_recent_comments ul li a:focus,
        .widget_archive ul li a:hover,
        .widget_archive ul li a:focus,
        .widget_pages ul li.current_page_ancestor > a,
        .widget_pages ul li.current_page_parent > a,
        .widget_pages ul li.current_page_item > a,
        .widget_pages ul li :hover,
        .widget_pages ul li :focus,
        .widget_categories ul li.current-cat-ancestor > a,
        .widget_categories ul li.current-cat > a,
        .widget_categories ul li a:hover,
        .widget_categories ul li a:focus,
        .widget_nav_menu ul li.current-menu-ancestor > a,
        .widget_nav_menu ul li.current-menu-parent > a,
        .widget_nav_menu ul li.current-menu-item > a,
        .widget_nav_menu ul li a:hover,
        .widget_nav_menu ul li a:focus,
        .widget_rss .rsswidget:hover,
        .widget_rss .rsswidget:focus,
        .widget_tag_cloud .tagcloud a:hover,
        .widget_tag_cloud .tagcloud a:focus,
        .widget_tag_cloud.use-theme-style .tagcloud a:hover,
        .widget_tag_cloud.use-theme-style .tagcloud a:focus,
        .widget_wpfelix_about .my-name,
        .widget_wpfelix_popular_posts .entry-posted-on a:hover,
        .widget_wpfelix_popular_posts .entry-posted-on a:focus,
        .widget_wpfelix_recent_posts .entry-posted-on a:hover,
        .widget_wpfelix_recent_posts .entry-posted-on a:focus,
        .widget_text.footer-text-menu a:hover,
        .widget_text.footer-text-menu a:focus,
        .widget_wpfelix_latest_tweets .tweet-at a:hover,
        .widget_wpfelix_latest_tweets .tweet-at a:focus,
        .widget_text.footer-text-menu a:hover,
        .widget_text.footer-text-menu a:focus,
        ul.side-menu li:hover > a,
        ul.side-menu li.hover > a,
        ul.side-menu li.current_page_item > a,
        ul.side-menu li.current-menu-item > a,
        ul.side-menu li.current_page_ancestor > a,
        ul.side-menu li.current-menu-ancestor > a,
        ul.side-menu li.current-menu-parent > a,
        ul.side-menu li.current_page_item > .submenu-toggle,
        ul.side-menu li.current-menu-item > .submenu-toggle,
        ul.side-menu li.current_page_ancestor > .submenu-toggle,
        ul.side-menu li.current-menu-ancestor > .submenu-toggle,
        ul.side-menu li.current-menu-parent > .submenu-toggle,
        ul.side-menu li.hover > .submenu-toggle,
        ul.side-menu li .submenu-toggle:hover,
        ul.entry-meta-above a:hover, ul.entry-meta-above a:focus,
        ul.post-categories a:hover,
        ul.post-categories a:focus,
        ul.entry-share-links a:hover,
        ul.entry-share-links a:focus,
        ul.entry-meta-below a:hover,
        ul.entry-meta-below a:focus,
        .entry-related .entry-posted-on a:hover,
        .entry-related .entry-posted-on a:focus,
        .slider-item-full ul.entry-meta-above a:hover,
        .slider-item-full ul.entry-meta-above a:focus,
        .slider-item-full ul.post-categories a:hover,
        .slider-item-full ul.post-categories a:focus,
        .slider-item-full-grid ul.entry-meta-above a:hover,
        .slider-item-full-grid ul.entry-meta-above a:focus,
        .slider-item-full-grid ul.post-categories a:hover,
        .slider-item-full-grid ul.post-categories a:focus,
        a#cancel-comment-reply-link:hover, a#cancel-comment-reply-link:focus,
        .comment-reply-link:hover,
        .comment-reply-link:focus,
        .page-links > a:hover,
        .page-links > a:focus,
        .page-links > span,
        .posts-page-links > a:hover,
        .posts-page-links > a:focus,
        .posts-page-links > span.current,
        .color-accent {
            color: {$colors['accent_color']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Buttons
    --------------------------------------------------------------*/
    
    if ( $colors['button_text_color'] )
    {
        echo <<<CSS
        .button {
            color: {$colors['button_text_color']};
        }
CSS;
    }

    if ( $colors['accent_color'] )
    {
        echo <<<CSS
        .button {
            background-color: {$colors['accent_color']};
        }
CSS;
    }

    if ( $colors['button_hover_text_color'] )
    {
        echo <<<CSS
        .button:hover,
        .button:focus {
            color: {$colors['button_hover_text_color']};
        }
CSS;
    }

    if ( $colors['button_hover_background_color'] )
    {
        echo <<<CSS
        .button:hover,
        .button:focus {
            background-color: {$colors['button_hover_background_color']};
        }
CSS;
    }

    if ( $colors['button_hover_text_color'] )
    {
        echo <<<CSS
        .button-alt {
            color: {$colors['button_hover_text_color']};
        }
CSS;
    }

    if ( $colors['button_hover_background_color'] )
    {
        echo <<<CSS
        .button-alt {
            background-color: {$colors['button_hover_background_color']};
        }
CSS;
    }

    if ( $colors['button_text_color'] )
    {
        echo <<<CSS
        .button-alt:hover,
        .button-alt:focus {
            color: {$colors['button_text_color']};
        }
CSS;
    }

    if ( $colors['accent_color'] )
    {
        echo <<<CSS
        .button-alt:hover,
        .button-alt:focus {
            background-color: {$colors['accent_color']};
        }
CSS;
    }


    /*--------------------------------------------------------------
    ## Header
    --------------------------------------------------------------*/

    if ( $colors['background_color_header'] )
    {
        echo <<<CSS
        .site-header {
            background-color: {$colors['background_color_header']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Navigation
    --------------------------------------------------------------*/
    
    if ( $colors['border_color_navbar'] )
    {
        echo <<<CSS
        .main-navigation {
            border-top-color: {$colors['border_color_navbar']};
            border-bottom-color: {$colors['border_color_navbar']};
        }
        .main-nav-top + .site-branding {
            border-bottom-color: {$colors['border_color_navbar']};
        }
CSS;
    }

    if ( $colors['background_color_navbar'] )
    {
        echo <<<CSS
        .main-navigation {
            background-color: {$colors['background_color_navbar']};
        }
CSS;
    }

    if ( $colors['link_color_navbar'] )
    {
        echo <<<CSS
        ul.primary-menu > li > a,
        ul.primary-menu ul > li > a,
        .main-navigation .list-social a,
        .main-navigation .button-toggle {
            color: {$colors['link_color_navbar']};
        }
CSS;
    }

    if ( $colors['link_active_color_navbar'] )
    {
        echo <<<CSS
        ul.primary-menu > li:hover > a,
        ul.primary-menu > li.hover > a,
        ul.primary-menu > li.current_page_item > a,
        ul.primary-menu > li.current-menu-item > a,
        ul.primary-menu > li.current_page_ancestor > a,
        ul.primary-menu > li.current-menu-ancestor > a,
        ul.primary-menu > li.current-menu-parent > a,
        .main-navigation .list-social a:hover,
        .main-navigation .list-social a:focus,
        .main-navigation .button-toggle:hover,
        .main-navigation .button-toggle:focus,
        .main-navigation .button-toggle.active {
            color: {$colors['link_active_color_navbar']};
        }
        ul.primary-menu ul {
            border-top-color: {$colors['link_active_color_navbar']};
        }
CSS;
    }

    if ( $colors['border_color_navsub'] )
    {
        echo <<<CSS
        ul.primary-menu ul {
            border-color: {$colors['border_color_navsub']};
        }
        ul.primary-menu ul > li + li {
            border-top-color: {$colors['border_color_navsub']};
        }
CSS;
    }

    if ( $colors['background_color_navsub'] )
    {
        echo <<<CSS
        ul.primary-menu ul {
            background-color: {$colors['background_color_navsub']};
        }
CSS;
    }

    if ( $colors['link_color_navsub'] )
    {
        echo <<<CSS
        ul.primary-menu ul > li > a {
            color: {$colors['link_color_navsub']};
        }
CSS;
    }

    if ( $colors['link_active_color_navsub'] )
    {
        echo <<<CSS
        ul.primary-menu ul > li:hover > a,
        ul.primary-menu ul > li.hover > a,
        ul.primary-menu ul > li.current_page_item > a,
        ul.primary-menu ul > li.current-menu-item > a,
        ul.primary-menu ul > li.current_page_ancestor > a,
        ul.primary-menu ul > li.current-menu-ancestor > a,
        ul.primary-menu ul > li.current-menu-parent > a {
            color: {$colors['link_active_color_navsub']};
        }
CSS;
    }

    if ( $colors['link_active_background_color_navsub'] )
    {
        echo <<<CSS
        ul.primary-menu ul > li:hover > a,
        ul.primary-menu ul > li.hover > a,
        ul.primary-menu ul > li.current_page_item > a,
        ul.primary-menu ul > li.current-menu-item > a,
        ul.primary-menu ul > li.current_page_ancestor > a,
        ul.primary-menu ul > li.current-menu-ancestor > a,
        ul.primary-menu ul > li.current-menu-parent > a {
            background-color: {$colors['link_active_background_color_navsub']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## CATEGORIES
    --------------------------------------------------------------*/

    if ( $colors['text_color_category'] )
    {
        echo <<<CSS
        ul.post-categories a {
            color: {$colors['text_color_category']};
        }
CSS;
    }

    if ( $colors['border_color_category'] )
    {
        echo <<<CSS
        ul.post-categories a {
            border-color: {$colors['border_color_category']};
        }
CSS;
    }

    if ( $colors['background_color_category'] )
    {
        echo <<<CSS
        ul.post-categories a {
            background-color: {$colors['background_color_category']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Widget
    --------------------------------------------------------------*/
    
    if ( $colors['text_color_widget_title'] )
    {
        echo <<<CSS
        .widget-title {
            color: {$colors['text_color_widget_title']};
        }
CSS;
    }

    if ( $colors['border_color_widget_title'] )
    {
        echo <<<CSS
        .widget-title {
            border-color: {$colors['border_color_widget_title']};
        }
CSS;
    }

    if ( $colors['background_color_widget_title'] )
    {
        echo <<<CSS
        .widget-title {
            background-color: {$colors['background_color_widget_title']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Footer
    --------------------------------------------------------------*/

    if ( $colors['background_color_footer'] )
    {
        echo <<<CSS
        .site-footer {
            background-color: {$colors['background_color_footer']};
            color: {$colors['text_color_footer']};
            border-top-color: {$colors['border_color_footer']};
        }
        .site-footer .footer-copyright {
            color: {$colors['text_weak_color_footer']};
        }
        .site-footer .list-social a {
            color: {$colors['text_strong_color_footer']};
            box-shadow: inset 0px 0px 0px 1px {$colors['border_color_footer']};
        }
CSS;
    }

    /*--------------------------------------------------------------
    ## Extras
    --------------------------------------------------------------*/
    
    if ( '' != get_theme_mod( 'featured_margin_top', '' ) )
    {
        printf(
            '.site-banner {
                margin-top: %spx;
            }',
            get_theme_mod( 'featured_margin_top', '' )
        );
    }
    if ( '' != get_theme_mod( 'header_padding_top', '' ) )
    {
        printf(
            '.site-branding {
                padding-top: %spx;
            }',
            get_theme_mod( 'header_padding_top', '' )
        );
    }
    if ( '' != get_theme_mod( 'header_padding_bottom', '' ) )
    {
        printf(
            '.site-branding {
                padding-bottom: %spx;
            }',
            get_theme_mod( 'header_padding_bottom', '' )
        );
    }

    if ( '' != get_theme_mod( 'footer_padding_top', '' ) )
    {
        printf(
            '.site-footer {
                padding-top: %spx;
            }',
            get_theme_mod( 'footer_padding_top', '' )
        );
    }
    if ( '' != get_theme_mod( 'footer_padding_bottom', '' ) )
    {
        printf(
            '.site-footer {
                padding-bottom: %spx;
            }',
            get_theme_mod( 'footer_padding_bottom', '' )
        );
    }

    if ( get_theme_mod( 'jetpack_css', true ) )
    {
        printf(
            '.widget.jetpack_subscription_widget {
                background-color: %s;
            }',
            $colors['text_strong_color']
        );
        printf(
            '.widget.jetpack_subscription_widget input[type="email"] {
                color: %s;
            }',
            $colors['text_weak_color']
        );
        printf(
            '.widget.jetpack_subscription_widget input[type="email"]:focus {
                color: %s;
            }',
            $colors['text_strong_color']
        );
        printf(
            '.widget.jetpack_subscription_widget .widget-title:after {
                border-bottom-color: %s !important;
            }',
            $colors['accent_color']
        );
        printf(
            '.widget.jetpack_subscription_widget #subscribe-submit input[type="submit"] {
                color: %1$s;
                background-color: %2$s;
            }',
            $colors['button_text_color'],
            $colors['accent_color']
        );
        printf(
            '.widget.jetpack_subscription_widget #subscribe-submit input[type="submit"]:hover,
            .widget.jetpack_subscription_widget #subscribe-submit input[type="submit"]:focus {
                color: %1$s;
                background-color: %2$s;
            }',
            $colors['button_hover_text_color'],
            $colors['button_hover_background_color']
        );

        printf(
            '.widget_rss_links a,
            .widget_top-posts a {
                color: %1$s;
            }',
            $colors['text_strong_color']
        );
        printf(
            '.widget_rss_links a:hover,
            .widget_rss_links a:focus,
            .widget_top-posts a:hover,
            .widget_top-posts a:focus {
                color: %1$s;
            }',
            $colors['accent_color']
        );
        
        printf(
            '#infinite-handle > span button:hover,
            #infinite-handle > span button:focus {
                color: %s
            }',
            $colors['accent_color']
        );

        printf(
            '.infinite-scroll-page-num {
                background-color: %s
            }',
            $colors['background_color_alt']
        );
        printf(
            '.infinite-loader:before {
                box-shadow: inset 0px 0px 0px 4px %s;
            }',
            $colors['accent_color']
        );
    }
    return ob_get_clean();
}