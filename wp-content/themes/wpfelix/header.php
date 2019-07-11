<?php
/**
 * The header for our theme.
 *
 * @package WPFelix
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php

$nav_pos = get_theme_mod( 'header_nav_pos', 'bottom' );
$header_image = '';
$jetpack_infinite_scroll = false;

if ( class_exists( 'Jetpack' ) && in_array( 'infinite-scroll', Jetpack::get_active_modules() ) )
{
    $jetpack_infinite_scroll = true;
}

$sr_only_site_branding = display_header_text() ? '' : ' screen-reader-text';

wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site"<?php echo ( $jetpack_infinite_scroll ? ' data-jpk-infinite="true"' : '' ); ?>>
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'wpfelix' ); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <?php        
        if ( 'top' == $nav_pos )
        {
            get_template_part( 'template-parts/header', 'nav' );
        }
        ?>
        <div class="site-branding" <?php if ( get_header_image() ) : printf( ' style="background-image:url(%s);"', esc_url( get_header_image() ) ); endif; ?>>
            <div class="grid-container text-center">
                <?php
                the_custom_logo();
                if ( is_front_page() && is_home() ) : ?>
                    <h1 class="site-title<?php echo esc_attr( $sr_only_site_branding ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php else : ?>
                    <p class="site-title<?php echo esc_attr( $sr_only_site_branding ); ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                endif;

                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) : ?>
                    <p class="site-description<?php echo esc_attr( $sr_only_site_branding ); ?>"><?php echo $description; /* WPCS: xss ok. */ ?></p>
                <?php
                endif; ?>
            </div>
        </div><!-- .site-branding -->
        <?php
        if ( 'bottom' == $nav_pos )
        {
            get_template_part( 'template-parts/header', 'nav' );
        }
        ?>
    </header><!-- #masthead -->
    <?php
    if ( get_theme_mod( 'header_sidenav_enable', true ) ) {
        get_template_part( 'template-parts/header', 'sidenav' );
    }
    get_template_part( 'template-parts/header', 'pagetitle' );
    ?>
    <div id="content" class="site-content">
