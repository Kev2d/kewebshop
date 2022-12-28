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


async function getSearchData(value) {
    await $.ajax({
        type: 'POST',
        url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getlistofproducts',
        data: {
            searchInput: value,
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
        },
        success: function (response) {
            if (response) {
                removeSearch();
                const productArr = JSON.parse(response);
                console.log(productArr);
                createSearchDropDown(productArr);
            }
        }
    });
}

function createSearchDropDown(productArr) {
    if ($(".js-search-dropdown").length === 0) {
        $(".js-shop-header").after('<ul class="search-dropdown js-search-dropdown"></ul>');
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

function removeSearch() {
    $('.js-search-dropdown').remove();
}

function setSearchHeight() {
    if ($('.js-search-dropdown').length > 0) {
        const headerHeight = $('.js-shop-header').outerHeight();
        const winHeight = $(window).height();
        console.log(headerHeight,winHeight)
        $('.js-search-dropdown').css('max-height', winHeight - headerHeight);
    }
}