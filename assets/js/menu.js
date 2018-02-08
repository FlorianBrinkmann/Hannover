var root = document.documentElement;
root.removeAttribute('class', 'no-js');
root.setAttribute('class', 'js');

(function ($) {
    var body, masthead, menuToggle, siteNavigation;

    function initMainNavigation(container) {

        // Add dropdown toggle that displays child menu items.
        var dropdownToggle = $('<button />', {
            'class': 'dropdown-toggle',
            'aria-expanded': false
        }).append($('<svg version="1.1" id="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" style="enable-background:new 0 0 16 16;" xml:space="preserve"><title>' + screenReaderText.expand + '</title><polygon points="11,6 7.5,9.5 4,6 3,7 7.5,11.5 12,7 "/></svg>', {
            text: screenReaderText.expand
        }));

        container.find('.menu-item-has-children > a').after(dropdownToggle);

        // Toggle buttons and submenu items with active children menu items.
        container.find('.current-menu-ancestor > button').addClass('toggled-on');
        container.find('.current-menu-ancestor > .sub-menu').addClass('toggled-on');

        // Add menu items with submenus to aria-haspopup="true".
        container.find('.menu-item-has-children').attr('aria-haspopup', 'true');


        container.find('.dropdown-toggle').click(function (e) {
            var _this = $(this),
                screenReaderSpan = _this.find('svg title');

            e.preventDefault();
            _this.toggleClass('toggled-on');
            _this.next('.children, .sub-menu').toggleClass('toggled-on');

            // jscs:disable
            _this.attr('aria-expanded', _this.attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
            screenReaderSpan.text(screenReaderSpan.text() === screenReaderText.expand ? screenReaderText.collapse : screenReaderText.expand);
        });
    }


    initMainNavigation($('.primary-menu'));

    masthead = $('#header');
    menuToggle = masthead.find('#menu-toggle');
    //siteHeaderMenu = masthead.find('#site-header-menu');
    siteNavigation = masthead.find('nav');

    // Enable menuToggle.
    (function () {

        // Return early if menuToggle is missing.
        if (!menuToggle.length) {
            return;
        }

        // Add an initial values for the attribute.
        menuToggle.add(siteNavigation).attr('aria-expanded', 'false');

        menuToggle.on('click', function () {
            $(this).add(siteNavigation).toggleClass('toggled-on');

            // jscs:disable
            $(this).add(siteNavigation).attr('aria-expanded', $(this).add(siteNavigation).attr('aria-expanded') === 'false' ? 'true' : 'false');
            // jscs:enable
        });
    })();

    // Fix sub-menus for touch devices and better focus for hidden submenu items for accessibility.
    (function () {
        if (!siteNavigation.length || !siteNavigation.children().length) {
            return;
        }

        // Toggle `focus` class to allow submenu access on tablets.
        function toggleFocusClassTouchScreen() {
            if (window.innerWidth >= 910) {
                $(document.body).on('touchstart', function (e) {
                    if (!$(e.target).closest(siteNavigation + 'li').length) {
                        $(siteNavigation + 'li').removeClass('focus');
                    }
                });
                siteNavigation.find('.menu-item-has-children > a').on('touchstart', function (e) {
                    var el = $(this).parent('li');

                    if (!el.hasClass('focus')) {
                        e.preventDefault();
                        el.toggleClass('focus');
                        el.siblings('.focus').removeClass('focus');
                    }
                });
            } else {
                siteNavigation.find('.menu-item-has-children > a').unbind('touchstart');
            }
        }

        if ('ontouchstart' in window) {
            $(window).on('resize', toggleFocusClassTouchScreen);
            toggleFocusClassTouchScreen();
        }

        siteNavigation.find('a').on('focus blur', function () {
            $(this).parents('.menu-item').toggleClass('focus');
        });
    })();

    // Add the default ARIA attributes for the menu toggle and the navigations.
    function onResizeARIA() {
        if (window.innerWidth < 910) {
            if (menuToggle.hasClass('toggled-on')) {
                menuToggle.attr('aria-expanded', 'true');
            } else {
                menuToggle.attr('aria-expanded', 'false');
            }

            if (siteNavigation.hasClass('toggled-on')) {
                siteNavigation.attr('aria-expanded', 'true');
            } else {
                siteNavigation.attr('aria-expanded', 'false');
            }

            menuToggle.attr('aria-controls', 'site-navigation social-navigation');
        } else {
            menuToggle.removeAttr('aria-expanded');
            siteNavigation.removeAttr('aria-expanded');
            menuToggle.removeAttr('aria-controls');
        }
    }

    $(document).ready(function () {
        body = $(document.body);

        $(window)
            .on('load', onResizeARIA)
            .on('resize', function () {
                onResizeARIA();
            });
    });
})(jQuery);
