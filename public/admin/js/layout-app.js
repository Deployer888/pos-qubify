
!function($) {
    "use strict";

    var MainApp = function() {
        this.$btnFullScreen = $("#btn-fullscreen")
    };
     // preloader
    MainApp.prototype.preloader = function () {
        $(window).on('load', function(){
            $('.ic-preloader').delay(1000).fadeOut("slow");
        })
    },
    //full screen
    MainApp.prototype.initFullScreen = function () {
        var $this = this;
        $this.$btnFullScreen.on("click", function (e) {
            e.preventDefault();

            if (!document.fullscreenElement && /* alternative standard method */ !document.mozFullScreenElement && !document.webkitFullscreenElement) {  // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        });
    },


    MainApp.prototype.initNavbar = function () {

        $('.navbar-toggle').on('click', function (event) {
            $(this).toggleClass('open');
            $('#navigation').slideToggle(400);
        });

        $('.navigation-menu>li').slice(-2).addClass('last-elements');

    },

    MainApp.prototype.initMobileMenu = function () {
        $('.navigation-menu li a').on('click', function(e) {
            var element = $(this).parent('li');
            if (element.hasClass('open')) {
                element.removeClass('open');
                element.find('li').removeClass('open');
                element.find('ul').slideUp(500,"swing");
            }
            else {
                element.addClass('open');
                element.children('ul').slideDown(500,"swing");
                element.siblings('li').children('ul').slideUp(500,"swing");
                element.siblings('li').removeClass('open');
                element.siblings('li').find('li').removeClass('open');
                element.siblings('li').find('ul').slideUp(500,"swing");
            }
        });

    },
    MainApp.prototype.hasMenu = function () {
        $('.has-submenu').children('.submenu').parent().addClass('arrow');
    },
    MainApp.prototype.initScrollbar = function () {
        $('.slimscroll').slimScroll({
            height: 'auto',
            position: 'right',
            size: "7px",
            color: '#9ea5ab',
            wheelStep: 5,
            touchScrollStep: 50
        });
    }
    // === fo,llowing js will activate the menu in left side bar based on url ====
    MainApp.prototype.initMenuItem = function () {
        $(".navigation-menu a").each(function () {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().parent().addClass("active"); // add active class to an anchor
                $(this).parent().parent().parent().parent().parent().addClass("active"); // add active class to an anchor
            }
        });
    },
    MainApp.prototype.initComponents = function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    },

    MainApp.prototype.init = function () {
        this.preloader();
        this.initFullScreen();
        this.initNavbar();
        this.initMobileMenu();
        this.hasMenu();
        this.initScrollbar();
        this.initMenuItem();
        this.initComponents();
        Waves.init();
    },

    //init
    $.MainApp = new MainApp, $.MainApp.Constructor = MainApp
}(window.jQuery),

//initializing
function ($) {
    "use strict";
    $.MainApp.init();
}(window.jQuery);
