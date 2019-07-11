<?php
/**
 * Search form in HTML5 Format for the theme
 *
 * @package WPFelix
 */
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'wpfelix' ); ?></span>
        <input type="search" class="form-field search-field" placeholder="<?php esc_html_e( 'Search&hellip;', 'wpfelix' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit"><i class="fa fa-search"></i></button>
</form>