var first_slide_removed = false;

$(document).ready(function() {

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        centerMode: true,
        focusOnSelect: true,
        arrows: false
    });

    $('.slider-without-navigation').slick({
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });

    $('.homepage-slider').slick({
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        initialSlide: initial_slide,
        autoplay: true,
        autoplaySpeed: 8000
    });

    $('.homepage-slider').on('afterChange', function(event, slick, currentSlide) {

        if (!first_slide_removed)
        {
            $('.homepage-slider').slick('slickRemove', initial_slide, false);

            first_slide_removed = true;
        }
    });
});