<?php

function kewebshop_block_categories($categories, $post)
{
    $categories[] = [
        'slug' => 'kewebshop',
        'title' => __('Keweb Shop', 'kewebshop')
    ];

    return array_reverse($categories);
}
add_filter('block_categories', 'kewebshop_block_categories', 1, 2);

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types()
{


    if (function_exists('acf_register_block_type')) {

        acf_register_block_type(array(
            'name'              => 'favoriteCategories',
            'title'             => __('Favorite Products By Category'),
            'description'       => __('Show your favorite products by category'),
            'render_template'   => 'acf-blocks/favoriteCategories.php',
            'category'          => 'kewebshop',
            'icon'              => 'email',
            'keywords'          => array('favoriteCategories'),
        ));

        acf_register_block_type(array(
            'name'              => 'advertismentblock',
            'title'             => __('Advertise product'),
            'description'       => __('Advertise your favorit products'),
            'render_template'   => 'acf-blocks/advertisementBlock.php',
            'category'          => 'kewebshop',
            'icon'              => 'email',
            'keywords'          => array('advertismentblock'),
        ));

        acf_register_block_type(array(
            'name'              => 'popularproducts',
            'title'             => __('Popular products block'),
            'description'       => __('Show popular products'),
            'render_template'   => 'acf-blocks/popularProducts.php',
            'category'          => 'kewebshop',
            'icon'              => 'email',
            'keywords'          => array('popularproducts'),
        ));

        acf_register_block_type(array(
            'name'              => 'entertainmentblock',
            'title'             => __('Entertainment Block'),
            'description'       => __('Show video'),
            'render_template'   => 'acf-blocks/entertainmentBlock.php',
            'category'          => 'kewebshop',
            'icon'              => 'email',
            'keywords'          => array('entertainmentblock'),
        ));

        acf_register_block_type(array(
            'name'              => 'moreproductsblock',
            'title'             => __('Show More Products Block'),
            'description'       => __('Show 10 or more products in your e-shop'),
            'render_template'   => 'acf-blocks/moreProducts.php',
            'category'          => 'kewebshop',
            'icon'              => 'email',
            'keywords'          => array('moreproductsblock'),
        ));
        
    }
}

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'     => 'Theme Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-settings',
        'capability'    => 'edit_posts',
        'redirect'        => false
    ));

    acf_add_options_sub_page(array(
        'page_title'     => 'Theme Footer',
        'menu_title'    => 'Footer',
        'parent_slug'    => 'theme-settings',
    ));
}