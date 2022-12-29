$(document).on("click", '.js-pagination', function (e) {
    e.preventDefault();
    const productCat = $('.js-cat.highlight--text').parents('li').data('cat');
    const pageNr = $(this).data('page');
    getProductsByCategory(pageNr, productCat);
});
    async function getProductsByCategory(pageNr, id) {
        await $.ajax({
            type: 'POST',
            url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getwoocommerceproducts',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
                $('.js-all-products').css('opacity', 0.5);
            },
            data: {
                productCat: id,
                pageNr: pageNr
            },
            success: function (response) {
                if (response) {
                    $('.js-all-products').html(response);
                    $('.js-all-products').removeAttr('style');
                }
            }
        });
}
