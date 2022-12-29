<?php

       if (isset($_POST['minPrice']) && isset($_POST['maxPrice'])) :
           $minPrice = (int)$_POST['minPrice'];
           $maxPrice = (int)$_POST['maxPrice'];
       
           $priceMetaQuery = array(
               'key' => '_price',
               'value' => array($minPrice, $maxPrice),
               'type' => 'numeric',
               'compare' => 'BETWEEN'
           );
       else :
           $priceMetaQuery = array();
       endif;
       
       if (isset($_POST['popularProduct']) && $_POST['popularProduct'] === 'true') :
           $popularMetaQuery = array(
               'key' => 'popular_product',
               'value' => '1',
               'compare' => '='
           );
       else :
           $popularMetaQuery = array();
       endif;
       
       
       if (isset($_POST['discountProduct']) && $_POST['discountProduct'] === 'true') :
           $discountMetaQuery = array(
               'key' => '_sale_price',
               'value' => '0',
               'compare' => '>'
           );
       else :
           $discountMetaQuery = array();
       endif;
       
       
       if (isset($_POST['attributeOptions'])) :
           $attrArray = $_POST['attributeOptions'];
           $finalattrArray = array_map(function ($value) {
               return array(
                   'taxonomy' => $value['attributeName'],
                   'field' => 'slug',
                   'terms' => $value['attributeData'],
                   'operator' => 'IN'
               );
           }, $attrArray);
       else :
           $finalattrArray = array();
       endif;
       
       if (isset($_POST['pageNr'])) :
           $paged = (int)$_POST['pageNr'];
       else :
           $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
       endif;

        if (get_search_query()) :
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page' => 24,
                'paged' => $paged,
                's' => get_search_query(),
            );

        else :
            $args = array();
        endif;

        $products = new WP_Query($args);
        ?>

        <?php if ($products->have_posts() && get_search_query()) : ?>

            <div class="search-page__inside">

                <?php while ($products->have_posts()) : $products->the_post();
                    $productId = get_the_ID();
                    $product = wc_get_product($productId);
                    $regularPrice = $product->get_regular_price();
                    $salePrice = $product->get_sale_price();
                    $currentPrice = $product->get_price();
                    $galleryImageId = $product->get_gallery_image_ids()[1];
                    $postThumbnail = get_the_post_thumbnail($productId, 'product-thumb');
                    if ($salePrice) {
                        $discountPrice = round((($regularPrice - $salePrice) / $regularPrice) * 100);
                    }
                ?>

                    <div class="search-page__inside-item">
                        <a href="<?= get_permalink($productId); ?>">

                            <div class="search-page__inside-item-thumbnail<?= $postThumbnail ? ' has-thumbnail' : '' ?>">
                                <?php if (!$postThumbnail) : ?>
                                    <img alt="Product thumbnail placeholder" src="<?= get_template_directory_uri(); ?>/assets/img/defaultimages/product_placeholder.jpg" width="400" height="400">
                                <?php else : ?>
                                    <?= $postThumbnail; ?>
                                    <?= wp_get_attachment_image($galleryImageId, 'product-thumb'); ?>
                                <?php endif; ?>

                                <?php if ($salePrice) : ?>

                                    <span class="discount--percentage">-<?= $discountPrice; ?>%</span>

                                <?php endif; ?>

                                <?php if (get_field('popular_product', $productId)) : ?>

                                    <span class="popular--product"><?= __('Populaarne', 'kewebshop'); ?></span>

                                <?php endif; ?>

                            </div>

                            <h5><?php the_title(); ?></h5>

                            <div class="search-page__inside-item-prices">

                                <?php if ($salePrice) : ?>

                                    <span class="regular--price"><?= $regularPrice; ?>€</span>

                                <?php endif; ?>

                                <span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>€</span>

                            </div>
                        </a>

                    </div>

                <?php endwhile; ?>

            </div>

        <?php else : ?>

            <p>No search results found.</p>

        <?php endif; ?>