$(document).ready(function () {
  $('#newsletter-input').on({
    focus: function () {
      if ($(this).val() == placeholder_blocknewsletter || (typeof(msg_newsl) != 'undefined' && $(this).val() == msg_newsl)) {
        $(this).val('');
      } else {
        validateNewslaterEmail($(this));
      }
    },
    blur: function () {
      if ($(this).val() == '') {
        $(this).val(placeholder_blocknewsletter);
      } else {
        validateNewslaterEmail($(this));
      }
    },
    keyup: function () {
      validateNewslaterEmail($(this));
    },
    change: function () {
      validateNewslaterEmail($(this));
    }
  });

  if (typeof msg_newsl != 'undefined' && msg_newsl) {
    if ($('#newsletter_block_left form').length) {
      $(window).load(function () {
        $('html, body').animate({scrollTop: $('#newsletter_block_left').offset().top}, 'slow')
      });
    } else {
      $(window).load(function () {
        $('html, body').animate({scrollTop: $('#newsletter_block_left').offset().top}, 'slow');
      });
    }
  }
});

function validateNewslaterEmail(element) {
  if (element.val() != '') {
    if (!validate_isEmail(element.val())) {
      element
        .parent()
          .removeClass('email-valid')
          .addClass('email-error');
    } else {
      element
        .parent()
          .removeClass('email-error')
          .addClass('email-valid');
    }
  } else {
    element
      .parent()
        .removeClass('email-error')
        .removeClass('email-valid');
  }
}
