<?php

function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

/**
 * Track product views. Always.
 */
function wc_track_product_view_always()
{
    if (!is_singular('product') /* xnagyg: remove this condition to run: || ! is_active_widget( false, false, 'woocommerce_recently_viewed_products', true )*/) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed'])) { // @codingStandardsIgnoreLine.
        $viewed_products = array();
    } else {
        $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed']))); // @codingStandardsIgnoreLine.
    }

    // Unset if already in viewed products list.
    $keys = array_flip($viewed_products);

    if (isset($keys[$post->ID])) {
        unset($viewed_products[$keys[$post->ID]]);
    }

    $viewed_products[] = $post->ID;

    if (count($viewed_products) > 15) {
        array_shift($viewed_products);
    }

    // Store for session only.
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

remove_action('template_redirect', 'wc_track_product_view', 20);
add_action('template_redirect', 'wc_track_product_view_always', 20);





add_filter('woocommerce_breadcrumb_defaults', 'my_change_breadcrumb_delimiter');
function my_change_breadcrumb_delimiter($defaults)
{
    $defaults['delimiter'] = '<span class="breadcrumb--delimiter"> > </span>  ';
    $defaults['home'] = 'Shop';
    return $defaults;
}


function custom_woocommerce_breadcrumb($term)
{
    // Get the shop page id and URL
    $shop_page_id = get_option('woocommerce_shop_page_id');
    $shop_page_url = get_permalink($shop_page_id);
    $shop_page_parent_id = wp_get_post_parent_id($shop_page_id);

    $html = '<nav class="woocommerce-breadcrumb">';

    // Add a link to the shop page
    $html .= '<a href="' . esc_url($shop_page_url) . '">' . __('Home', 'kewebshop') . '</a>';

    // Add a delimiter between the shop page and the current term
    $html .= '<span class="breadcrumb--delimiter"> &gt; </span>';

    // If the current term has a parent, add a link to the parent term
    if ($term->parent > 0) {
        $parent_terms = get_the_terms($term->parent, 'product_cat');
        if (!empty($parent_terms)) {
            $parent_term = array_shift($parent_terms);
            $html .= '<a href="' . esc_url(get_term_link($parent_term)) . '">' . esc_html($parent_term->name) . '</a>';
            $html .= '<span class="breadcrumb--delimiter"> &gt; </span>';
        }
    }

    // Add the current term to the breadcrumb
    $html .= esc_html($term->name);

    $html .= '</nav>';

    echo $html;
}


add_filter('woocommerce_product_review_comment_form_args', 'custom_review_form_args');
function custom_review_form_args($args)
{
    $args['comment_field'] = '<p class="comment-form-comment">';
    $args['comment_field'] .= '<label for="comment">' . esc_html__('Kirjuta tagasiside või jäta hinnang', 'kewebshop') . '&nbsp;<span class="required">*</span></label>';
    $args['comment_field'] .= '<textarea id="comment" name="comment" cols="45" rows="8" required></textarea>';
    $args['comment_field'] .= '</p>';
    if (wc_review_ratings_enabled()) {
        $args['comment_field'] .= '<p class="comment-form-rating">';
        $args['comment_field'] .= '<span class="thumbs-up">';
        $args['comment_field'] .= '<input type="radio" name="rating" id="thumbs-up" value="5" />';
        $args['comment_field'] .= '<label for="thumbs-up"><svg>
        <use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/thumbs-up-icon.svg#thumbs-up" href="' . get_template_directory_uri() . '/assets/img/icons/thumbs-up-icon.svg#thumbs-up"></use>
        </svg>' . esc_html__('Meeldib', 'kewebshop') . '</label>';
        $args['comment_field'] .= '</span>';
        $args['comment_field'] .= '<span class="thumbs-down">';
        $args['comment_field'] .= '<input type="radio" name="rating" id="thumbs-down" value="1" />';
        $args['comment_field'] .= '<label for="thumbs-down"><svg>
        <use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/thumbs-down-icon.svg#thumbs-down" href="' . get_template_directory_uri() . '/assets/img/icons/thumbs-down-icon.svg#thumbs-down"></use>
        </svg></label>';
        $args['comment_field'] .= '</span>';
        $args['comment_field'] .= '</p>';
    }
    return $args;
}


function handle_delete_request() {
    // Check if the nonce is valid
    if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'my-nonce')) {
      wp_send_json_error(array('message' => 'Invalid nonce'));
    }
  
    // Get the comment ID from the request
    $comment_id = $_REQUEST['comment_id'];
  
    // Delete the comment
    wp_delete_comment($comment_id, true);
  
    wp_send_json_success();
  }