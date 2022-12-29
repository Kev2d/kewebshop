$(document).on("click", '.js-pagination', function (e) {
    e.preventDefault();
    let productCat;
    const searchQuery = $('.js-search-page').data('searchquery');
    if ($('.js-cat.highlight--text').length) {
        productCat = $('.js-cat.highlight--text').parents('li').data('cat');
    } else {
        productCat = [];
        $('.js-cat').each(function (index) {
            const thisId = $(this).parents('li').data('cat');
            productCat.push(thisId)
        });
    }
    const pageNr = $(this).data('page');
    getProductsByCategory(pageNr, productCat, searchQuery);
});
async function getProductsByCategory(pageNr, id, searchQuery) {
    await $.ajax({
        type: 'POST',
        url: myObj.restURL + 'baseUrl/v1/baseEndPoint/getsearchproducts',
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
            $('.js-all-results').css('opacity', 0.5);
        },
        data: {
            productCat: id,
            pageNr: pageNr,
            searchQuery: searchQuery
        },
        success: function (response) {
            if (response) {
                $('.js-all-results').html(response);
                $('.js-all-results').removeAttr('style');
            }
        }
    });
}
