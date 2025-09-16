(function($) {
    "use strict";

    // login form sync slider
    $(".login-img-slider-heads").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        autoplay: true,
        dots: false,
        asNavFor: ".mobile-img-slider-heads"
    });
    $(".mobile-img-slider-heads").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: ".login-img-slider-heads",
        dots: false,
        arrows: false,
        autoplay: true,
        focusOnSelect: true
    });
})(jQuery);
