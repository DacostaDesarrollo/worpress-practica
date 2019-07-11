<?php
/**
 * wordpress functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wordpress
 */

if ( ! function_exists( 'wordpress_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wordpress_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on wordpress, use a find and replace
		 * to change 'wordpress' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wordpress', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'wordpress' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wordpress_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'wordpress_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wordpress_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'wordpress_content_width', 640 );
}
add_action( 'after_setup_theme', 'wordpress_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wordpress_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wordpress' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wordpress' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wordpress_widgets_init' );

function botonera_shortcode( $atts ) {
   $a = shortcode_atts( array(
      'whatsapp' => '',
      'phone' => '',
      'email' => ''
   ), $atts );

   return sprintf( '
   	<div id="wh-widget-send-button-wrapper" class="wh-widget-send-button-wrapper wh-widget-right">

    <div class="wh-widget-hello-popup-wrapper wh-popup-right wh-hide popup-slide popup-slide-in" id="wh-popup-hello">
        <div class="wh-widget-hello-popup">
            <div class="wh-widget-hello-popup-close" wh-click="closeHelloPopup">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    <path d="M0 0h24v24H0z" fill="none"></path>
                </svg>
            </div>
            <div class="wh-widget-hello-popup-content">
                <div class="wh-widget-hello-popup-content-logo">
                    <img wh-src="logoUrl" alt="">
                </div>
                <div class="wh-widget-hello-popup-content-text" wh-click="showMessengers">
                    <a wh-href="href" wh-target="target">
                        <div wh-html-br="text"></div>
                    </a>
                </div>
                <div class="wh-clear"></div>
            </div>

            <div class="wh-messengers wh-hide"><ul wh-html-element="buttons"></ul></div>
        </div>
    </div>

    <div id="popup-placement" class="popup-slide"></div>
    <div id="popup-placement-form" class="popup-slide contact-form">
    <div class="content-form-footer">
		<header>
		<div class="title-bar-icon-close" wh-click="closePopup">
                <svg fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                    <path d="M0 0h24v24H0z" fill="none"></path>
                </svg>
            </div>
		<h4>Contáctenos</h4>
		<p>Utilice el siguiente formulario para ponerse en contacto con nosotros!</p>
		</header>
			 '.do_shortcode('[contact-form-7 id="'.$a['email'].'" title="form"]').'
		</div>
   </div>
    <div id="wh-call-to-action" class="wh-hide" wh-click="clickOnCallToAction" style="top: 29px;">
        <a wh-href="href" wh-target="target" href="javascript:void(0)" target="">
            <div class="wh-call-to-action-content" wh-html-unsafe="text">Contáctenos</div>
        </a>
    </div>
	<div id="wh-widget-send-button-wrapper-list" class=" wh-widget-send-button-wrapper-list" >
		<a class="wh-widget-button button-slide-out" href="javascript:void(0);" data-action="facebook">
	    	<div class="wh-widget-button-icon wh-messenger-bg-facebook " >
	    		<div>
	    			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-9 -10 41 44" class="wh-messenger-svg-close wh-svg-close"><path d=" M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill-rule="evenodd"></path></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" class="wh-messenger-svg-facebook wh-svg-icon"><path d=" M16 6C9.925 6 5 10.56 5 16.185c0 3.205 1.6 6.065 4.1 7.932V28l3.745-2.056c1 .277 2.058.426 3.155.426 6.075 0 11-4.56 11-10.185C27 10.56 22.075 6 16 6zm1.093 13.716l-2.8-2.988-5.467 2.988 6.013-6.383 2.868 2.988 5.398-2.987-6.013 6.383z" fill-rule="evenodd"></path></svg></div></div><div class=" mes-us">Facebook Messenger</div><div class=" clear"></div>
	    </a>
	    <a class=" wh-widget-button button-slide-out" href="javascript:void(0);" data-action="whatsapp" data-number="%2$s">
	    				<div class=" wh-widget-button-icon wh-messenger-bg-whatsapp">
	    					<div><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-9 -10 41 44" class="wh-messenger-svg-close wh-svg-close"><path d=" M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill-rule="evenodd"></path></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" class="wh-messenger-svg-whatsapp wh-svg-icon"><path d=" M19.11 17.205c-.372 0-1.088 1.39-1.518 1.39a.63.63 0 0 1-.315-.1c-.802-.402-1.504-.817-2.163-1.447-.545-.516-1.146-1.29-1.46-1.963a.426.426 0 0 1-.073-.215c0-.33.99-.945.99-1.49 0-.143-.73-2.09-.832-2.335-.143-.372-.214-.487-.6-.487-.187 0-.36-.043-.53-.043-.302 0-.53.115-.746.315-.688.645-1.032 1.318-1.06 2.264v.114c-.015.99.472 1.977 1.017 2.78 1.23 1.82 2.506 3.41 4.554 4.34.616.287 2.035.888 2.722.888.817 0 2.15-.515 2.478-1.318.13-.33.244-.73.244-1.088 0-.058 0-.144-.03-.215-.1-.172-2.434-1.39-2.678-1.39zm-2.908 7.593c-1.747 0-3.48-.53-4.942-1.49L7.793 24.41l1.132-3.337a8.955 8.955 0 0 1-1.72-5.272c0-4.955 4.04-8.995 8.997-8.995S25.2 10.845 25.2 15.8c0 4.958-4.04 8.998-8.998 8.998zm0-19.798c-5.96 0-10.8 4.842-10.8 10.8 0 1.964.53 3.898 1.546 5.574L5 27.176l5.974-1.92a10.807 10.807 0 0 0 16.03-9.455c0-5.958-4.842-10.8-10.802-10.8z" fill-rule="evenodd"></path></svg></div></div><div class=" mes-us">WhatsApp</div><div class=" clear"></div></a>
	    					<a class=" wh-widget-button button-slide-out" href="javascript:void(0);" data-action="phone" data-number="%3$s"><div class=" wh-widget-button-icon wh-messenger-bg-call"><div><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-9 -10 41 44" class="wh-messenger-svg-close wh-svg-close"><path d=" M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill-rule="evenodd"></path></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-72 -72 704 704" class="wh-messenger-svg-call wh-svg-icon"><path d=" M166.156,512h-41.531c-7.375-0.031-20.563-8.563-20.938-8.906C37.438,437.969,0,348.906,0,255.938 C0,162.719,37.656,73.375,104.281,8.219C104.313,8.188,117.25,0,124.625,0h41.531c15.219,0,33.25,11.125,40.063,24.781l2.906,5.844 c6.781,13.594,6.188,35.563-1.344,48.75l-27.906,48.813c-7.563,13.219-26.188,24.25-41.406,24.25H110.75 c-36.813,64-36.813,143.094-0.031,207.125h27.75c15.219,0,33.844,11.031,41.406,24.25l27.875,48.813 c7.531,13.188,8.156,35.094,1.375,48.781l-2.906,5.844C199.375,500.844,181.375,512,166.156,512z M512,128v256 c0,35.344-28.656,64-64,64H244.688c-1.25-11.219-3.969-22.156-9.156-31.25l-27.875-48.813 c-13.406-23.406-42.469-40.375-69.188-40.375h-8.156c-20.188-45.438-20.188-97.719,0-143.125h8.156 c26.719,0,55.781-16.969,69.188-40.375l27.906-48.813c5.188-9.094,7.906-20.063,9.156-31.25H448C483.344,64,512,92.656,512,128z M328,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S328,381.25,328,368z M328,304c0-13.25-10.75-24-24-24 s-24,10.75-24,24s10.75,24,24,24S328,317.25,328,304z M328,240c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24 S328,253.25,328,240z M392,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S392,381.25,392,368z M392,304 c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S392,317.25,392,304z M392,240c0-13.25-10.75-24-24-24s-24,10.75-24,24 s10.75,24,24,24S392,253.25,392,240z M456,368c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S456,381.25,456,368z M456,304 c0-13.25-10.75-24-24-24s-24,10.75-24,24s10.75,24,24,24S456,317.25,456,304z M456,240c0-13.25-10.75-24-24-24s-24,10.75-24,24 s10.75,24,24,24S456,253.25,456,240z M456,144c0-8.844-7.156-16-16-16H296c-8.844,0-16,7.156-16,16v32c0,8.844,7.156,16,16,16h144 c8.844,0,16-7.156,16-16V144z" fill-rule="evenodd"></path></svg></div></div><div class=" mes-us">Phone</div><div class=" clear"></div></a>
	    					<a class=" wh-widget-button button-slide-out" href="javascript:void(0);" data-action="email" data-email="%4$s"><div class=" wh-widget-button-icon wh-messenger-bg-email"><div><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-9 -10 41 44" class="wh-messenger-svg-close wh-svg-close"><path d=" M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill-rule="evenodd"></path></svg><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" class="wh-messenger-svg-email wh-svg-icon"><path d=" M27 22.757c0 1.24-.988 2.243-2.19 2.243H7.19C5.98 25 5 23.994 5 22.757V13.67c0-.556.39-.773.855-.496l8.78 5.238c.782.467 1.95.467 2.73 0l8.78-5.238c.472-.28.855-.063.855.495v9.087z" fill-rule="evenodd"></path><path d=" M27 9.243C27 8.006 26.02 7 24.81 7H7.19C5.988 7 5 8.004 5 9.243v.465c0 .554.385 1.232.857 1.514l9.61 5.733c.267.16.8.16 1.067 0l9.61-5.733c.473-.283.856-.96.856-1.514v-.465z" fill-rule="evenodd"></path></svg></div></div><div class=" mes-us">Email</div><div class=" clear"></div></a>
	    <a class=" wh-widget-button wh-widget-button-activator" href="javascript:void(0);">
	    	<div class=" wh-widget-button-icon wh-messenger-bg-activator" style="background-color:#eb1b24;"><div><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="-9 -10 41 44" class="wh-messenger-svg-close wh-svg-close"><path d=" M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" fill-rule="evenodd"></path></svg><i class="wh-icon-whatshelp wh-svg-icon"></i></div></div>
	    	<div class=" mes-us">More</div>
	    	<div class=" clear"></div>
	    </a>
	</div>
	<div class=" clear"></div>
</div>',
        esc_url( $a['facebook'] ),
        esc_html( $a['whatsapp'] ),
        esc_html( $a['phone'] ),
        esc_html( $form )
    );

}
add_shortcode( 'botonera', 'botonera_shortcode' );


/**
 * Enqueue scripts and styles.
 */
function wordpress_scripts() {

	wp_enqueue_style( 'wordpress-fonts-botonera', get_template_directory_uri().'/vendor/botonera/css/fonts.css' );
	wp_enqueue_style( 'wordpress-style-botonera', get_template_directory_uri().'/vendor/botonera/css/main.css' );
	wp_enqueue_style( 'wordpress-style', get_stylesheet_uri() );

	wp_enqueue_script( 'wordpress-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'wordpress-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'wordpress-botonera-js', get_template_directory_uri() . '/vendor/botonera/js/botonera.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'wordpress_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

