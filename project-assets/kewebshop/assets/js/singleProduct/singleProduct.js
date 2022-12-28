$(".js-min-qty").click(function () {
  let curVal = $('.js-single-qty').val();
  if (curVal <= 1) {
    $('.js-single-qty').val(1);
  } else {
    $('.js-single-qty').val(+curVal - 1);
  }
});

$(".js-plus-qty").click(function () {
  const curVal = $('.js-single-qty').val();
  $('.js-single-qty').val(+curVal + 1);
});
$(document).on("click", '.js-delete-review', function (e) {
  e.preventDefault();
  const thisId = $(this).data('comment-id');
  const thisEmail = $(this).data('user-email');
  const thisObj = $(this);
  removeReview(thisId, thisEmail, thisObj);
});


async function removeReview(id, email, thisObj) {
  await $.ajax({
    type: 'POST',
    url: myObj.restURL + 'baseUrl/v1/baseEndPoint/removeproductreview',
    beforeSend: function (xhr) {
      xhr.setRequestHeader('X-WP-Nounce', myObj.restNounce);
      thisObj.parents('.js-user-review').html('<span>' + js_strings.commentDeleted + '</span>');
    },
    data: {
      review_id: id,
      user_email: email
    },
  });
}

$("#js-review-comment").on("keydown keyup", function () {
  if ($("#js-review-comment").val().trim() !== '') {
    $('.js-add-review').addClass('js-active')
  } else {
    $('.js-add-review').removeClass('js-active')
  }
});

$("#js-review-thumbs-up").on("click", function () {
  $('#js-review-thumbs-down').prop('checked', false);
});

$("#js-review-thumbs-down").on("click", function () {
  $('#js-review-thumbs-up').prop('checked', false);
});