<?php
class WPFelix_Style_Generator
{
    function __construct()
    {
        if ( is_customize_preview() )
        {
            add_action( 'wp_head', array( $this, 'render_customizer_css' ) );
            add_action( 'customize_save_after', array( $this, 'render_css' ) );
        }
    }


    function render_customizer_css()
    {
        ob_start();
        echo 'a{color:red;}';
        $output = ob_get_clean();
        printf( '<style type="text/css">%s</style>', $output );
    }


    function render_css()
    {
    }
}
new WPFelix_Style_Generator();