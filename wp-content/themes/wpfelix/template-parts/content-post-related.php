<?php
/**
 * The template for displaying related posts in single post.
 *
 * @package WPFelix
 */

if ( ! get_theme_mod( 'post_related', true ) )
{
    return;
}

$tags = wp_get_post_tags( get_the_ID(), array( 'fields' => 'ids' ) );
$cats = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );

$query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'post__not_in' => array( get_the_ID() ),
    'ignore_sticky_posts' => true,
    'posts_per_page' => 3,
    'orderby' => 'rand'
);

if ( ! empty( $tags ) && ! empty( $cats ) )
{
    $query_args['tax_query'] = array(
        'relation'  => 'OR',
        array(
            'taxonomy'         => 'post_tag',
            'field'            => 'id',
            'terms'            => $tags,
            'include_children' => false,
            'operator'         => 'IN'
        ),
        array(
            'taxonomy'         => 'category',
            'field'            => 'id',
            'terms'            => $cats,
            'include_children' => false,
            'operator'         => 'IN'
        )
    );
}
else
{
    if ( ! empty( $tags ) )
    {
        $query_args['tag__in'] = $tags;
    }

    if ( ! empty( $cats ) )
    {
        $query_args['category__in'] = $cats;
    }
}

$query = new WP_Query( $query_args );

if ( ! $query->have_posts() )
{
    return;
}

?>
<div class="posts-related">
    <h3 class="special-title">
        <span class="left-border"></span>
        <span class="title-text"><?php esc_html_e( 'You Might Also Like', 'wpfelix' ); ?></span>
        <span class="right-border"></span>
    </h3>
    <div class="grid grid-gs">
        <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <div class="entry-related grid-m4">
            <?php
            if ( has_post_thumbnail() )
            {
                printf( '<a class="entry-featured" href="%1$s">%2$s</a>', esc_url( get_permalink() ), get_the_post_thumbnail( get_the_ID(), 'medium' ) );
            }
            the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h4>' );
            printf(
                '<div class="entry-posted-on">
                    <span class="screen-reader-text">%1$s</span>
                    <a href="%2$s" rel="bookmark">
                        <time class="entry-date" datetime="%3$s">%4$s</time>
                    </a>
                </div>',
                esc_html__( 'Posted on', 'wpfelix' ),
                esc_url( get_permalink() ),
                get_the_date( 'c' ),
                get_the_date( get_option( 'date_format' ) )
            );
            ?>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<?php wp_reset_postdata(); ?>