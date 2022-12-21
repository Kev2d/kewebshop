$(".js-min-qty").click(function () {
  let curVal = $('.js-single-qty').val();
  console.log(curVal);
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


// document.addEventListener('click', function(event) {
//   event.preventDefault();
//   // Check if the clicked element is a delete button
//   if (!event.target.matches('.reviews__delete-button')) return;

//   // Get the comment ID from the button's data attribute
//   var commentId = event.target.dataset.commentId;

//   // Send a DELETE request to the server to delete the comment
//   fetch(myObj.restURL + 'wc/v3/products/reviews/' + commentId + '?force=true', {
//     method: 'DELETE',
//     headers: {
//       'X-WP-Nonce': myObj.restNounce
//     }
//   }).then(function() {
//     // Reload the page to update the list of reviews
//     console.log(myObj.restURL + 'wc/v3/products/reviews/' + commentId + '?force=true');
//    /*  window.location.reload(); */
//   });
// });

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
      thisObj.html('<span class="js-loader"></span>');
    },
    data: {
      review_id: id,
      user_email: email
    },
    success: function (response) {
      if (response) {
        thisObj.parents('.js-user-review').html('<span>Tagasiside edukalt eemaldatud</span>');
      }
    }
  });
}