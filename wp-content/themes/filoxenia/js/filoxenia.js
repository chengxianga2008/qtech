(function ($) { 
    "use strict";

    // --------------------------------------------------
    // sliding bar
    // --------------------------------------------------
     jQuery(".sliding-toggle").toggle(
    function() {
        jQuery(".sliding-content").slideToggle(500,'easeInOutExpo');
        jQuery(this).addClass("open");
    },
    function() {
        jQuery(".sliding-content").slideToggle(500,'easeInOutExpo');
        jQuery(this).removeClass("open");
    })

    $('.slider').flexslider({
        animation: "slide",
        slideshow: true,
        directionNav:false,
        controlNav: true
    });
	
	$('a.scroll').smoothScroll({
        speed: 800,
        offset: -78
	});

    // --------------------------------------------------
    // tabs
    // --------------------------------------------------
    jQuery('.de_tab').find('.de_tab_content > div').hide();
    jQuery('.de_tab').find('.de_tab_content > div:first').show();
    jQuery('.de_tab').find('li:first span').addClass("active");
    jQuery('.de_nav li').click(function() {

        jQuery(this).parent().find('li span').removeClass("active");
        jQuery(this).find('span').addClass("active");
        jQuery(this).parent().parent().find('.de_tab_content > div').hide();
    
        var indexer = jQuery(this).index(); //gets the current index of (this) which is #nav li
        jQuery(this).parent().parent().find('.de_tab_content > div:eq(' + indexer + ')').fadeIn(); //uses whatever index the link has to open the corresponding box 
    });
    
    var jqueryPlugins = {
    init: function () {
        this.foundation();
        this.magnificPopup();
        this.fitVids();
    },

    // Read more: https://github.com/zurb/foundation
    foundation: function () {
        $(document).foundation();
    },

    // Read more: https://github.com/dimsemenov/Magnific-Popup
    magnificPopup: function () {
        $('.popup').each(function () {
            $(this).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });
    },

    // Read more: https://github.com/davatron5000/FitVids.js
    fitVids: function () {
        $('.container').fitVids();
    }
};

var tweaks = {
    init: function () {
        this.topBar();

        if (!this.isMobile()) {
            this.backToTop();
            this.heroParallax();
        }
    },

    // Check screen size
    isMobile: function () {
        return (matchMedia(Foundation.media_queries.small).matches || matchMedia(Foundation.media_queries.medium).matches) && !matchMedia(Foundation.media_queries.large).matches;
    },
    
     

    // Back to top button
    backToTop: function () {
        var $button = $('#back-top');

        // Add HTML to the page, if it isn't already there.
        if ($button.length === 0) {
            $('body').append('<a id="back-top" class="animated" href="#"><i class="fa fa-caret-up"></i></a>');
            $button = $('#back-top');
        }

        if ($(document).scrollTop() >= 200) {
            $button.stop().show(0, function () {
                $(this).removeClass('fadeOutUp').addClass('fadeInDown');
            });
        } else {
            $button.stop().removeClass('fadeInDown').addClass('fadeOutUp');
        }
    },

    // Parallax functionality for homepage hero
    heroParallax: function () {
        var $hero = $('.hero');
        var $msg = $('.hero-messages');

        // Don't run if not needed
        if ($hero.length === 0) return;

        // Set background image position
        var top = ($(window).scrollTop() / 3) - ($('.top-bar').height() / 2);
        $hero.css('backgroundPosition', 'center ' + top + 'px');

        // Add initial fade in
        $msg.addClass('fadeInDown animated');

        // Add classes on scroll up or down
        if ($(document).scrollTop() >= 250) {
            $msg.removeClass('fadeInUp fadeInDown').addClass('fadeOutDown');
        } else if ($(document).scrollTop() < 250 && $msg.hasClass('fadeOutDown')) {
            $msg.removeClass('fadeOutDown').addClass('fadeInUp');
        }
    },

    goToTop: function () {
        $('html, body').animate({
            scrollTop: 0
        }, 600);
    },

    topBar: function () {
        $(document).on('click.fndtn.topbar', '[data-topbar]', function () {
            $(this).find('.title.back h5 a').unwrap();
        });
    },

    showPopupImageOverlay: function ($el) {
        $el.before('<div class="popup-overlay"><i class="fa fa-search-plus"></i></div>');

        var $overlay = $('.popup-overlay');
        $overlay.stop(true, true).fadeIn(300);

        var overlayHeight = $el.height();
        var $icon = $overlay.find('i');

        $overlay.css('fontSize', overlayHeight * 0.2);
        $icon.css('top', (overlayHeight / 2) - ($icon.height() / 2));
        $icon.css('left', ($el.width() / 2) - ($icon.width() / 2));
    },

    hidePopupImageOverlay: function () {
        $('.popup-overlay').stop(true, true).fadeOut(150, function () {
            $(this).remove();
        });
    }
};

    $('.dropdown').parent().addClass('has-dropdown');
    // Preload
    $(window).load(function() {
        $('.images-preloader').hide();
    });

    $(".logo-clients").owlCarousel({
        autoPlay : false,
        itemsCustom : [
        [0, 1],
        [450, 2],
        [600, 3],
        [700, 4],
        [1000, 5],
        [1200, 6],
        [1400, 6],
        [1600, 6]
      ],
    });
    $(window).scroll(function () {

        var $h1 = $('.contain-to-grid');

        if ($h1.length > 0) {

            if ($(this).scrollTop() > 40) {

                $('.contain-to-grid').addClass('bar-sticked');

                //$('#site-header-1').css('padding-bottom', 80);

            } else {

                $('.contain-to-grid').removeClass('bar-sticked');

                //$('#site-header-1').css('padding-bottom', 0);

            }

        }

    });

    $(document).ready(function () {
        jqueryPlugins.init();
        tweaks.init();

        $(document).on('scroll', function () {
            tweaks.init();
        });

        // Handle the #back-top button click
        $(document).on('click', '#back-top', function (e) {
            e.preventDefault();

            tweaks.goToTop();
        });

        // Popup image overlay
        if (!tweaks.isMobile()) {
            $(document).on('mouseenter', '.popup a img', function () {
                tweaks.showPopupImageOverlay($(this));
            });
            $(document).on('mouseleave', '.popup a ', function () {
                tweaks.hidePopupImageOverlay();
            });
        }
        
    });

})(jQuery);
