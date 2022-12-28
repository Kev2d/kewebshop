

$(function () {
    $('.js-open-submenu').click(function () {
        $(this).toggleClass('js-open');
        $(this).parents().next('.js-submenu').slideToggle();
    });

    if ($('.js-submenu.js-open').length) {
        $('.js-submenu.js-open').show();
    }
    $(document).on("click", '.js-filter-label', function () {
        $('.js-filter-options').hide();
        $(this).next('.js-filter-options').fadeIn(300);
    });

    const minValue = $('.js-slider-min').val();
    const maxValue = $('.js-slider-max').val();

    $("#js-price-slider").slider({
        min: +minValue,
        max: +maxValue,
        step: 1,
        values: [+minValue, +maxValue],
        slide: function (event, ui) {
            for (let i = 0; i < ui.values.length; ++i) {
                $("input.sliderValue[data-index=" + i + "]").val(ui.values[i]);
            }
        }
    });

    $("input.sliderValue").change(function () {
        const $this = $(this);
        $("#js-price-slider").slider("values", $this.data("index"), $this.val());
    });

    $(document).on("click", '.js-cat', function (e) {
        e.preventDefault();
        const thisId = $(this).parents('li').data('cat');
        $('.js-cat').removeClass('highlight--text');
        $(this).addClass('highlight--text');
        $(this).siblings('.js-open-submenu').addClass('js-open');
        $(this).parents('li').next('.js-submenu').slideDown();
        getProductsByCategory(thisId);
        getProductsFilters(thisId);
    });

    async function getProductsByCategory(id) {
        await $.ajax({
            type: 'POST',
            url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getwoocommerceproducts',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
                $('.js-all-products').css('opacity', 0.5);
            },
            data: {
                productCat: id,
            },
            success: function (response) {
                if (response) {
                    $('.js-all-products').html(response);
                    $('.js-all-products').removeAttr('style');
                }
            }
        });
    }

    async function getProductsFilters(id) {
        await $.ajax({
            type: 'POST',
            url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getproductfilters',
            data: {
                productCat: id,
            },
            success: function (response) {
                if (response) {
                    $('.js-products-header').html(response);
                }
            }
        });
    }
});

$(document).on("change", ".js-filter-options :checkbox", debounce(function () {
    const parent = $(this).closest('.js-filter-options');
    const numCheckedInputs = parent.find('input:checked').length;
    const filterLabel = $(this).closest('.js-filter-options').parents().children('.js-filter-label');
    if (numCheckedInputs > 0) {
        filterLabel.addClass('js-active');
        if (!$(filterLabel).children('.js-filter-count').length) {
            filterLabel.append('<span class="js-filter-count"></span>');
        }
        if ($(filterLabel).children('.js-filter-count').length) {
            $(filterLabel).children('.js-filter-count').text('(' + numCheckedInputs + ')');
        }
    } else {
        $(filterLabel).children('.js-filter-count').remove();
        filterLabel.removeClass('js-active');
    }
    getProductsByFilterOptions();
}, 300));

$('#js-price-slider').on('slidechange', debounce(function () {
    const filterLabel = $(this).closest('.js-filter-options').parents().children('.js-filter-label');
    filterLabel.addClass('js-active');
    getProductsByFilterOptions();
}, 300));

$(document).on("change", ".js-switch input", debounce(function () {
    const filterLabel = $(this).closest('.js-filter-label');
    if ($(this).is(':checked')) {
        filterLabel.addClass('js-active');
    } else {
        filterLabel.removeClass('js-active');
    }
    getProductsByFilterOptions();
}, 300));

async function getProductsByFilterOptions() {
    const activeCat = $('.js-cat.highlight--text').parents('li').data('cat');
    const minPrice = $('.js-slider-min').val();
    const maxPrice = $('.js-slider-max').val();
    let popularProduct = false;
    let discountProduct = false;
    const attrArray = [];
    if ($('#js-popular').is(':checked')) {
        popularProduct = true;
    }
    if ($('#js-discount').is(':checked')) {
        discountProduct = true;
    }
    $('.js-filter-options').each(function () {
        if ($(this).data("attr")) {
            const dataArray = [];
            $(this).find(':input').filter(':checked').each(function () {
                dataArray.push($(this).val());
            });

            if (dataArray.length > 0) {

                const attributesValues = {
                    attributeName: $(this).data("attr"),
                    attributeData: dataArray,
                }
                attrArray.push(attributesValues);
            }
        }
    });
    await $.ajax({
        type: 'POST',
        url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getwoocommerceproducts',
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
            $('.js-all-products').css('opacity', 0.5);
        },
        data: {
            productCat: activeCat,
            minPrice: minPrice,
            maxPrice: maxPrice,
            popularProduct: popularProduct,
            discountProduct: discountProduct,
            attributeOptions: attrArray,
        },
        success: function (response) {
            if (response) {
                $('.js-all-products').html(response);
                $('.js-all-products').removeAttr('style');
            }
        }
    });
}

$(document).mouseup(function (e) {
    const container = $(".js-filter-options");
    // if the target of the click isn't the container nor a descendant of the container
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        $('.js-filter-options').hide();
    }
});


