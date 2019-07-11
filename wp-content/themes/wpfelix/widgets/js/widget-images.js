/**
 * Media selector for wpfelix images.
 *
 * Author:      Stev Ngo
 * AuthorURI:   http://stevngodesign.com/
 */
jQuery( document ).ready( function( $ ) {

    //-- Primary widget script object
    WPFelixImagesWidget = {

        //-- This function will be called on clicking at "add" icon
        add_images: function( event, widget_id ) {
            event.preventDefault();
            var _this = this;
            var frame = wp.media({
                className: 'media-frame wpfelix-media-frame',
                title : WPFelixImagesWidgetFrame.frame_title,
                multiple : true,
                library : { type : 'image' },
                button : { text : WPFelixImagesWidgetFrame.button_title }
            });

            //-- Process results from uploader.
            frame.off( 'close' ).on( 'close', function() {
                var attachments = frame.state().get( 'selection' );
                _this.render_images( widget_id, attachments );
            });

            frame.open();
        },

        //-- Render choosen images to widget
        render_images: function( widget_id, attachments ) {
            var _this = this;
            var image_preview = $( '#' + widget_id + ' > ul.images' );

            if ( image_preview.length == 0 ) return;

            image_preview = image_preview.first();

            attachments.map( function( attachment ){
                attachment = attachment.toJSON();

                if ( undefined != typeof attachment.id ) {
                    image_preview.prepend(
                        '<li data-id="' + attachment.id + '"' + 
                            ' style="background-image:url(' + attachment.url + ');">' +
                            '<a class="image-edit" href="#" onclick="WPFelixImagesWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                                '<i class="dashicons dashicons-edit"></i>' +
                            '</a>' +
                            '<a class="image-delete" href="#" onclick="WPFelixImagesWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                                '<i class="dashicons dashicons-trash"></i>' +
                            '</a>' +
                        '</li>'
                    );
                }
            } );

            image_preview.sortable({
                items: "> *:not(:last-child)",
                stop: function( event, ui ) {
                    _this.generate_values( widget_id );
                }
            });

            _this.generate_values( widget_id );
        },

        //-- Render changed image
        render_changed_image: function( widget_id, attachment, image_id ) {
            var item = $( '#' + widget_id + ' li[data-id="' + image_id + '"]' );
            if ( undefined != typeof attachment.id ) {
                item.html(
                    '<a class="image-edit" href="#" onclick="WPFelixImagesWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                        '<i class="dashicons dashicons-edit"></i>' +
                    '</a>' +
                    '<a class="image-delete" href="#" onclick="WPFelixImagesWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
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
                title : WPFelixImagesWidgetFrame.frame_edit_title,
                multiple : false,
                library : { type : 'image' },
                button : { text : WPFelixImagesWidgetFrame.button_edit_title }
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
            var image_previews = $( '#' + widget_id + ' > ul.images > li' ),
                image_ids = [],
                field_value = $( '#' + widget_id + 'images' );

            image_previews.each( function() {
                var image_id = $(this).data('id');
                if ( undefined != image_id && ! isNaN( image_id ) && image_id != 0 ) {
                    image_ids.push( image_id );
                }
            } );
            
            if ( image_ids.length > 0 ) {
                field_value.val( image_ids.join( "," ) );
            }
        }
    }
} );