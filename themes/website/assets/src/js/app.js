global.jQuery = require('jquery')
var $ = global.jQuery
window.$ = $

$(function() {
    "use strict";
    // Your js script is below this line
    // --------------------------------------------------------------------- //
    initNavbar();


    // Initiate menu and custom events
    function initNavbar() {
        var navbar = $('#mainNav');
        // Load navbar settings
        var headerHeight = Math.floor($('header.masthead').height() * 0.8);
        var transClass = $('header.masthead').data('navbar-trans');

        // Toggle navbar style on scroll
        $('body').scrollspy({
            target: '#mainNav',
        });

        var scrollSubscriber = function () {
            if (navbar.offset().top > headerHeight) {
                transClass && navbar.removeClass(transClass);
            } else {
                transClass && navbar.addClass(transClass);
            }
        }

        $(window).scroll(scrollSubscriber);
        // In case if page already scrolled
        scrollSubscriber();
    }
});
