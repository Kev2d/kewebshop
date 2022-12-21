$(function () {
  $('.js-all-tabs').append('<span class="js-hover-line"></span>')
  setLineWidth($('.js-tab.js-active'));
});

$(window).resize(function () {
  setLineWidth($('.js-tab.js-active'));
  moveLine($('.js-tab.js-active'));
});

$('.js-tab').click(function (e) {
  e.preventDefault();
  const activeTabHref = $(this).attr('href');

  moveLine($(this));
  $(`${activeTabHref}`).removeClass('js-hidden');
  if (!$(this).hasClass("js-active")) {
    $(`${activeTabHref}`).find('.latest-products__products-item-thumbnail').each(function () {
      $(this).children('img:first').css('opacity', '0');
      $(this).children('img:first').animate({ opacity: 1 }, 300);
    });
  }
  $(`${activeTabHref}`).siblings().addClass('js-hidden');
  $(this).addClass('js-active');
  $(this).siblings().removeClass('js-active');
  setLineWidth($('.js-tab.js-active'));
});

$('.js-tab').mouseenter(function () {
  setLineWidth($(this));
  moveLine($(this));
});

$('.js-tab').mouseleave(function () {
  setLineWidth($('.js-tab.js-active'));
  moveLine($('.js-tab.js-active'));
}
).mouseleave();

function moveLine(myObject) {
  let newLineWidth = 0;
  myObject.prevAll('.js-tab').each(function () {
    const siblingWidth = $(this).outerWidth();
    newLineWidth += siblingWidth;
  });

  $('.js-hover-line').css('left', newLineWidth + 'px');
}

function setLineWidth(myObject) {
  const activeTabWidth = myObject.outerWidth();
  $('.js-hover-line').css('width', activeTabWidth + 'px');
}


//Fade in Image on tab switch
//Should I use Display:none to speed up the site
//hide scrollbars on mobile