<section class="latest-products">

<?php if (get_field('favorite_products_title')) : ?>

<h1><?php the_field('favorite_products_title'); ?></h1>

<?php endif; ?>

    <?php if (have_rows('latest_products_repeater')) :
        $i = 1;
        $x = 1;
    ?>

        <div class="latest-products__tabs js-all-tabs">

            <?php while (have_rows('latest_products_repeater')) : the_row();
                $productsTaxonomy = get_sub_field('products');
                $taxTerm = get_term($productsTaxonomy[0]);
            ?>
                <a href="#tab_<?= $i; ?>" class="js-tab<?= $i === 1 ? ' js-active' : '' ?>">
                    <h4>

                        <?php if (get_sub_field('category_title')) : ?>

                            <?php the_sub_field('category_title'); ?>

                        <?php else : ?>

                            <?= $taxTerm->name; ?>

                        <?php endif; ?>
                    </h4>
                </a>

            <?php $i++;
            endwhile; ?>

        </div>

        <?php while (have_rows('latest_products_repeater')) : the_row();
            $productsTaxonomy = get_sub_field('products');
        ?>

            <div class="latest-products__products<?= $x === 1 ? '' : ' js-hidden' ?>" id="tab_<?= $x; ?>">

                <?php
                $args = array(
                    'post_type'             => 'product',
                    'post_status'           => 'publish',
                    'ignore_sticky_posts'   => 1,
                    'posts_per_page'        => 5,
                    'tax_query'             => array(
                        array(
                            'taxonomy'      => 'product_cat',
                            'field' => 'term_id', //This is optional, as it defaults to 'term_id'
                            'terms'         => $productsTaxonomy[0],
                            'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
                        )
                    )
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

                        <div class="latest-products__products-item">
                            <a href="<?= get_permalink($productId); ?>">

                                <div class="latest-products__products-item-thumbnail<?= $postThumbnail ? ' has-thumbnail' : '' ?>">
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

                                <div class="latest-products__products-item-prices">

                                    <?php if ($salePrice) : ?>

                                        <span class="regular--price"><?= $regularPrice; ?>€</span>

                                    <?php endif; ?>

                                    <span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>€</span>

                                </div>
                            </a>

                        </div>

                <?php endwhile;
                endif; ?>

            </div>

        <?php $x++;
        endwhile; ?>

    <?php endif; ?>


</section>