/* global wpFelixColorScheme, Color */
/**
 * Add a listener to the Color Scheme control to update other color controls to new values/defaults.
 * Also trigger an update of the Color Scheme CSS when a color is changed.
 */

( function( api ) {
    var cssTemplate = wp.template( 'wpfelix-color-scheme' ),
        colorSchemeKeys = [
            'background_color',
            'background_color_alt',
            'text_color',
            'text_strong_color',
            'text_weak_color',
            'accent_color',
            'border_color',
            'button_text_color',
            'button_hover_text_color',
            'button_hover_background_color',
            'background_color_header',
            'background_color_navbar',
            'border_color_navbar',
            'link_color_navbar',
            'link_active_color_navbar',
            'border_color_navsub',
            'background_color_navsub',
            'link_color_navsub',
            'link_active_color_navsub',
            'link_active_background_color_navsub',
            'text_color_category',
            'border_color_category',
            'background_color_category',
            'text_color_widget_title',
            'border_color_widget_title',
            'background_color_widget_title',
            'background_color_footer',
            'text_color_footer',
            'text_strong_color_footer',
            'text_weak_color_footer',
            'border_color_footer'
        ],
        colorSettings = [
            'background_color',
            'background_color_alt',
            'text_color',
            'text_strong_color',
            'text_weak_color',
            'accent_color',
            'border_color',
            'button_text_color',
            'button_hover_text_color',
            'button_hover_background_color',
            'background_color_header',
            'background_color_navbar',
            'border_color_navbar',
            'link_color_navbar',
            'link_active_color_navbar',
            'border_color_navsub',
            'background_color_navsub',
            'link_color_navsub',
            'link_active_color_navsub',
            'link_active_background_color_navsub',
            'text_color_category',
            'border_color_category',
            'background_color_category',
            'text_color_widget_title',
            'border_color_widget_title',
            'background_color_widget_title',
            'background_color_footer',
            'text_color_footer',
            'text_strong_color_footer',
            'text_weak_color_footer',
            'border_color_footer'
        ];

    api.controlConstructor.select = api.Control.extend( {
        ready: function() {
            if ( 'color_scheme' === this.id ) {
                this.setting.bind( 'change', function( value ) {
                    // Update Background Color.
                    var color = wpFelixColorScheme[value].colors[0];
                    api( 'background_color' ).set( color );
                    api.control( 'background_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Background Alternate Color.
                    color = wpFelixColorScheme[value].colors[1];
                    api( 'background_color_alt' ).set( color );
                    api.control( 'background_color_alt' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Text Color.
                    color = wpFelixColorScheme[value].colors[2];
                    api( 'text_color' ).set( color );
                    api.control( 'text_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Text Highlight Color.
                    color = wpFelixColorScheme[value].colors[3];
                    api( 'text_strong_color' ).set( color );
                    api.control( 'text_strong_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Text Muted Color.
                    color = wpFelixColorScheme[value].colors[4];
                    api( 'text_weak_color' ).set( color );
                    api.control( 'text_weak_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Accent Color
                    color = wpFelixColorScheme[value].colors[5];
                    api( 'accent_color' ).set( color );
                    api.control( 'accent_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Border Color
                    color = wpFelixColorScheme[value].colors[6];
                    api( 'border_color' ).set( color );
                    api.control( 'border_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Button Color
                    color = wpFelixColorScheme[value].colors[7];
                    api( 'button_text_color' ).set( color );
                    api.control( 'button_text_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Button Hover Color
                    color = wpFelixColorScheme[value].colors[8];
                    api( 'button_hover_text_color' ).set( color );
                    api.control( 'button_hover_text_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Button Hover Background Color
                    color = wpFelixColorScheme[value].colors[9];
                    api( 'button_hover_background_color' ).set( color );
                    api.control( 'button_hover_background_color' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Header Background Color
                    color = wpFelixColorScheme[value].colors[10];
                    api( 'background_color_header' ).set( color );
                    api.control( 'background_color_header' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Background Color
                    color = wpFelixColorScheme[value].colors[11];
                    api( 'background_color_navbar' ).set( color );
                    api.control( 'background_color_navbar' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Border Color
                    color = wpFelixColorScheme[value].colors[12];
                    api( 'border_color_navbar' ).set( color );
                    api.control( 'border_color_navbar' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Link Color
                    color = wpFelixColorScheme[value].colors[13];
                    api( 'link_color_navbar' ).set( color );
                    api.control( 'link_color_navbar' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Link Active Color
                    color = wpFelixColorScheme[value].colors[14];
                    api( 'link_active_color_navbar' ).set( color );
                    api.control( 'link_active_color_navbar' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Submenu Border Color
                    color = wpFelixColorScheme[value].colors[15];
                    api( 'border_color_navsub' ).set( color );
                    api.control( 'border_color_navsub' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Submenu Background Color
                    color = wpFelixColorScheme[value].colors[16];
                    api( 'background_color_navsub' ).set( color );
                    api.control( 'background_color_navsub' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Submenu Link Color
                    color = wpFelixColorScheme[value].colors[17];
                    api( 'link_color_navsub' ).set( color );
                    api.control( 'link_color_navsub' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Submenu Link Active Color
                    color = wpFelixColorScheme[value].colors[18];
                    api( 'link_active_color_navsub' ).set( color );
                    api.control( 'link_active_color_navsub' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Nav Submenu Link Active Background Color
                    color = wpFelixColorScheme[value].colors[19];
                    api( 'link_active_background_color_navsub' ).set( color );
                    api.control( 'link_active_background_color_navsub' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Category Links Text Color
                    color = wpFelixColorScheme[value].colors[20];
                    api( 'text_color_category' ).set( color );
                    api.control( 'text_color_category' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Category Links Border Color
                    color = wpFelixColorScheme[value].colors[21];
                    api( 'border_color_category' ).set( color );
                    api.control( 'border_color_category' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Category Links Background Color
                    color = wpFelixColorScheme[value].colors[22];
                    api( 'background_color_category' ).set( color );
                    api.control( 'background_color_category' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Widget Title Text Color
                    color = wpFelixColorScheme[value].colors[23];
                    api( 'text_color_widget_title' ).set( color );
                    api.control( 'text_color_widget_title' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Widget Title Border Color
                    color = wpFelixColorScheme[value].colors[24];
                    api( 'border_color_widget_title' ).set( color );
                    api.control( 'border_color_widget_title' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Widget Title Border Color
                    color = wpFelixColorScheme[value].colors[25];
                    api( 'background_color_widget_title' ).set( color );
                    api.control( 'background_color_widget_title' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Footer Background Color
                    color = wpFelixColorScheme[value].colors[26];
                    api( 'background_color_footer' ).set( color );
                    api.control( 'background_color_footer' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Foter Text Color
                    color = wpFelixColorScheme[value].colors[27];
                    api( 'text_color_footer' ).set( color );
                    api.control( 'text_color_footer' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Foter Highlight Color
                    color = wpFelixColorScheme[value].colors[28];
                    api( 'text_strong_color_footer' ).set( color );
                    api.control( 'text_strong_color_footer' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Foter Muted Color
                    color = wpFelixColorScheme[value].colors[29];
                    api( 'text_weak_color_footer' ).set( color );
                    api.control( 'text_weak_color_footer' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                    // Update Foter Border Color
                    color = wpFelixColorScheme[value].colors[30];
                    api( 'border_color_footer' ).set( color );
                    api.control( 'border_color_footer' ).container.find( '.rgba-color-control' )
                        .data( 'data-default-color', color )
                        .wpColorPicker( 'color', color );

                } );
            }
        }
    } );

    // Generate the CSS for the current Color Scheme.
    function updateCSS() {
        var scheme = api( 'color_scheme' )(), css,
            colors = _.object( colorSchemeKeys, wpFelixColorScheme[ scheme ].colors );

        // Merge in color scheme overrides.
        _.each( colorSettings, function( setting )
        {
            colors[ setting ] = api( setting )();
        });

        //console.log( colors );

        css = cssTemplate( colors );

        api.previewer.send( 'wpfelix-update-color-scheme-css', css );
    }

    // Update the CSS whenever a color setting is changed.
    _.each( colorSettings, function( setting ) {
        api( setting, function( setting ) {
            setting.bind( updateCSS );
        } );
    } );
} )( wp.customize );
