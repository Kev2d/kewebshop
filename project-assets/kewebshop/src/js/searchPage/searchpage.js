$('.js-open-submenu').click(function () {
    $(this).toggleClass('js-open');
    $(this).parents().next('.js-submenu').slideToggle();
});