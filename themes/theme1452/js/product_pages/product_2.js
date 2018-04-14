$(function () {
  if($('body').hasClass('content_only')) return;
  FixedProductInfo.init();
});


var FixedProductInfo = (function () {
  var options = {},
    isDesktop,
    slider,
    DATA = {};

  function DATACash() {
    DATA.fixedBody = 'pr-body';
    DATA.detectiveClass = 'fixed';
  }

  function getY(obj) {
    if (!obj) return 0;
    var docElem, win,
      rect,
      doc = obj.ownerDocument;
    if (!doc) return 0;
    docElem = doc.documentElement;
    rect = obj.getBoundingClientRect();
    win = doc == doc.window ? doc : (doc.nodeType === 9 ? doc.defaultView || doc.parentWindow : false);
    return rect.top + (win.pageYOffset || docElem.scrollTop) - (docElem.clientTop || 0);
  }

  function rBlockPosition() {
    var rCol = document.getElementById(DATA.fixedBody),
      lCol = document.getElementById('left-column');
    if (!isDesktop || !rCol) return;
    var wh = document.documentElement.clientHeight || 0,
      st = Math.min(window.pageYOffset || document.documentElement.scrollTop, Math.max(
          document.body.scrollHeight, document.documentElement.scrollHeight,
          document.body.offsetHeight, document.documentElement.offsetHeight,
          document.body.clientHeight, document.documentElement.clientHeight
        ) - wh),
      headH = 70, rColH = rCol.offsetHeight, pageH = lCol.offsetHeight, pagePos = getY(lCol), rColMB = 15,
      rColBottom = st + wh - pageH - pagePos - rColMB, rColPT = pagePos - headH, rColPos = getY(rCol),
      lastSt = options.lastSt || 0, lastStyles = options.lastStyles || {}, styles, needFix = false,
      smallEnough = headH + rColMB + rColH + Math.max(0, rColBottom) <= wh;
    if (st - 1 < rColPT && !(smallEnough && rColPos < headH) || (rColH >= pageH)) {
      styles = {marginTop: 0}
    } else if (st - 1 < Math.min(lastSt, rColPos - headH) || smallEnough) {
      styles = {top: headH};
      needFix = true;
    } else if (st + 1 > Math.max(lastSt, rColPos + rColH + rColMB - wh) && rColBottom < 0) {
      styles = {bottom: rColMB};
      needFix = true;
    } else {
      styles = {marginTop: (rColBottom >= 0) ? pageH - rColH : Math.min(rColPos - pagePos, pageH - rColH + rColPT)}
    }
    if (!compareStyles(styles, lastStyles)) {
      for (var key in lastStyles) {
        lastStyles[key] = null;
      }
      for (var key in styles) {
        lastStyles[key] = styles[key];
      }
      for (var key in lastStyles) {
        if (lastStyles[key] !== null) {
          rCol.style[key] = lastStyles[key] + 'px';
        } else {
          rCol.style[key] = null;
        }
      }
      options.lastStyles = styles;
    }
    if (needFix !== rCol.classList.contains(DATA.detectiveClass)) {
      if (needFix) {
        rCol.classList.add(DATA.detectiveClass);
      } else {
        rCol.classList.remove(DATA.detectiveClass);
      }
    }
    options.lastSt = st;
  }

  function compareStyles(st, newSt) {
    var obj1 = {},
      obj2 = {};
    for (var key in st) obj1[key] = Math.round(st[key]);
    for (var key in newSt) obj2[key] = Math.round(newSt[key]);

    return JSON.stringify(obj1) === JSON.stringify(obj2);
  }

  function addEventListener() {
    $(window).resize(function () {
      isDesktop = $(window).width() + compensante > 767;

      var prBody = $("#" + DATA.fixedBody);

      if (isDesktop) {
        if (prBody.length) {
          prBody.width($('#right-column').width()).css({left: document.getElementById('right-column').getBoundingClientRect().left + 20});
        }
        rBlockPosition();

        if (typeof slider !== 'undefined') {
          slider.destroySlider();
          slider = undefined;
          setTimeout(function () {
            $('#thumbs_list').removeAttr('style').find('> li').removeAttr('style');
          }, 300);
        }
      } else {
        gallerySlider();
        if (prBody.length) {
          prBody.removeAttr('style');
        }
      }
    });

    $(window).on('scroll', function () {
      rBlockPosition();
    });

    $(document).on('click', '.color_pick, .attribute_radio', function () {
      reloadRBlock();
    });
    $(document).on('change', '.attribute_select', function () {
      reloadRBlock();
    })
  }

  function reloadRBlock() {
    if ($("#" + DATA.fixedBody)) {
      $("#" + DATA.fixedBody).css('margin-top', 0);
      rBlockPosition();
    }
  }

  function gallerySlider() {
    if (!!$.prototype.bxSlider && $('#views_block-1 > .bx-wrapper').length === 0) {
      slider = $('#left-column').find('#thumbs_list').bxSlider({
        mode: 'horizontal',
        useCSS: false,
        maxSlides: 1,
        slideMargin: 15,
        slideWidth: 767,
        infiniteLoop: false,
        hideControlOnEnd: true,
        pager: false,
        autoHover: true,
        autoControls: false,
        auto: false,
        controls: true
      });
    }
  }

  function init() {
    if($('body').hasClass('content_only')) return;
    DATACash()
    isDesktop = $(window).width() + compensante > 767;
    if (isDesktop) {
      var prBody = $("#" + DATA.fixedBody);
      if (prBody.length) {
        prBody.width($('#right-column').width())
          .css({left: document.getElementById('right-column').getBoundingClientRect().left + 20});
      }
    } else {
      gallerySlider()
    }

    addEventListener();
  }

  return {
    init: init
  }
})();