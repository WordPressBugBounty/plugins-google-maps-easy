(function ($) {
  'use strict';

  $(function () {
    $('.gmp-overview a[href^="#"]').on('click', function (event) {
      var target = document.querySelector(this.getAttribute('href'));
      if (!target) {
        return;
      }

      event.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    $('.gmp-overview-faq-question').on('click', function () {
      var $item = $(this).closest('.gmp-overview-faq-item');
      var willOpen = !$item.hasClass('is-open');

      $item.siblings('.is-open').removeClass('is-open');
      $item.toggleClass('is-open', willOpen);
    });
  });
})(jQuery);
