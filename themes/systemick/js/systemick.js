/**
 * @file
 * Defines Javascript behaviors for the systemick theme.
 */

(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.nodeDetailsSummaries = {
    attach: function (context) {

      $('.node--type-front .view-services .view-content .views-row:nth-child(1) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-1').show();
        return false;
      });

      $('.node--type-front .view-services .view-content .views-row:nth-child(2) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-3').show();
        return false;
      });

      $('.node--type-front .view-services .view-content .views-row:nth-child(3) a').click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        $('.block-views-blockservice-modal-block-2').show();
        return false;
      });

      $('.block-views .footer .button a').click(function () {
        $(".block-contactblock, .block-tweets").css('opacity', 1);
        $('html, body').animate({ scrollTop: $("#block-contactblock").offset().top }, "slow");
        $(this).parents('.block-views').hide();
      });

      $('.menu li:last-child a, .node--type-front .field--name-body p a').click(function () {
        $(".block-contactblock, .block-tweets").css('opacity', 1);
        $('html, body').animate({ scrollTop: $("#block-contactblock").offset().top }, "slow");
        return false;
      });

//      $('.menu li:nth-child(3) a').click(function () {
//        //$(".block-contactblock, .block-tweets").css('opacity', 1);
//        //window.location = '/';
//        $('html, body').animate({ scrollTop: $("#block-views-block-services-block-1").offset().top }, "slow");
//        return false;
//      });

      // Init Skrollr
//      var s = skrollr.init({
//        render: function(data) {
//          //Debugging - Log the current scroll position.
//          //console.log(data.curTop);
//        }
//      });

    }
  };

})(jQuery, Drupal, drupalSettings);
