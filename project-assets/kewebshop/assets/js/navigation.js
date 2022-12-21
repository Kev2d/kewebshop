$('.js-hamburger').click(function () {
  $(this).toggleClass('js-open');
  $('.js-primary-menu').slideToggle();
});

$(".js-main-item").click(function () {
  const windowWidth = $(window).width();
  $(this).siblings('.js-second-level').slideToggle();
  $(this).parents().toggleClass('js-open');
  $(this).toggleClass('js-open');
  if (windowWidth > 768) {
    $(this).parents().siblings().children('.js-second-level').hide();
  } else {
    $(this).parents().siblings().children('.js-second-level').slideUp();
  }
  $(this).parents().siblings().removeClass('js-open');
  $(this).parents().siblings().children('.js-main-item').removeClass('js-open');
});

$(document).mouseup(function (e) {
  const container = $("nav");
  const windowWidth = $(window).width();
  // if the target of the click isn't the container nor a descendant of the container
  if (!container.is(e.target) && container.has(e.target).length === 0) {
    $('.js-second-level').slideUp();
    $('.js-main-item, .js-hamburger').removeClass('js-open');
    $('.js-main-item').parents().removeClass('js-open');
    if (windowWidth < 768) {
      $('.js-primary-menu').slideUp();
    }
  }
});

//Call function on ready and window resize

$(document).ready(navigationMobile);
$(window).on('resize', navigationMobile);

function navigationMobile() {

  const windowWidth = $(window).width();

  if (windowWidth < 768) {

    //Remove href from main link and add it as a "all" link in menu

    $(".js-second-title").each(function () {
      $(this).addClass('js-closed');
      const currentHref = $(this).attr('href');
      $(this).removeAttr('href');
      if ($(this).siblings('ul').find('.js-all-link').length === 0) {
        $(this).siblings('ul').prepend(`<li><a class="js-all-link" href="${currentHref}">All</a></li>`);
      }
    });

    //When clicked on main link open menu

    $(document).on("click", '.js-second-title.js-closed', function () {
      $(this).removeClass('js-closed');
      $(this).siblings('.js-menu').slideDown();
      $(this).parents().siblings().children('.js-second-title').removeClass('js-open');
      $(this).parents().siblings().children('.js-second-title').addClass('js-closed');
      $(this).parents().siblings().children('.js-menu').slideUp();
      $(this).addClass('js-open');
    });

    $(document).on("click", '.js-second-title.js-open', function () {
      $(this).siblings('.js-menu').slideUp();
      $(this).removeClass('js-open');
      $(this).addClass('js-closed');
    });

  } else {
    $('.js-primary-menu').show();

    //Add href back to main link

    $('.js-second-title').each(function () {
      if ($(this).attr('href') === undefined) {
        const allHref = $(this).siblings('ul').find('.js-all-link').attr('href');
        $(this).attr('href', `${allHref}`);
        $(this).siblings('ul').find('.js-all-link').remove();
      }
    });

  }
}
