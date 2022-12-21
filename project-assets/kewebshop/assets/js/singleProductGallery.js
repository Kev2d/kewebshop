$(document).ready(function () {
  $('.js-product-gallery').slick({
    arrows: false,
    autoplay: false,
  });
});


$('.js-thumbnails img').click(function (e) {
  e.preventDefault();
  const currentSlider = $('.slick-current').data('imgid');
  const slideno = $(this).data('imgid');
  if (currentSlider === slideno) {
    return false;
  }
  $(this).parents().siblings().children().removeClass('js-active');
  $(this).addClass('js-active');
  $('.js-product-gallery').slick('slickGoTo', slideno - 1);
});

$('.js-product-gallery').on('afterChange', function(event, slick, currentSlide){
  const curId = $('.js-product-gallery .slick-current img').data('imgid');
  $('.js-thumbnails img').removeClass('js-active');
  $(`.js-thumbnails img[data-imgid='${curId}']`).addClass('js-active');
  console.log(curId);
});



$(document).ready(followOnScroll);
$(window).on('resize',followOnScroll);

function followOnScroll() {

    const windowWidth = $(window).width();
    const parentWidth = $('.js-single-content-parent').width();

    $('.js-single-content').css('width', parentWidth + 'px');

    if (windowWidth > 768) {
    $(".js-product-gallery img").each(function () {
      if ($(this).attr('width') > 800) {
        $(this)
          .css('display', 'block')
          .parent()
          .zoom({ magnify: 2 });
      }
    });
  } else {
    $('.js-product-gallery img').trigger('zoom.destroy');
  }

    $(window).scroll(function () {

      if (windowWidth > 768) {

        const parentTop = $('.js-single-content-parent').offset().top;
        const parentBottom = parentTop + $('.js-single-content-parent').outerHeight();
        const childHeight = $('.js-single-content').height();
        const scrollTop = $(window).scrollTop();

        if (scrollTop > parentTop && parentBottom - childHeight > scrollTop) {
          $('.js-single-content').addClass('fixed').removeClass('absolute bottom');
        } else if (scrollTop < parentTop) {
          $('.js-single-content').addClass('absolute').removeClass('fixed bottom');
        } else {
          $('.js-single-content').addClass('absolute bottom').removeClass('fixed');
        }
      } else {
        $('.js-single-content').addClass('absolute').removeClass('fixed bottom absolute');
      }
    });

}


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

