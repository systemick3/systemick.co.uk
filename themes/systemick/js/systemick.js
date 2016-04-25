/**
 * @file
 * Defines Javascript behaviors for the systemick theme.
 */

(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {

      $('.view-services .view-content .views-row:nth-child(1) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-1').show();
        return false;
      });

      $('.view-services .view-content .views-row:nth-child(2) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-3').show();
        return false;
      });

      $('.view-services .view-content .views-row:nth-child(3) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-2').show();
        return false;
      });

      $('.block-views .footer .button a').click(function () {
        $(".block-contactblock, .block-tweets").css('opacity', 1);
        $('html, body').animate({ scrollTop: $("#block-contactblock").offset().top }, "slow");
        $(this).parents('.block-views').hide();
      });

      $('.menu li:last-child a').click(function () {
        $(".block-contactblock, .block-tweets").css('opacity', 1);
        $('html, body').animate({ scrollTop: $("#block-contactblock").offset().top }, "slow");
        return false;
      });

      var elements = [
        $('.field--name-body'),
        $('.block-views-blockservices-block-1'),
        $('.block-views-blocktechnologies-block-1'),
        $('.block-contactblock'),
        $('.block-tweets')
      ];
      $.each(elements, function (index, element) {
        element.css('opacity', 0);
      });

      var animations = function () {
        var that = this,
          $window = $(window),
          windowHeight = $window.height,
          windowTopPosition = $window.scrollTop(),
          windowBottomPosition = (windowTopPosition - windowHeight);

        $.each(elements, function (index, el) {
          var timeout = (index == 0) ? 0 : index * 400,
            elementHeight = el.height(),
            elementTopPosition = el.offset().top,
            elementBottomPosition = (elementTopPosition + elementHeight);

          setTimeout(function() {
            if (isReadyToFire(el, {}) && !el.data('isAnimated')) {
              el.animate({
                opacity: 1
              }, 600, function() {
                //alert('animation done');
              });
              el.data('isAnimated', true);
            }
          }, timeout);

        });
      };

      var isReadyToFire = function (element, options) {
        var $window = $(window),
          docViewTop = $window.scrollTop(),
          docViewBottom = docViewTop + $window.height(),
          elementTop = element.offset().top,
          elementBottom = elementTop + element.height() - 200;

        // options.offset should be a percentage
        // eg if 60 then the element will begin to animate when it is higher than 60% of the distance from the top
        // in other words 40% up from the bottom
        if (options.offset) {
          var docViewFold = docViewTop + (($window.height() / 10) * (options.offset / 10));
          return (elementTop <= docViewFold);
        }

        return ((elementBottom <= docViewBottom) && (elementTop >= docViewTop));
      };

      $(window).on('scroll', function () {
        animations();
      });

      $(window).trigger('scroll');
    }
  };

})(jQuery, Drupal, drupalSettings);