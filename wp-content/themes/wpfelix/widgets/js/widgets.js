/**
 * Media selector for WPFelix Image and About Widgets.
 *
 * Author:      Stev Ngo
 * AuthorURI:   http://stevngodesign.com/
 */
jQuery( document ).ready( function( $ ) {

    //-- Primary widget script object
    WPFelixImageWidget = {

        //-- This function will be called on clicking at "add" icon
        add_image: function( event, widget_id ) {
            event.preventDefault();
            var _this = this;
            var frame = wp.media({
                className: 'media-frame wpfelix-media-frame',
                multiple : false,
                library : { type : 'image' }
            });

            //-- Process results from uploader.
            frame.off( 'close' ).on( 'close', function() {
                var attachment = frame.state().get( 'selection' );

                if ( undefined != typeof attachment ) {
                    _this.render_image( widget_id, attachment );
                }
            });

            frame.open();
        },

        //-- Render choosen image to widget
        render_image: function( widget_id, attachment ) {
            var _this = this;
            var image_preview = $( '#' + widget_id + ' > ul.image' );

            if ( image_preview.length == 0 ) return;

            image_preview = image_preview.first();

            attachment = attachment.first().toJSON();

            if ( image_preview.children().length > 1 ) {
                image_preview.children().first().remove();
            }
            if ( undefined != typeof attachment.id ) {
                image_preview.prepend(
                    '<li data-id="' + attachment.id + '"' + 
                        ' style="background-image:url(' + attachment.url + ');">' +
                        '<a class="image-edit" href="#" onclick="WPFelixImageWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                            '<i class="dashicons dashicons-edit"></i>' +
                        '</a>' +
                        '<a class="image-delete" href="#" onclick="WPFelixImageWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                            '<i class="dashicons dashicons-trash"></i>' +
                        '</a>' +
                    '</li>'
                );
            }

            _this.generate_values( widget_id );
        },

        //-- Render changed image
        render_changed_image: function( widget_id, attachment, image_id ) {
            var item = $( '#' + widget_id + ' li[data-id="' + image_id + '"]' );
            if ( undefined != typeof attachment.id ) {
                item.html(
                    '<a class="image-edit" href="#" onclick="WPFelixImageWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                        '<i class="dashicons dashicons-edit"></i>' +
                    '</a>' +
                    '<a class="image-delete" href="#" onclick="WPFelixImageWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                        '<i class="dashicons dashicons-trash"></i>' +
                    '</a>'
                );
                item.attr( 'data-id', attachment.id );
                item.css({
                    'background-image': 'url(' + attachment.url + ')'
                });
            }
        },

        //-- Edit
        edit_image: function( event, widget_id, image_id ) {
            event.preventDefault();
            var _this = this;
            var frame = wp.media({
                className: 'media-frame wpfelix-media-frame',
                multiple : false,
                library : { type : 'image' }
            });

            frame.off( 'open' ).on( 'open',function() {
                var selection = frame.state().get('selection');
                var attachment = wp.media.attachment( image_id );
                selection.add( attachment ? attachment : '' );
            } );

            //-- Process results from uploader.
            frame.off( 'close' ).on( 'close', function() {
                var attachment = frame.state().get( 'selection' ).first().toJSON();
                _this.render_changed_image( widget_id, attachment, image_id );
                _this.generate_values( widget_id );
            });

            frame.open();
        },

        //-- Remove
        remove_image: function( event, widget_id, image_id ) {
            event.preventDefault();
            $( '#' + widget_id + ' li[data-id="' + image_id + '"]' ).remove();
            this.generate_values( widget_id );
        },

        //-- Generate values
        generate_values: function( widget_id ) {
            var image_preview = $( '#' + widget_id + ' > ul.image > li' ),
                field_value = $( '#' + widget_id + 'image' );

            image_preview = image_preview.first();

            var image_id = image_preview.data('id');
            if ( undefined != image_id && ! isNaN( image_id ) && image_id != 0 ) {
                field_value.val( image_id );
            }
            else {
                field_value.val( '' );
            }
        }
    }
} );