;( function( $ )
{
    "use strict";

    var touchSupport   = false,
        isCustomize    = false,
        scrollBarWidth = 0,
        adminBar;

    document.addEventListener( 'DOMContentLoaded', function()
    {
        isCustomize    = document.querySelector( '[customize-partial-id]' ) ? true : false;
        scrollBarWidth = window.innerWidth - $( window ).width();
        adminBar       = document.getElementById( 'wpadminbar' );

        skipLinkFocusFix();

        if ( $.fn.fitVids )
        {
            $( '#page' ).fitVids();
        }

        var app = new Felix();

        //felixSidebar();

        if ( 'true' == $( '#page' ).attr( 'data-jpk-infinite' ) )
        {
            $( document.body ).on( 'post-load', function( event, response )
            {
                if ( response.type != 'success' )
                {
                    return;
                }

                if ( $.fn.fitVids )
                {
                    $( '#page' ).fitVids();
                }
            } );
        }
    });

    window.addEventListener( 'load', function()
    {
        var sidebar = new FelixSidebar();
    });

    /**
     * Helps with accessibility for keyboard only users.
     *
     * Learn more: https://git.io/vWdr2
     */
    function skipLinkFocusFix()
    {
        var isWebkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
            isOpera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
            isIe     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

        if ( ( isWebkit || isOpera || isIe ) && document.getElementById && window.addEventListener )
        {
            window.addEventListener( 'hashchange', function()
            {
                var id = location.hash.substring( 1 ),
                    element;

                if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) )
                {
                    return;
                }

                element = document.getElementById( id );

                if ( element )
                {
                    if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) )
                    {
                        element.tabIndex = -1;
                    }

                    element.focus();
                }
            }, false );
        }
    }

    /**
     * Generate an random unique id with 4 characters
     */
    function guniqid()
    {
        var guid = function() {
            return Math.floor( ( 1 + Math.random() ) * 0x10000 ).toString( 16 ).substring( 1 );
        };
        return guid() + guid();
    }

    /**
     * Simple forEach loop for array-like object
     * @param  {array-like object} list]
     * @param  {Function}  fn, callback function, agrument: item, index
     * @param  {mixed}     scope
     */
    function forEach( list, fn, scope )
    {
        for ( var i = 0; i < list.length; i++ )
        {
            fn.call( scope, list[ i ], i );
        }
    }

    /**
     * Get siblings of element filtered by selector
     * @param  {Element} el
     * @param  {string}  selector
     * @return {array}
     */
    function siblings( el, selector )
    {
        var filterEls = null,
            id = null,
            results = [];

        if ( selector )
        {
            if ( ! el.parentNode.id )
            {
                id = el.tagName.toLowerCase() + '-' + Math.floor( ( 1 + Math.random() ) * 0x10000 ).toString( 16 ).substring( 1 ) + '-' + Date.now();
                el.parentNode.setAttribute( 'id', id );

                filterEls = document.querySelectorAll( '#' + el.parentNode.id + '>' + selector );
                el.parentNode.removeAttribute( 'id' );
            }
            else
            {
                filterEls = document.querySelectorAll( '#' + el.parentNode.id + '>' + selector );
            }

            if ( filterEls )
            {
                forEach( filterEls, function( child )
                {
                    if ( child !== el )
                    {
                        results.push( child );
                    }
                });
            }

            return results;
        }
        else
        {
            return Array.prototype.filter.call( el.parentNode.children, function( child )
            {
                return child !== el;
            });
        }
    }

    /**
     * Get child elements filtered by selctor. If no selector is parsed, return all children.
     * @param  {Element} el
     * @param  {string}  selector
     * @return {array}
     */
    function children( el, selector )
    {
        var id = null,
            filterEls = [];

        if ( selector )
        {
            if ( ! el.id )
            {
                id = el.tagName.toLowerCase() + '-' + Math.floor( ( 1 + Math.random() ) * 0x10000 ).toString( 16 ).substring( 1 ) + '-' + Date.now();
                el.setAttribute( 'id', id );

                filterEls = document.querySelectorAll( '#' + el.id + '>' + selector );

                el.removeAttribute( 'id' );
            }
            else
            {
                filterEls = document.querySelectorAll( '#' + el.id + '>' + selector );
            }

            return Array.prototype.slice.call( filterEls );
        }
        else
        {
            return el.children;
        }
    }

    /**
     * Get the position of an element relative to the document
     * @param  {Element} el
     * @return {object}  Contains top and left;
     */
    function offset( el )
    {
        var rect = el.getBoundingClientRect(),
            scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
            scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        return {
            top: rect.top + scrollTop,
            left: rect.left + scrollLeft
        };
    }

    /**
     * Get element width and height with paddings, without borders or margins.
     * This does not include padding because offsetHeight, offsetWidth already did that.
     * @param  {Boolean}  b include border or not
     * @return {Object}   contains width and height
     */
    function getInnerDimensions( el )
    {
        var d = {
                width: el.offsetWidth,
                height: el.offsetHeight
            },
            cs = getComputedStyle( el, null ),
            bt, br, bb, bl;

        bt = parseFloat( cs.getPropertyValue( 'border-top-width' ) );
        br = parseFloat( cs.getPropertyValue( 'border-right-width' ) );
        bb = parseFloat( cs.getPropertyValue( 'border-bottom-width' ) );
        bl = parseFloat( cs.getPropertyValue( 'border-left-width' ) );

        d.width = Number( ( d.width - bl - br ).toFixed( 10 ) );
        d.height = Number( ( d.height - bt - bb ).toFixed( 10 ) );

        return d;
    };

    /**
     * Get element width and height without margins, paddings and borders
     * @param  {Boolean}  p include paddings or not. Default to true
     * @param  {Boolean}  m include margins or not. Default to false
     * @param  {Boolean}  b include borders or not. Default to true
     * @return {Object}   contains width and height
     */
    function getDimensions( el )
    {
        var d  = getInnerDimensions( el ),
            cs = getComputedStyle( el, null ),
            pt, pr, pb, pl;

        pt = parseFloat( cs.getPropertyValue( 'padding-top' ) );
        pr = parseFloat( cs.getPropertyValue( 'padding-right' ) );
        pb = parseFloat( cs.getPropertyValue( 'padding-bottom' ) );
        pl = parseFloat( cs.getPropertyValue( 'padding-left' ) );

        d.width  = Number( ( d.width - pl - pr ).toFixed( 10 ) );
        d.height = Number( ( d.height - pt - pb ).toFixed( 10 ) );

        return d;
    };

    /**
     * Get element width and height with border, paddings and maybe margins
     * @param  {Boolean}  m include margins or not. Default to false
     * @return {Object}   contains width and height
     */
    function getOuterDimensions( el, m )
    {
        var d = {
                width: el.offsetWidth,
                height: el.offsetHeight
            },
            cs = getComputedStyle( el, null ),
            mt, mr, mb, ml;

        if ( ! m )
        {
            return d;
        }

        mt = parseFloat( cs.getPropertyValue( 'margin-top' ) );
        mr = parseFloat( cs.getPropertyValue( 'margin-right' ) );
        mb = parseFloat( cs.getPropertyValue( 'margin-bottom' ) );
        ml = parseFloat( cs.getPropertyValue( 'margin-left' ) );

        d.width = Number( ( d.width + ml + mr ).toFixed( 10 ) );
        d.height = Number( ( d.height + mt + mb ).toFixed( 10 ) );

        return d;
    };

    /**
     * Check and return supported transition event name
     * @return {string}
     */
    function whichTransitionEnd()
    {
        var t,
        el = document.createElement( "fakeelement" );

        var transitions = {
            "transition"      : "transitionend",
            "OTransition"     : "oTransitionEnd",
            "MozTransition"   : "transitionend",
            "WebkitTransition": "webkitTransitionEnd"
        };

        for ( t in transitions )
        {
            if ( el.style[ t ] !== undefined )
            {
                return transitions[ t ];
            }
        }
    }

    /**
     * Dispatch custom events
     *
     * @param  {Element} el         element
     * @param  {String}  type       custom event name
     * @param  {Object}  detail     custom detail information
     */
    function dispatchEvent( target, type, detail )
    {
        var event = new CustomEvent( type, {
            bubbles: true,
            cancelable: true,
            detail: detail
        });
    
        target.dispatchEvent( event );
    }

    /**
     * Dispatch Event on Element
     * @param  {Element} el
     * @param  {String}  phase
     * @param  {String}  type
     * @param  {Object}  detail
     */
    function dispatchElementEvent( el, phase, type, detail )
    {
        dispatchEvent( el, 'felix.' + phase + '.' + type, detail );
    }

    /**
     * Smart sticky sidebar
     */
    function FelixSidebar()
    {
        this.fnSetupProps();

        if ( ! this.el || ! this.wrap )
        {
            return;
        }

        var self = this;
        self.resizeTimeout = 200;

        self.fnCalc();

        self.onScroll = self.fnScroll.bind( self );

        window.addEventListener( 'scroll', self.onScroll );
        window.addEventListener( 'resize', self.fnResize.bind( self ) );

        clearTimeout( self.resizeTimeout );
    }

    FelixSidebar.prototype.fnSetupProps = function()
    {
        var secondary = document.getElementById( 'secondary' ),
            fixedNav  = document.getElementById( 'site-navigation' );

        this.el = null;
        this.wrap = null;
        this.fixedNavH = 0;

        if ( secondary )
        {
            this.wrap = secondary.parentNode;
            this.el   = document.querySelector( '#secondary [data-role="mainsidebar"]' );
        }

        if ( 'true' === fixedNav.getAttribute( 'data-fixed' ) )
        {
            this.fixedNavH = getOuterDimensions( fixedNav ).height;
        }
    };

    FelixSidebar.prototype.fnCalc = function()
    {
        var dims = getOuterDimensions( this.el, true );

        this.width  = dims.width;
        this.height = dims.height;
        this.offset = Math.abs( offset( this.wrap ).top - offset( this.el ).top );
        this.lastWinPos = window.pageYOffset;
        this.top = this.bot = false;
        this.stop = false;
        this.adminBarH = 0;

        if ( adminBar && 'fixed' === getComputedStyle( adminBar ).position )
        {
            this.adminBarH = getOuterDimensions( adminBar ).height;
        }
    };

    FelixSidebar.prototype.fnResize = function()
    {
        var self = this;

        clearTimeout( self.resizeTimeout );

        self.resizeTimeout = setTimeout( function()
        {
            var oldStyles = {
                width : self.el.style.width,
                position : self.el.style.position,
                top : self.el.style.top,
                bot : self.el.style.bottom
            };

            self.el.style.width = '';
            self.el.style.position = '';
            self.el.style.top = '';
            self.el.style.bottom = '';

            self.fnCalc();

            if ( oldStyles.width )
            {
                self.el.style.width = self.width + 'px';
            }

            if ( oldStyles.position )
            {
                self.el.style.position = oldStyles.position;
            }

            if ( oldStyles.top )
            {
                self.el.style.top = oldStyles.top;
            }

            if ( oldStyles.bottom )
            {
                self.el.style.bottom = oldStyles.bottom;
            }
            
        }, 200 );
    };

    FelixSidebar.prototype.fnScroll = function( e )
    {
        if ( window.innerWidth < 980 )
        {
            this.el.style.position = '';
            this.el.style.top = '';
            this.el.style.bottom = '';
            this.el.style.width = '';
            return;
        }

        var self, winPos, curPos, startPos, endPos, winH, visibleH;

        winPos = window.pageYOffset;

        if ( winPos === this.lastWinPos && e )
        {
            return;
        }

        self     = this;
        curPos   = offset( self.el ).top;
        startPos = offset( self.wrap ).top - self.fixedNavH;
        endPos   = startPos + getOuterDimensions( self.wrap ).height + self.fixedNavH;

        visibleH = winH = window.innerHeight;

        if ( self.height < visibleH )
        {
            visibleH = self.height + self.fixedNavH + self.adminBarH;
        }

        if ( winPos + self.adminBarH > startPos && winPos + visibleH < endPos )
        {
            self.stop = false;
            if ( winPos > self.lastWinPos )
            {
                if ( self.top )
                {
                    self.top = false;

                    if ( self.height + self.offset > winH )
                    {
                        self.el.style.width    = '';
                        self.el.style.position = 'relative';

                        if ( curPos > startPos + self.offset )
                        {
                            self.el.style.top = curPos - startPos - self.offset + 'px';
                        }
                        else
                        {
                            self.el.style.top = startPos + 'px';
                        }

                        self.el.style.bottom = '';
                    }
                }
                else if ( ! self.bot && winPos + winH > curPos + self.height )
                {
                    self.bot = true;

                    self.el.style.width    = self.width + 'px';
                    self.el.style.position = 'fixed';

                    if ( self.height + self.offset > winH )
                    {
                        self.el.style.top      = '';
                        self.el.style.bottom   = '0';
                    }
                    else
                    {
                        self.el.style.top      = self.offset + self.adminBarH + self.fixedNavH + 'px';
                        self.el.style.bottom   = '';
                    }
                }
            }
            else if ( winPos < self.lastWinPos )
            {
                if ( self.bot )
                {
                    self.bot = false;

                    if ( self.el.style.position == 'fixed' )
                    {
                        self.el.style.width = '';
                        self.el.style.position = 'relative';

                        if ( curPos > startPos + self.fixedNavH + self.offset )
                        {
                            self.el.style.top = curPos - startPos - self.fixedNavH - self.offset + 'px';
                        }
                        else
                        {
                            self.el.style.top = startPos + self.fixedNavH + self.offset + 'px';
                        }
                    }
                    self.el.style.bottom = '';
                }
                else if ( ! self.top && winPos + self.offset + self.fixedNavH + self.adminBarH <= curPos )
                {
                    self.top = true;

                    self.el.style.width    = self.width + 'px';
                    self.el.style.position = 'fixed';
                    self.el.style.top      = self.offset + self.adminBarH + self.fixedNavH + 'px';
                    self.el.style.bottom   = '';
                }
            }
        }
        else if ( ! self.stop )
        {
            self.stop = true;

            if ( winPos + self.adminBarH <= startPos )
            {
                self.top = false;
                self.bot = false;
                self.el.style.width    = '';
                self.el.style.position = '';
                self.el.style.top      = '';
                self.el.style.bottom   = '';
            }
            else if ( winPos + visibleH >= endPos )
            {
                self.top = false;
                self.bot = true;
                self.el.style.width    = '';
                self.el.style.position = 'relative';
                self.el.style.top      = endPos - startPos - self.height - self.offset - self.fixedNavH + 'px';
                self.el.style.bottom   = '';
            }
        }

        self.lastWinPos = winPos;
    };


    /**
     * Application Object
     */
    function Felix()
    {
        this.header   = document.getElementById( 'masthead' );
        this.nav      = document.getElementById( 'site-navigation' );
        this.mainMenu = document.getElementById( 'primary-menu' );
        this.sideMenu = document.getElementById( 'side-menu' );
        this.footer   = document.getElementById( 'colophon' );

        if ( 'function' !== typeof imagesLoaded )
        {
            return;
        }

        if ( this.header && this.mainMenu )
        {
            this.fnMainMenu();
            this.fnMainMenuFixed();
        }

        if ( this.sideMenu )
        {
            this.fnSideMenu();
        }

        this.fnAriaControls();

        if ( $.fn.fitVids )
        {
            $( '#page' ).fitVids();
        }

        if ( $.fn.owlCarousel )
        {
            this.fnCarousels();
        };

        this.fnBackToTop();
        
        document.body.addEventListener( 'click', this._fnBodyClickHandler.bind( this ) );
        document.body.addEventListener( 'keyup', this._fnBodyKeyUpHandler.bind( this ) );
    };

    /**
     * Main menu
     */
    Felix.prototype.fnMainMenu = function()
    {
        var self  = this,
            items = this.mainMenu.querySelectorAll( 'li.menu-item' );

        if ( ! self._onMainMenuLinkClick )
        {
            self._onMainMenuLinkClick = self._fnMainMenuLinkClickHandler.bind( self );
        }

        forEach( items, function( item, index )
        {
            var link = item.firstElementChild,
                submenu = children( item, 'ul' );

            link.setAttribute( 'data-touch', 'false' );
            link.addEventListener( 'click', self._onMainMenuLinkClick );

            if ( submenu.length )
            {
                item.addEventListener( 'mouseenter', self._fnMainMenuItemHoverHandler );
            }

            while ( submenu.length )
            {
                submenu = submenu[0];

                if ( ! submenu.classList.contains( 'oposite' ) )
                {
                    if ( offset( submenu ).left + getOuterDimensions( submenu ).width > window.innerWidth )
                    {
                        submenu.classList.add( 'oposite' );
                    }
                }

                submenu = children( submenu, 'li > ul' );
            }
        });
    };

    /**
     * Main menu link click
     */
    Felix.prototype._fnMainMenuLinkClickHandler = function( e )
    {
        var link = e.target.closest( 'a' ),
            item = link.parentNode;

        if ( isCustomize )
        {
            return;
        }

        if ( touchSupport )
        {
            if ( 'false' == link.getAttribute( 'data-touch' ) )
            {
                var otherParents = siblings( item ),
                    submenu = children( item, 'ul' );

                if ( submenu.length )
                {
                    e.preventDefault();
                }

                if ( otherParents )
                {
                    otherParents.forEach( function( op )
                    {
                        op.classList.remove( 'hover' );

                        forEach( op.querySelectorAll( 'a[data-touch="true"]' ), function( a )
                        {
                            a.setAttribute( 'data-touch', 'false' );
                        });

                        forEach( op.querySelectorAll( '.menu-item.hover' ), function( i )
                        {
                            i.classList.remove( 'hover' );
                        });
                    });
                }
                item.classList.add( 'hover' );
                link.setAttribute( 'data-touch', 'true' );
            }
            else
            {
                item.classList.remove( 'hover' );
                link.setAttribute( 'data-touch', 'false' );
            }
        }
    };

    /**
     * Main menu item hover
     */
    Felix.prototype._fnMainMenuItemHoverHandler = function( e )
    {
        var submenu = children( this, 'ul' );

        if ( submenu.length )
        {
            submenu = submenu[0];

            if ( ! submenu.classList.contains( 'oposite' ) )
            {
                if ( offset( submenu ).left + getOuterDimensions( submenu ).width > window.innerWidth )
                {
                    submenu.classList.add( 'oposite' );
                }
            }
        }
    };

    Felix.prototype.fnMainMenuFixed = function()
    {
        var self = this,
            navHeight,
            placeHolder,
            desktop, mobile,
            adminBarHeight = 0,
            fixedAdminBar = false,
            adminBarChanged = true,
            navScrollTop, lastScrollTop, fnCalc, fnScroll, fnResize;

        desktop = ( 'true' === self.nav.getAttribute( 'data-fixed' ) ) ? true : false;
        mobile  = ( 'true' === self.nav.getAttribute( 'data-fixed-mobile' ) ) ? true : false;

        if ( desktop || mobile )
        {
            fnCalc = function()
            {
                navHeight     = getOuterDimensions( self.nav ).height;
                navScrollTop  = offset( self.nav ).top;
                lastScrollTop = $( window ).scrollTop();

                placeHolder = document.createElement( 'div' );
                placeHolder.style.display = 'none';
                placeHolder.style.height  = navHeight + 'px';

                self.nav.parentNode.insertBefore( placeHolder, self.nav );
            }

            fnScroll = function()
            {
                var winScroll = $( window ).scrollTop(),
                    topOffset = 0;

                if ( fixedAdminBar )
                {
                    topOffset = adminBarHeight;
                }

                if ( winScroll > navScrollTop - topOffset )
                {
                    if ( placeHolder.style.display === 'none' )
                    {
                        if ( desktop )
                        {
                            self.nav.classList.add( 'fixed-big' );
                        }

                        if ( mobile )
                        {
                            self.nav.classList.add( 'fixed-small' );
                        }

                        placeHolder.style.display = 'block';
                    }

                    if ( adminBarChanged )
                    {
                        self.nav.style.top = topOffset + 'px';
                    }

                    if ( winScroll >= lastScrollTop )
                    {
                        if ( ! self.nav.classList.contains( 'activating' ) )
                        {
                            self.nav.classList.add( 'activating' );
                            self.nav.classList.remove( 'activated' );
                        }
                    }
                    else
                    {
                        if ( ! self.nav.classList.contains( 'activated' ) )
                        {
                            self.nav.classList.add( 'activated' );
                            self.nav.classList.remove( 'activating' );
                        }
                    }
                }
                else
                {
                    self.nav.classList.remove( 'fixed-big' );
                    self.nav.classList.remove( 'fixed-small' );

                    self.nav.style.top = '';
                    placeHolder.style.display = 'none';
                    self.nav.classList.remove( 'activated' );
                }

                lastScrollTop = $( window ).scrollTop();
            }

            fnResize = function()
            {
                if ( adminBar )
                {
                    fixedAdminBar = ( 'fixed' === getComputedStyle( adminBar ).position );

                    if ( adminBarHeight != getOuterDimensions( adminBar ).height )
                    {
                        adminBarChanged = true;
                    }

                    adminBarHeight = getOuterDimensions( adminBar ).height;
                }
            }

            imagesLoaded( self.header, function()
            {
                fnCalc();
                fnResize();
                fnScroll();

                window.addEventListener( 'scroll', fnScroll );
                window.addEventListener( 'resize', fnResize );
            });
        }
    };

    Felix.prototype.fnSideMenu = function()
    {
        var self  = this,
            links = this.sideMenu.querySelectorAll( 'li.menu-item > a' ),
            toggleHandler;

        toggleHandler = function( e )
        {
            var child = $( this ).next( 'ul' );

            if ( child.is( ':visible' ) )
            {
                child.slideUp( 240 );
                $( this ).parent().removeClass( 'hover' );
            }
            else
            {
                child.slideDown( 240 );
                $( this ).parent().addClass( 'hover' );
            }
        }

        forEach( links, function( link )
        {
            if ( link.nextElementSibling && link.nextElementSibling.tagName === 'UL' )
            {
                var toggle = document.createElement( 'span' );
                toggle.setAttribute( 'class', 'submenu-toggle' );
                link.parentNode.insertBefore( toggle, link.nextElementSibling );
                toggle.addEventListener( 'click', toggleHandler );
            }
        });
    };

    /**
     * Aria controls
     */
    Felix.prototype.fnAriaControls = function()
    {
        var controls = document.querySelectorAll( '[aria-controls]' ),
            self = this;

        controls.forEach( function( c )
        {
            c.addEventListener( 'click', self._fnAriaControlClickHandler.bind( self, c ) );
        });
    }

    /**
     * Aria controls click handler
     * @param  {Element} c The Control
     * @param  {Event}   e
     */
    Felix.prototype._fnAriaControlClickHandler = function( c, e )
    {
        var self = this,
            target = document.getElementById( c.getAttribute( 'aria-controls' ) ),
            otherControls;

        if ( ! target )
        {
            return;
        }

        otherControls = document.querySelectorAll( '[aria-controls="' + c.getAttribute( 'aria-controls' ) + '"]' );

        if ( c.getAttribute( 'aria-expanded' ) == 'true' )
        {
            c.setAttribute( 'aria-expanded', 'false' );
            c.classList.remove( 'active' );
            target.setAttribute( 'aria-expanded', 'false' );
            target.classList.remove( 'active' );
            document.body.classList.remove( c.getAttribute( 'aria-controls' ) + '-active' );

            forEach( otherControls, function( oc )
            {
                oc.setAttribute( 'aria-expanded', 'false' );
                oc.classList.remove( 'active' );
            });
        }
        else
        {
            c.setAttribute( 'aria-expanded', 'true' );
            c.classList.add( 'active' );
            target.setAttribute( 'aria-expanded', 'true' );
            target.classList.add( 'active' );
            document.body.classList.add( c.getAttribute( 'aria-controls' ) + '-active' );

            forEach( otherControls, function( oc )
            {
                oc.setAttribute( 'aria-expanded', 'true' );
                oc.classList.add( 'active' );
            });
        }

        c.blur();
    };

    /**
     * Process carousels
     * This function should not be called dirrectly on init
     */
    Felix.prototype._fnProcessCarousel = function( elem )
    {
        var options = elem.data( 'options' );
        var settings = $.extend(
            {
                nav: true,
                navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                dots: false,
                smartSpeed: 500,
                autoHeight: true
            },
            options
        );

        if ( $.fn.imagesLoaded )
        {
            elem.imagesLoaded( function() {
                elem.owlCarousel( settings );
            } );
        }
        else
        {
            elem.owlCarousel( settings );
        }
    };

    /**
     * Carousels
     */
    Felix.prototype.fnCarousels = function()
    {
        var self = this,
            adapCarousel;

        $( '.carousel[data-theme-carousel="true"]' ).each( function()
        {
            self.fnProcessCarousel( $( this ) );
        } );

        adapCarousel = function()
        {
            $( '.carousel[data-full-carousel="true"]' ).each( function()
            {
                var stageOuter = $( this ).children( '.owl-stage-outer' );
                var stage = stageOuter.children( '.owl-stage' );

                stage.children().css( { 'height': '' } );
                stageOuter.css( { 'margin-left': '', 'margin-right': '' } );
                stage.css( { 'margin-left': '' } );

                var offset = stageOuter.offset().left;
                var stageOuterWidth = stageOuter.width();
                var totalWidth = ( stage.children( '.owl-item.active' ).length + 2 ) * stage.children( '.owl-item.active' ).first().width();

                if ( window.innerWidth > totalWidth ) {
                    offset = stage.children( '.owl-item.active' ).width();
                }

                if ( stageOuter.width() < window.innerWidth )
                {
                    stageOuter.css( {
                        'margin-left': -offset + 'px',
                        'margin-right': -offset + 'px'
                    } );
                    stage.css( {
                        'margin-left': offset + 'px',
                    } );
                    stage.children().css( { 'height': stage.outerHeight() } );
                }
            } );
        };

        if ( $.fn.imagesLoaded )
        {
            $( '.carousel[data-full-carousel="true"]' ).imagesLoaded( function()
            {
                adapCarousel();
            } );
        }
        else
        {
            adapCarousel();
        }

        $( window ).on( 'resize', function()
        {
            adapCarousel();
        } );
    };

    Felix.prototype.fnProcessCarousel = function( $el )
    {
        var options = $el.data( 'options' );
        var settings = $.extend(
            {
                nav: true,
                navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
                dots: false,
                smartSpeed: 500,
                autoHeight: true
            },
            options
        );

        if ( $.fn.imagesLoaded )
        {
            $el.imagesLoaded( function()
            {
                $el.owlCarousel( settings );
            } );
        }
        else
        {
            $el.owlCarousel( settings );
        }
    };

    /**
     * Back to top button
     */
    Felix.prototype.fnBackToTop = function()
    {
        var btn = document.getElementById( 'backtotop' ),
            lastScrollTop = $( window ).scrollTop();

        if ( ! btn )
        {
            return;
        }

        btn.addEventListener( 'click', function( e )
        {
            $( 'html, body' ).stop().animate( { scrollTop: 0 }, 1500, 'swing' );
        });

        window.addEventListener( 'scroll', function()
        {
            var winScroll = $( window ).scrollTop();

            if ( winScroll > 480 )
            {
                if ( winScroll >= lastScrollTop )
                {
                    if ( btn.classList.contains( 'active' ) )
                    {
                        btn.classList.remove( 'active' );
                    }
                }
                else
                {
                    if ( ! btn.classList.contains( 'active' ) )
                    {
                        btn.classList.add( 'active' );
                    }
                }
            }
            else
            {
                btn.classList.remove( 'active' );
            }

            lastScrollTop = $( window ).scrollTop();
        });
    }

    /**
     * Body click handler
     */
    Felix.prototype._fnBodyClickHandler = function( e )
    {
        var target = e.target,
            self = this;

        if ( ! target.closest( '#primary-menu' ) || target.getAttribute( 'id' ) === 'primary-menu' )
        {
            forEach( self.mainMenu.querySelectorAll( 'a[data-touch="true"]' ), function( link )
            {
                link.setAttribute( 'data-touch', 'false' );
            });

            forEach( self.mainMenu.querySelectorAll( '.menu-item.hover' ), function( item )
            {
                item.classList.remove( 'hover' );
            });
        }

        if ( ! e.target.closest( '[aria-expanded="true"]' ) && ! e.target.hasAttribute( 'aria-expanded' ) )
        {
            forEach( document.querySelectorAll( '[aria-expanded="true"]' ), function( control )
            {
                if ( ! control.id )
                {
                    return;
                }

                forEach( document.querySelectorAll( '[aria-controls="' + control.id + '"]' ), function( from )
                {
                    from.setAttribute( 'aria-expanded', 'false' );
                    from.classList.remove( 'active' );
                });

                control.setAttribute( 'aria-expanded', 'false' );
                control.classList.remove( 'active' );
                document.body.classList.remove( control.id + '-active' );
            });
        }
    };

    /**
     * Key up handling
     */
    Felix.prototype._fnBodyKeyUpHandler = function( e )
    {
        if ( e.keyCode == 27 && ( ! document.activeElement || 'BODY' === document.activeElement.tagName ) )
        {
            forEach( document.querySelectorAll( '[aria-expanded="true"]' ), function( control )
            {
                if ( ! control.id )
                {
                    return;
                }

                forEach( document.querySelectorAll( '[aria-controls="' + control.id + '"]' ), function( from )
                {
                    from.setAttribute( 'aria-expanded', 'false' );
                    from.classList.remove( 'active' );
                });

                control.setAttribute( 'aria-expanded', 'false' );
                control.classList.remove( 'active' );
                document.body.classList.remove( control.id + '-active' );
            });
        }
    };
} )( jQuery );