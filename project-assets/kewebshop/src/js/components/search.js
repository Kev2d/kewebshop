$(".js-search-input").focus(function () {
    $('.js-search-bar').addClass('js-active');
});

$(".js-search-input").focusout(function () {
    if ($(this).val().length === 0) {
        $('.js-search-bar').removeClass('js-active');
    }
});

$(".js-search-input").on('keydown change', debounce(function () {
    const inputLength = $(this).val().length;
    const inputVal = $(this).val();
    if (inputLength) {
        $('.js-search-products').addClass('js-active');
        getSearchData(inputVal);
    } else {
        $('.js-search-products').removeClass('js-active');
    }
}, 300));

$(document).on("click", '.js-search-input', debounce(function () {
    const inputLength = $(this).val().length;
    if (inputLength > 2) {
        $('.js-search-products').addClass('js-active');
        getSearchData(inputVal);
    } else {
        $('.js-search-products').removeClass('js-active');
    }
}, 300));

$(document).on("click", '.js-close-icon', function () {
    removeSearch();
    $('.js-close-icon').hide();
    $('.js-search-icon').show();
    $('.js-search-input').val('');
});


async function getSearchData(value) {
    const windowWidth = $(window).width();
    await $.ajax({
        type: 'POST',
        url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getlistofproducts',
        data: {
            searchInput: value,
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
            $('.js-search-icon').hide();
            $('.js-close-icon').hide();
            $('.js-search-loader').addClass('js-show');
        },
        success: function (response) {
            if (response) {
                $('.js-search-loader').removeClass('js-show');
                if (windowWidth > 768) {
                    $('.js-search-icon').show();
                } else {
                    $('.js-close-icon').show();
                }
                removeSearch();
                const productArr = JSON.parse(response);
                createSearchDropDown(productArr);
            }
        }
    });
}

function createSearchDropDown(productArr) {
    if ($(".js-search-dropdown").length === 0) {
        $(".js-shop-header").after('<ul class="search-dropdown js-search-dropdown"></ul>');
        $('.js-search-dropdown').append(`<li class="search-dropdown__allResults"><span class="js-see-all-results">${js_strings.showAllResults}</span></li>`);
        for (const product of productArr) {
            const productId = product['ID'];
            const productTitle = product['title'];
            const price = product['price'];
            const saleProduct = product['isOnSale'];
            const thumbUrl = product['thumbUrl'];
            const productUrl = product['productUrl'];
            console.log(productId, productTitle, price, saleProduct, thumbUrl, productUrl);
            $('.js-search-dropdown').append(`<li class="search-dropdown__item" data-id="${productId}">
            <a href="${productUrl}">
            <div class="search-dropdown__item-inside">
            <div class="search-dropdown__item-inside-info">
            <span class="search-dropdown__item-inside-title">${productTitle}</span>
            <span class="${saleProduct ? 'highlight--text' : ''}">${price}€</span>
            </div>
            <img src="${thumbUrl ? thumbUrl : templateUrl.url + '/assets/img/defaultimages/product_placeholder.jpg'}" alt="search-thumb" width="100" height="100">
            </div>
            </a>
            </li>`);
        }
    }
    setSearchHeight();
}

$(document).on("click", '.js-see-all-results', function () {
    $('#search-form').submit();
});

function removeSearch() {
    $('.js-search-dropdown').remove();
}

$(document).ready(setSearchHeight);
$(window).on('resize', setSearchHeight);

function setSearchHeight() {
    const windowWidth = $(window).width();
    const winHeight = $(window).height();
    if (windowWidth > 768) {
        if ($('.js-search-dropdown').length > 0) {
            const headerHeight = $('.js-shop-header').outerHeight();
            $('.js-search-dropdown').css('max-height', winHeight - headerHeight + 'px');
            $('.js-search-dropdown').css('top', 'unset');
        }
    } else {
        const searchBar = $('.js-search-bar');
        const top = searchBar.offset().top;
        const searchBarHeight = searchBar.outerHeight();
        const distance = searchBarHeight + top;
        $('.js-search-dropdown').css('max-height', winHeight - searchBarHeight + 'px');
        $('.js-search-dropdown').css('top', distance + 'px');
    }
}