/**
 * @file
 * Defines Javascript behaviors for the systemick_2017 theme.
 */

(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.bannerAnimate = {
    attach: function (context) {
      window.addEventListener('scroll', function(e){
        var banner = $('.header__background');
        var distanceY = window.pageYOffset || document.documentElement.scrollTop,
            displayOn = 20,
            header = document.querySelector("header");
        if (distanceY > displayOn) {
            banner.addClass('border-black');
        } 
        else {
          if (banner.hasClass('border-black')) {
            banner.removeClass('border-black');
          }
        }
      });
    }
  };

})(jQuery, Drupal, drupalSettings);
