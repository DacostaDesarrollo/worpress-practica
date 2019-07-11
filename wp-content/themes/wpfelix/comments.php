<?php defined( 'ABSPATH' ) or exit();
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WPFelix
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <div class="comments-wrap clearfix">
            <h3 class="special-title">
                <span class="left-border"></span>
                <span class="title-text"><?php
                    comments_number( esc_html__( 'Responses', 'wpfelix' ),
                        esc_html__( '1 Response', 'wpfelix' ),
                        esc_html__( '% Responses', 'wpfelix' )
                    );
                ?></span>
                <span class="right-border"></span>
            </h3>
            <ol class="comment-list">
                <?php wp_list_comments( 'type=comment&max_depth=3&callback=wpfelix_comment_template' ); ?>
            </ol>
            <?php wpfelix_comments_navigation(); ?>
        </div>
    <?php endif; // have_comments() ?>

    <?php
    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name__mail' );
    $aria_req = ( $req ? " aria-required='true'" : '' );
    $args = array(
        'id_form'           => 'commentform',
        'id_submit'         => 'submit',
        'class_submit'      => 'button button-default button-block',
        'title_reply'       => sprintf(
            '<h3 id="reply-title" class="comment-reply-title"><span class="left-border"></span>
            <span class="title-text">%s</span>
            <span class="right-border"></span></h3>',
            esc_html__( 'Leave A Reply', 'wpfelix' )
        ),
        'title_reply_before' => '<div class="reply-title-wrap">',
        'title_reply_after' => '</div>',
        'title_reply_to'    => esc_html__( 'Reply To %s','wpfelix' ),
        'cancel_reply_before' => '<div class="cancel-reply-link-wrapper">',
        'cancel_reply_after' => '</div>',
        'cancel_reply_link' => esc_html__( 'Cancel Reply','wpfelix'),
        'label_submit'      => esc_html__( 'Submit','wpfelix'),
        'submit_button'     => '<button name="%1$s" type="submit" id="%2$s" class="%3$s"><strong>%4$s</strong></button>',
        'comment_notes_before' => sprintf( '<p class="comment-form-field-notes">%s</p>', esc_html__( '* All fields are required', 'wpfelix' ) ),
        'fields' => apply_filters( 'comment_form_default_fields', array(
                'author' =>
                '<p class="comment-form-author">' .
                '<input class="form-field" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
                '" size="30"' . $aria_req . ' placeholder="' . esc_html__( 'Your Name *', 'wpfelix' ) . '"/></p>',

                'email' =>
                '<p class="comment-form-email">'.
                '<input class="form-field" id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' placeholder="' . esc_html__( 'Your Email *', 'wpfelix' ) . '"/></p>'
            )
        ),
        'comment_field' =>  '<p class="comment-form-comment"><textarea id="comment" class="form-field" name="comment" cols="45" rows="6" placeholder="' . 
        esc_html__( 'Write Comment *', 'wpfelix' ) . '" aria-required="true">' .
        '</textarea></p>',
    );
    
    comment_form( $args );
    ?>
</div><!-- #comments -->
