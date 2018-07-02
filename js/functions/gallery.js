$(document).ready(function() {

    $("[data-fancybox]").fancybox({
        loop : true,
        transitionDuration : 700,
        buttons : [
            'close'
        ],
        afterShow : function( instance, current ) {
            console.info( instance );
        }
    });

    var animationInProgress = false;

    $('.slick-slide img').on('click', function() {

        setTimeout(function() {

            var prev_button = $('.fancybox-button--arrow_left');
            var next_button = $('.fancybox-button--arrow_right');

            prev_button.on('click', function() {

                var instance = $.fancybox.getInstance();

                if (!animationInProgress)
                {
                    animationInProgress = true;
                    $('.slider-for').slick('slickPrev');
                    instance.prev();
                    prev_button.prop("disabled", true);

                    setTimeout(function () {
                        animationInProgress = false;
                        prev_button.prop("disabled", false);
                    }, 500);
                }
            });

            next_button.on('click', function() {

                var instance = $.fancybox.getInstance();

                if (!animationInProgress)
                {
                    animationInProgress = true;
                    $('.slider-for').slick('slickNext');
                    instance.next();
                    next_button.prop("disabled", true);

                    setTimeout(function () {
                        animationInProgress = false;
                        next_button.prop("disabled", false);
                    }, 500);
                }
            });
        }, 500);
    });
});