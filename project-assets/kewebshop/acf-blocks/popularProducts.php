<section class="popular-products">

    <?php if (get_field('popular_products_title')) : ?>

        <h1><?php the_field('popular_products_title'); ?></h1>

    <?php endif; ?>
    <div class="popular-products__inside">

        <?php if (have_rows('popular_products_repeater')) : ?>

            <?php while (have_rows('popular_products_repeater')) : the_row();
                $product = get_sub_field('product');
                $productId = $product->ID;
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
                <div class="popular-products__inside-item">
                    <a href="<?php get_permalink($productId); ?>">

                        <div class="popular-products__inside-item-thumbnail<?= $postThumbnail ? ' has-thumbnail' : '' ?>">
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

                        <div class="popular-products__inside-item-prices">

                            <?php if ($salePrice) : ?>

                                <span class="regular--price"><?= $regularPrice; ?>???</span>

                            <?php endif; ?>


                            <span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>???</span>

                        </div>
                    </a>


                </div>

            <?php endwhile;
        else :
            ?>

            <?php
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => 4,
                'meta_key'      => 'popular_product',
                'meta_value'    => 1
            );
            $products = new WP_Query($args);
            ?>

            <?php if ($products->have_posts()) :
                while ($products->have_posts()) : $products->the_post();
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

                    <div class="popular-products__inside-item">
                        <a href="<?= get_permalink($productId); ?>">

                            <div class="popular-products__inside-item-thumbnail<?= $postThumbnail ? ' has-thumbnail' : '' ?>">
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

                            <div class="popular-products__inside-item-prices">

                                <?php if ($salePrice) : ?>

                                    <span class="regular--price"><?= $regularPrice; ?>???</span>

                                <?php endif; ?>


                                <span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>???</span>

                            </div>
                        </a>

                    </div>

            <?php endwhile;
            endif; ?>

        <?php endif; ?>


    </div>

</section>