/*--------------------------------------------------------------
# Element classList polyfill
--------------------------------------------------------------*/
/**
 * Element classList polyfill
 */
;( function()
{
    "use strict";

    // helpers
    var regExp = function( name )
    {
        return new RegExp('(^| )'+ name +'( |$)');
    };

    var forEach = function( list, fn, scope )
    {
        for ( var i = 0; i < list.length; i++ )
        {
            fn.call( scope, list[ i ] );
        }
    };

    // class list object with basic methods
    function ClassList( element )
    {
        this.element = element;
    }

    ClassList.prototype.add = function()
    {
        forEach( arguments, function( name )
        {
            if ( ! this.contains( name ) )
            {
                this.element.className += this.element.className.length > 0 ? ' ' + name : name;
            }
        }, this );
    };

    ClassList.prototype.remove = function()
    {
        forEach( arguments, function( name )
        {
            this.element.className = this.element.className.replace( regExp( name ), '' );
        }, this );
    };

    ClassList.prototype.toggle = function( name )
    {
        return this.contains( name ) ? ( this.remove( name ), false ) : ( this.add( name ), true );
    };

    ClassList.prototype.contains = function( name )
    {
        return regExp( name ).test( this.element.className );
    };

    ClassList.prototype.replace = function( oldName, newName )
    {
        this.remove( oldName ), this.add( newName );
    };

    // IE8/9, Safari
    if ( ! ( 'classList' in Element.prototype ) )
    {
        Object.defineProperty( Element.prototype, 'classList',
        {
            get: function()
            {
                return new ClassList( this );
            }
        } );
    }

    // replace() support for others
    if ( window.DOMTokenList && DOMTokenList.prototype.replace == null )
    {
        DOMTokenList.prototype.replace = ClassList.prototype.replace;
    }
} )();


/**
 * Overwrites native 'firstElementChild' prototype.
 * Adds Document & DocumentFragment support for IE9 & Safari.
 * Returns array instead of HTMLCollection.
 */
;( function( constructor )
{
    if ( constructor &&
        constructor.prototype &&
        constructor.prototype.firstElementChild == null )
    {
        Object.defineProperty( constructor.prototype, 'firstElementChild',
        {
            get: function()
            {
                var node, nodes = this.childNodes, i = 0;

                while ( node = nodes[ i++] )
                {
                    if ( node.nodeType === 1 )
                    {
                        return node;
                    }
                }
                return null;
            }
        });
    }
})( window.Node || window.Element );


/**
 * Overwrites native 'lastElementChild' prototype.
 * Adds Document & DocumentFragment support for IE9 & Safari.
 * Returns array instead of HTMLCollection.
 */
;( function( constructor )
{
    if ( constructor &&
        constructor.prototype &&
        constructor.prototype.lastElementChild == null )
    {
        Object.defineProperty( constructor.prototype, 'lastElementChild',
        {
            get: function()
            {
                var node, nodes = this.childNodes, i = nodes.length - 1;

                while ( node = nodes[ i--] )
                {
                    if ( node.nodeType === 1 )
                    {
                        return node;
                    }
                }
                return null;
            }
        });
    }
})( window.Node || window.Element );


/**
 * previousElementSibling
 * Source: https://github.com/jserz/js_piece/blob/master/DOM/NonDocumentTypeChildNode/previousElementSibling/previousElementSibling.md
 */
;( function( arr )
{
    arr.forEach( function( item )
    {
        if ( item.hasOwnProperty( 'previousElementSibling' ) )
        {
            return;
        }

        Object.defineProperty( item, 'previousElementSibling',
        {
            configurable: true,
            enumerable: true,
            get: function()
            {
                var el = this;
                while ( el = el.previousSibling )
                {
                    if ( el.nodeType === 1 )
                    {
                        return el;
                    }
                }
                return null;
            },
            set: undefined
        });
    });
})( [ Element.prototype, CharacterData.prototype ] );


/**
 * nextElementSibling
 * Source: https://github.com/jserz/js_piece/blob/master/DOM/NonDocumentTypeChildNode/nextElementSibling/nextElementSibling.md
 */
;( function( arr )
{
    arr.forEach( function( item )
    {
        if ( item.hasOwnProperty( 'nextElementSibling' ) )
        {
            return;
        }

        Object.defineProperty( item, 'nextElementSibling',
        {
            configurable: true,
            enumerable: true,
            get: function()
            {
                var el = this;
                while ( el = el.nextSibling )
                {
                    if ( el.nodeType === 1 )
                    {
                        return el;
                    }
                }
                return null;
            },
            set: undefined
        });
    });
})( [ Element.prototype, CharacterData.prototype ] );

;( function()
{
    "use strict";

    if ( window.Element && ! Element.prototype.closest )
    {
        /**
         * returns the closest ancestor of the current element (or the current element itself)
         * which matches the selectors given in parameter.
         * If there isn't such an ancestor, it returns null.
         * @param  {String} s
         * @return {Element}
         */
        Element.prototype.closest = function( s )
        {
            var matches = ( this.document || this.ownerDocument ).querySelectorAll( s ),
                i,
                el = this;

            do {
                i = matches.length;
                while ( --i >= 0 && matches.item( i ) !== el ) {}
            }
            while ( ( i < 0 ) && ( el = el.parentElement ) );

            return el;
        };
    }

    if ( typeof Object.assign != 'function' )
    {
        /**
         * Copy the values of all enumerable own properties from one or more source objects to a target object.
         * It will return the target object
         * @param  {Object} target
         * @param  {Object} varArgs One or more object to be assign to target.
         * @return {Object}
         */
        Object.assign = function( target, varArgs ) // .length of function is 2
        {
            'use strict';
            if ( target == null ) // TypeError if undefined or null
            {
                throw new TypeError( 'Cannot convert undefined or null to object' );
            }

            var to = Object( target );

            for ( var index = 1; index < arguments.length; index++ )
            {
                var nextSource = arguments[ index ];

                if ( nextSource != null ) // Skip over if undefined or null
                {
                    for ( var nextKey in nextSource )
                    {
                        // Avoid bugs when hasOwnProperty is shadowed
                        if ( Object.prototype.hasOwnProperty.call( nextSource, nextKey ) )
                        {
                            to[ nextKey ] = nextSource[ nextKey ];
                        }
                    }
                }
            }
            return to;
        };
    }
} )();


/**
 * Additions
 */
;( function()
{
    if ( window.Element && ! Element.prototype.hasClassStartingWith )
    {
        /**
         * Check if element contain a css class starting with specific string
         * @param  {string}  s string to check
         * @return {Boolean}
         */
        Element.prototype.hasClassStartingWith = function( s )
        {
            var re = new RegExp( "\\s\\b" + s ),
                el = this;
            return re.test( ' ' + el.className );
        };
    }

    if ( window.Element && ! Element.prototype.getInnerDimensions )
    {
        /**
         * Get element width without paddings and borders
         * @param  {Boolean}  b include border or not
         * @return {Object}   contains width and height
         */
        Element.prototype.getInnerDimensions = function( b )
        {
            var cs = getComputedStyle( this, null ),
                pt, pr, pb, pl, bt, br, bb, bl,
                dimensions = {};

            pt = parseFloat( cs.getPropertyValue( 'padding-top' ) );
            pr = parseFloat( cs.getPropertyValue( 'padding-right' ) );
            pb = parseFloat( cs.getPropertyValue( 'padding-bottom' ) );
            pl = parseFloat( cs.getPropertyValue( 'padding-left' ) );

            dimensions.width = Number( ( this.offsetWidth - ( pl + pr ) ).toFixed( 10 ) );
            dimensions.height = Number( ( this.offsetHeight - ( pt + pb ) ).toFixed( 10 ) );

            if ( ! b )
            {
                bt = parseFloat( cs.getPropertyValue( 'border-top-width' ) );
                br = parseFloat( cs.getPropertyValue( 'border-right-width' ) );
                bb = parseFloat( cs.getPropertyValue( 'border-bottom-width' ) );
                bl = parseFloat( cs.getPropertyValue( 'border-left-width' ) );

                dimensions.width = Number( ( dimensions.width - bl - br ).toFixed( 10 ) );
                dimensions.height = Number( ( dimensions.height - bt - bb ).toFixed( 10 ) );
            }

            return dimensions;
        };
    }
} )();
