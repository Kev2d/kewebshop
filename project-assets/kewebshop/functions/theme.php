<?php
//CUSTOM CSS AND JS

function keweb_script_enqueue()
{
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/include/slick/slick.css', array(), null, 'all');
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/include/slick/slick.min.js', array(), '1.0.0', true);
  wp_enqueue_style('main-css', get_template_directory_uri() . '/dist/style.min.css', array(), filemtime(get_stylesheet_directory() . '/dist/style.min.css'), 'all');
  wp_enqueue_script('js', get_template_directory_uri() . '/dist/keweb.min.js', array(), filemtime(get_stylesheet_directory() . '/dist/keweb.min.js'), true);
  /*   wp_localize_script(
    'js',
    'my_ajax_object',
    array('ajaxurl' => admin_url('admin-ajax.php'))
  ); */
  wp_localize_script('js', 'myObj', array(
    'restURL' => rest_url(),
    'restNonce' => wp_create_nonce('wp_rest')
  ));
  wp_localize_script('js', 'js_strings', array('showMoreProducts' => __('Näita rohkem tooteid', 'kewebshop')));
  wp_localize_script('js', 'templateUrl', array('url' => get_template_directory_uri()));


  //Get product total amount
  wp_register_script('getProductTotal', 'getProductTotal_url');
  wp_enqueue_script('getProductTotal');
  $translation_array = array('total_products' => wp_count_posts('product')->publish);
  //after wp_enqueue_script
  wp_localize_script('getProductTotal', 'products', $translation_array);

  wp_deregister_script('debouncer');
  wp_enqueue_script('debouncer', get_template_directory_uri() . '/dist/js/debouncer.js', array(), null, false);

  // Dequeue default jquery and add new
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', get_template_directory_uri() . '/dist/js/jquery.min.js', array(), null, false);

  wp_deregister_script('jquery-ui');
  wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/dist/js/jquery-ui.min.js', array(), null, false);

  wp_deregister_script('jquery-zoom');
  wp_enqueue_script('jquery-zoom', get_template_directory_uri() . '/include/slick/jquery.zoom.min.js', array(), null, false);
}

add_action('wp_enqueue_scripts', 'keweb_script_enqueue');

//MENUS

function keweb_theme_setup()
{
  add_theme_support('menus');
  add_theme_support('post-thumbnails');
  add_theme_support('post-formats', array('aside', 'image', 'video'));
  register_nav_menu('primary', 'Primary Header Navigation');
}

add_action('init', 'keweb_theme_setup');


//Custom-Logo

add_theme_support('custom-logo');

add_filter('acf/settings/remove_wp_meta_box', '__return_false');


//Image sizes

add_image_size('product-thumb', 400, 400);
add_image_size('single-thumb', 1200, 1200);


//ajax

/* add_action('wp_ajax_show_more_products', 'ajax_show_more_products');
add_action('wp_ajax_nopriv_show_more_products', 'ajax_show_more_products');

function ajax_show_more_products()
{
  require(get_template_directory() . '/acf-blocks/templates/more-products.php');
  exit;
}
 */

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
