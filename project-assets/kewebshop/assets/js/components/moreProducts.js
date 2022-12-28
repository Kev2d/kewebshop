$(function () {
    let productCount = 15;
    $(".js-more-products").each(function () {
        const productsContainer = $(this);
        if ($(this).children('.js-item').length >= 10) {
            $(this).after(`<span class="js-show-more red--button">${js_strings.showMoreProducts}</span>`);
        }

        $(document).on("click", '.js-show-more', function (e) {
            e.preventDefault();
            getMoreProducts($(this));
        });

        async function getMoreProducts(thisObj) {
            /*   let postdata = {
                  showMore: productCount,
                  action: 'show_more_products',
              } */
            /*     $.ajax({
                    data: postdata,
                    url: my_ajax_object.ajaxurl,
                    type: "POST",
                    beforeSend: function () {
                        $(productsContainer).siblings('.js-show-more').hide();
                        $(productsContainer).siblings('.js-loader').show();
                    },
                    success: function (data) {
                        $('.js-more-products').html(data);
                        productCount += 10;
                        $(productsContainer).siblings('.js-show-more').show();
                        if ($(productsContainer).children('.js-item').length >= products.total_products) {
                            $(productsContainer).siblings('.js-show-more').remove();
                        }
                        $(productsContainer).siblings('.js-loader').hide();
                    }
                }); */
            await $.ajax({
                type: 'POST',
                url: myObj.restURL + 'baseUrl/v1/baseEndPoint/moreProducts',
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
                    $(productsContainer).siblings('.js-show-more').hide();
                    $(productsContainer).siblings('.js-loader').show();
                },
                data: {
                    showMore: productCount,
                },
                success: function (response) {
                    if (response) {
                        $('.js-more-products').html(response);
                        productCount += 10;
                        $(productsContainer).siblings(thisObj).show();
                        if ($(productsContainer).children('.js-item').length >= products.total_products) {
                            $(productsContainer).siblings(thisObj).remove();
                        }
                        $(productsContainer).siblings('.js-loader').hide();
                    }
                }

            });
        }
    });
});
