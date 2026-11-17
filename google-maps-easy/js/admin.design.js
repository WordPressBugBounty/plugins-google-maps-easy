/**
 * One look for the admin pages of Easy Google Maps (with css/admin.design.css).
 * - jQuery UI dialogs open in the middle of the screen under the admin bar, whatever the page scroll.
 * - The buttons of a dialog that only close it (Cancel, Close, No) are plain, so the action is the only dark one. It
 *   runs a tick later: the plugin's own handlers of the same event may rewrite the buttons first.
 */
(function ($) {
  'use strict';

  var plain = /^(cancel|close|no|back|отмена|закрыть|нет|назад)$/i;

  /**
   * The plugin layout passes the content of its pages through wp_kses, which takes out SVG and data attributes. Parts
   * that need them (the review card, the PRO offer of the Overview) are printed in the footer with
   * data-gmp-move="<selector of the place>" and data-gmp-move-mode="prepend|append|replace" and moved here.
   */
  function moveParts() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-gmp-move]'), function (part) {
      // "a|b": the first of the places that is on the page (the Overview column, else the page frame)
      var target = null;
      part.getAttribute('data-gmp-move').split('|').some(function (selector) {
        target = document.querySelector(selector);
        return !!target;
      });
      if (!target) {
        return;
      }
      var mode = part.getAttribute('data-gmp-move-mode') || 'append';
      if (mode === 'prepend') {
        target.insertBefore(part, target.firstChild);
      } else if (mode === 'replace') {
        target.parentNode.replaceChild(part, target);
      } else {
        target.appendChild(part);
      }
      part.removeAttribute('data-gmp-move');
      part.hidden = false;
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', moveParts);
  } else {
    moveParts();
  }

  $(document).on('dialogopen', function (event) {
    var $dialog = $(event.target).closest('.ui-dialog');
    if (!$dialog.length) {
      return;
    }
    $dialog.addClass('gmp-dlg-center');
    setTimeout(function () {
      $dialog.find('.ui-dialog-buttonset button').each(function () {
        var $button = $(this);
        $button.toggleClass('gmp-dlg-plain', plain.test($.trim($button.text())));
      });
    }, 0);
  });
})(jQuery);
