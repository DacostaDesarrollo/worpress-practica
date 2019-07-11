<?php
/**
 * wpfelix functions and definitions.
 *
 * @package WPFelix
 */


/**
 * Notice and prevent install on older version of WP ( at least 4.5 )
 */
if ( version_compare( $GLOBALS['wp_version'], '4.5', '<' ) )
{
    require get_template_directory() . '/inc/functions-notice.php';
}


/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function wpfelix_setup()
{
    load_theme_textdomain( 'wpfelix', get_template_directory() . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary', 'wpfelix' ),
        'side' => esc_html__( 'Side', 'wpfelix' )
    ) );

    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );

    add_theme_support( 'post-formats', array(
        'aside',
        'image',
        'gallery',
        'audio',
        'video',
        'quote',
        'link',
    ) );

    add_editor_style( array( 'css/editor-style.css' ) );
}
add_action( 'after_setup_theme', 'wpfelix_setup' );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function wpfelix_content_width()
{
    $GLOBALS['content_width'] = apply_filters( 'wpfelix_content_width', 770 );
}
add_action( 'after_setup_theme', 'wpfelix_content_width', 0 );


/**
 * Register widget area.
 */
function wpfelix_widgets_init()
{
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'wpfelix' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Default widget area.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar for Pages', 'wpfelix' ),
        'id'            => 'sidebar-pages',
        'description'   => esc_html__( 'Widget area for widgets showing on pages.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Aside Top', 'wpfelix' ),
        'id'            => 'sidebar-aside-top',
        'description'   => esc_html__( 'Widget area for widgets showing at the right side area of the site, just after side menu. You need to enable side navigation to use this.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Aside Bottom', 'wpfelix' ),
        'id'            => 'sidebar-aside-bot',
        'description'   => esc_html__( 'Widget area for widgets showing at the very bottom of the right side area of the site. You need to enable side navigation to use this.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Lower Area Col 1', 'wpfelix' ),
        'id'            => 'sidebar-lower-c1',
        'description'   => esc_html__( 'Widget area just before footer.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="fwidget-c1 widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Lower Area Col 2', 'wpfelix' ),
        'id'            => 'sidebar-lower-c2',
        'description'   => esc_html__( 'Widget area just before footer.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="fwidget-c2 widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Lower Area Col 3', 'wpfelix' ),
        'id'            => 'sidebar-lower-c3',
        'description'   => esc_html__( 'Widget area just before footer.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="fwidget-c3 widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Instagram', 'wpfelix' ),
        'id'            => 'sidebar-footer-insta',
        'description'   => esc_html__( 'Footer Instagram.', 'wpfelix' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'wpfelix_widgets_init' );

//update_option( 'siteurl', 'http://67.207.90.189/worpress-practica');
//update_option( 'home', 'http://67.207.90.189/worpress-practica  ');

/**
 * Enqueue scripts and styles.
 */
function wpfelix_scripts()
{
    /**
     * Enqueue styles
     */
    wp_enqueue_style( 'wpfelix-fonts', wpfelix_fonts_url() );

    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3' );
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl.carousel.min.css', array(), '2.1.0' );
    wp_enqueue_style( 'wpfelix-theme', get_template_directory_uri() . '/css/theme.min.css' );

    if ( get_theme_mod( 'jetpack_css', true ) )
    {
        wp_enqueue_style( 'wpfelix-jetpack', get_template_directory_uri() . '/css/jetpack.css' );
    }

    wp_enqueue_style( 'wpfelix-style', get_stylesheet_uri() );

    /**
     * Register and qneue scripts
     */
    wp_enqueue_script( 'jquery-fidvids', get_template_directory_uri() . '/js/jquery.fitvids.min.js', array(), '1.1', true );
    wp_enqueue_script( 'imagesloaded', get_template_directory_uri() . '/js/imagesloaded.pkgd.min.js', array(), '3.2.0', true );
    wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '2.1.0', true );

    wp_enqueue_script( 'polyfills', get_template_directory_uri() . '/js/polyfills.min.js', array(), null, true );
    wp_enqueue_script( 'felix', get_template_directory_uri() . '/js/theme.min.js', array( 'polyfills', 'jquery' ), '20160608', true );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'wpfelix_scripts' );


/**
 * Required plugins
 */
require get_template_directory() . '/inc/options-plugins.php';

/**
 * Helper Functions
 */
require get_template_directory() . '/inc/functions-helper.php';

/**
 * Admin media settings
 */
require get_template_directory() . '/inc/classes/class-admin-media.php';

/**
 * Customizer Helper functions
 */
require get_template_directory() . '/inc/customize-helper.php';

/**
 * Customizer Register.
 */
require get_template_directory() . '/inc/customize.php';

/**
 * Customizer Styles.
 */
require get_template_directory() . '/inc/customize-styles.php';

/**
 * Social Share
 */
require get_template_directory() . '/inc/classes/class-social-share.php';

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/functions-template.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/functions-extras.php';

/**
 * Load Jetpack compatibility file.
 */
if ( class_exists( 'Jetpack' ) )
{
    require get_template_directory() . '/inc/jetpack.php';

    if ( Jetpack::is_module_active( 'stats' ) )
    {
        require get_template_directory() . '/widgets/widget-popular-posts.php';
    }
}

/**
 * Extra CSS class field for all widgets
 */
require get_template_directory() . '/inc/classes/class-widget-extends.php';

/**
 * Recent Posts widget
 */
require get_template_directory() . '/widgets/widget-recent-posts.php';

/**
 * Image widget
 */
require get_template_directory() . '/widgets/widget-image.php';

/**
 * Images widget
 */
require get_template_directory() . '/widgets/widget-images.php';

/**
 * About widget
 */
require get_template_directory() . '/widgets/widget-about.php';

/**
 * Social widget
 */
require get_template_directory() . '/widgets/widget-social.php';

/**
 * Allow child theme to enable/disable built-in twitter widget
 * @since 1.0.1
 */
if ( apply_filters( 'wpfelix_widget_latest_tweets_enabled', true ) )
{
    /**
     * Recent Posts widget
     */
    require get_template_directory() . '/widgets/widget-latest-tweets.php';
}

/**
 * Allow child theme to enable/disable built-in instagram widget
 * @since 1.0.1
 */
if ( apply_filters( 'wpfelix_widget_instagram_enabled', true ) )
{
    /**
     * Instagram widget
     */
    require get_template_directory() . '/widgets/widget-instagram.php';
}
