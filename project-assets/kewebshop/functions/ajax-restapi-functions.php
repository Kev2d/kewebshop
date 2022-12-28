<?php
add_action('rest_api_init', function () {
    register_rest_route('baseURL/v1/baseEndPoint', '/moreProducts/', array(
        'methods' => 'POST',
        'callback' => 'restAPI_endpoint_moreProducts'
    ));
    register_rest_route('baseURL/v1/baseEndPoint', '/getwoocommerceproducts/', array(
        'methods' => 'POST',
        'callback' => 'get_woocommerce_products'
    ));
    register_rest_route('baseURL/v1/baseEndPoint', '/getproductfilters/', array(
        'methods' => 'POST',
        'callback' => 'get_product_filters'
    ));
    register_rest_route('baseURL/v1/baseEndPoint', '/removeproductreview/', array(
        'methods' => 'POST',
        'callback' => 'remove_product_review'
    ));
    register_rest_route('baseURL/v1/baseEndPoint', '/getlistofproducts/', array(
        'methods' => 'POST',
        'callback' => 'get_list_of_products'
    ));
});

function restAPI_endpoint_moreProducts()
{
    ob_start();
    include(get_template_directory() . '/acf-blocks/templates/more-products.php');
    $template = ob_get_contents();
    ob_end_clean();
    return $template;
}

function get_woocommerce_products()
{
    ob_start();
    get_template_part('woocommerce/templates/all-products');
    $products_template = ob_get_contents();
    ob_end_clean();
    return $products_template;
}


function get_product_filters()
{
    ob_start();
    get_template_part('woocommerce/templates/all-products-header');
    $products_template = ob_get_contents();
    ob_end_clean();
    return $products_template;
}

function remove_product_review()
{
    $review_id = intval($_POST['review_id']);
    $review = get_comment($review_id);
    $the_user = get_user_by('email', $_POST['user_email']);
    $current_user_id = $the_user->ID;
    // Check if the current user is the author of the review
    if ($current_user_id == $review->user_id) {
        // The current user is the author, so delete the review
        wp_delete_comment($review_id, true);
    } else {
        // The current user is not the author, so display an error message
        wp_die('You are not authorized to delete this review.');
    }
    return true;
}

function get_list_of_products()
{
    $products_array = array();

    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        's' => $_POST['searchInput'],
        'search_columns' => array('title'),
        'posts_per_page' => 10
    );
    $products = new WP_Query($args);

    foreach ($products->posts as &$product) {
        $productTitle = $product->post_title;
        $productId = $product->ID;
        $productItem = wc_get_product($productId);
        $salePrice = $productItem->get_sale_price();
        $currentPrice = $productItem->get_price();
        $postThumbnailUrl = get_the_post_thumbnail_url($productId, 'search-thumb');
        $product_array = array(
            'ID' => $productId,
            'title' => $productTitle,
            'price' => $currentPrice,
            'isOnSale' => $salePrice ? 1 : 0,
            'thumbUrl' => $postThumbnailUrl,
            'productUrl' => get_permalink($productId),
        );

        array_push($products_array, $product_array);
    }


    return json_encode($products_array);
}
