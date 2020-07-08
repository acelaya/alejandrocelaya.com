(function () {

    'use strict';



    // iPad and iPod detection
    var isiPad = function(){
        return (navigator.platform.indexOf("iPad") != -1);
    };

    var isiPhone = function(){
        return (
            (navigator.platform.indexOf("iPhone") != -1) ||
            (navigator.platform.indexOf("iPod") != -1)
        );
    };

    // Go to next section
    var gotToNextSection = function(){
        var el = $('.fh5co-learn-more'),
            w = el.width(),
            divide = -w/2;
        el.css('margin-left', divide);
    };

    // Loading page
    var loaderPage = function() {
        $(".fh5co-loader").fadeOut("slow");
    };

    // FullHeight
    var fullHeight = function() {
        if ( !isiPad() && !isiPhone() ) {
            $('.js-fullheight').css('height', $(window).height() - 49);
            $(window).resize(function(){
                $('.js-fullheight').css('height', $(window).height() - 49);
            })
        }
    };

    // Scroll Next
    var ScrollNext = function() {
        $('body').on('click', '.scroll-btn', function(e){
            e.preventDefault();

            $('html, body').animate({
                scrollTop: $( $(this).closest('[data-next="yes"]').next()).offset().top
            }, 1000, 'easeInOutExpo');
            return false;
        });
    };

    // Click outside of offcanvass
    var mobileMenuOutsideClick = function() {

        $(document).click(function (e) {
        var container = $("#fh5co-offcanvas, .js-fh5co-nav-toggle");
        if (!container.is(e.target) && container.has(e.target).length === 0) {

            if ( $('body').hasClass('offcanvas-visible') ) {

                $('body').removeClass('offcanvas-visible');
                $('.js-fh5co-nav-toggle').removeClass('active');

            }


        }
        });

    };


    // Offcanvas
    var offcanvasMenu = function() {
        $('body').prepend('<div id="fh5co-offcanvas" />');
        $('#fh5co-offcanvas').prepend('<ul id="fh5co-side-links">');
        $('body').prepend('<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>');

        $('.left-menu li, .right-menu li').each(function(){

            var $this = $(this);

            $('#fh5co-offcanvas ul').append($this.clone());

        });
    };

    // Burger Menu
    var burgerMenu = function() {

        $('body').on('click', '.js-fh5co-nav-toggle', function(event){
            var $this = $(this);

            $('body').toggleClass('fh5co-overflow offcanvas-visible');
            $this.toggleClass('active');
            event.preventDefault();

        });

        $(window).resize(function() {
            if ( $('body').hasClass('offcanvas-visible') ) {
            $('body').removeClass('offcanvas-visible');
            $('.js-fh5co-nav-toggle').removeClass('active');
           }
        });

        $(window).scroll(function(){
            if ( $('body').hasClass('offcanvas-visible') ) {
            $('body').removeClass('offcanvas-visible');
            $('.js-fh5co-nav-toggle').removeClass('active');
           }
        });

    };


    // Animations

    var contentWayPoint = function() {
        var i = 0;
        $('.animate-box').waypoint( function( direction ) {

            if( direction === 'down' && !$(this.element).hasClass('animated') ) {

                i++;

                $(this.element).addClass('item-animate');
                setTimeout(function(){

                    $('body .animate-box.item-animate').each(function(k){
                        var el = $(this);
                        setTimeout( function () {
                            el.addClass('fadeInUp animated');
                            el.removeClass('item-animate');
                        },  k * 200, 'easeInOutExpo' );
                    });

                }, 100);

            }

        } , { offset: '95%' } );
    };



    // Document on load.
    $(function(){
        gotToNextSection();
        loaderPage();
        fullHeight();
        ScrollNext();
        mobileMenuOutsideClick();
        offcanvasMenu();
        burgerMenu();

        // Animate
        contentWayPoint();
    });


}());

function recaptchaCallaback (value) {
    var $contactForm = $('form#contactForm');
    if ($contactForm.size() === 0) {
        return;
    }

    $contactForm.find('[type=submit]').removeAttr('disabled');
}
