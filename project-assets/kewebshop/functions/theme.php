<?php
//CUSTOM CSS AND JS

function keweb_script_enqueue()
{
  wp_enqueue_style('slick-css', get_template_directory_uri() . '/assets/include/slick/slick.css', array(), null, 'all');
  wp_enqueue_script('slick-js', get_template_directory_uri() . '/assets/include/slick/slick.min.js', array(), '1.0.0', true);
  wp_enqueue_style('main-css', get_template_directory_uri() . '/dist/style.min.css', array(), filemtime(get_stylesheet_directory() . '/dist/style.min.css'), 'all');
  wp_enqueue_script('js', get_template_directory_uri() . '/dist/keweb.min.js', array(), filemtime(get_stylesheet_directory() . '/dist/keweb.min.js'), true);
  if (is_archive('product')) {
    wp_enqueue_script('js-productcat', get_template_directory_uri() . '/dist/productCat.min.js', array(), filemtime(get_stylesheet_directory() . '/dist/productCat.min.js'), true);
  }
  if ( is_product() ){
    wp_enqueue_script('js-singleproduct', get_template_directory_uri() . '/dist/singleProduct.min.js', array(), filemtime(get_stylesheet_directory() . '/dist/singleProduct.min.js'), true);
  }

  if ( is_search() ) {
    wp_enqueue_script('js-search', get_template_directory_uri() . '/dist/searchPage.min.js', array(), filemtime(get_stylesheet_directory() . '/dist/searchPage.min.js'), true);
  }

  wp_localize_script('js', 'myObj', array(
    'restURL' => rest_url(),
    'restNonce' => wp_create_nonce('wp_rest')
  ));
  wp_localize_script('js', 'js_strings', array('showMoreProducts' => __('Näita rohkem tooteid', 'kewebshop'), 'commentDeleted' => __('Tagasiside edukalt eemaldatud', 'kewebshop')));
  wp_localize_script('js', 'templateUrl', array('url' => get_template_directory_uri()));


  //Get product total amount
  wp_register_script('getProductTotal', 'getProductTotal_url');
  wp_enqueue_script('getProductTotal');
  $translation_array = array('total_products' => wp_count_posts('product')->publish);
  //after wp_enqueue_script
  wp_localize_script('getProductTotal', 'products', $translation_array);

  wp_deregister_script('debouncer');
  wp_enqueue_script('debouncer', get_template_directory_uri() . '/assets/js/debouncer.js', array(), null, false);

  // Dequeue default jquery and add new
  wp_deregister_script('jquery');
  wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), null, false);

  wp_deregister_script('jquery-ui');
  wp_enqueue_script('jquery-ui', get_template_directory_uri() . '/assets/js/jquery-ui.min.js', array(), null, false);

  wp_deregister_script('jquery-zoom');
  wp_enqueue_script('jquery-zoom', get_template_directory_uri() . '/assets/js/jquery.zoom.min.js', array(), null, false);
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

add_image_size('search-thumb', 100, 100);
add_image_size('product-thumb', 400, 400);
add_image_size('single-thumb', 1200, 1200);

