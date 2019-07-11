<?php
/**
 * The template for displaying author info box in single post.
 *
 * @package WPFelix
 */

if ( ! get_theme_mod( 'post_author_box', true ) )
{
    return;
}

$author = get_user_by( 'id', get_the_author_meta( 'ID' ) );

if ( ! $author )
{
    return;
}

?>
<div class="post-author-box post-author-<?php the_ID(); ?>">
    <div class="author-body">
        <div class="author-avatar">
            <div class="avatar-img">
            <?php echo get_avatar( $author->ID, 140 ); ?>
            </div>
        </div>
        <div class="author-info">
            <div class="author-header">
                <?php
                printf(
                    '<h4 class="entry-title author-name"><a href="%1$s">%2$s</a></h4>',
                    esc_url( get_author_posts_url( $author->ID ) ),
                    $author->display_name
                );
                wpfelix_author_social();
                ?>
            </div>
            <div class="author-description">
            <?php the_author_meta( 'description', $author->ID ); ?> 
            </div>
        </div>
    </div>
</div>
