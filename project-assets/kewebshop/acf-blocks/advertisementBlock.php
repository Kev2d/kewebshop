<section class="advertisement js-advertisement">

    <?php if (have_rows('advertisement_repeater')) : ?>

        <?php while (have_rows('advertisement_repeater')) : the_row();
            $product = get_sub_field('product');
            $productId = $product->ID;
        ?>

            <div class="advertisement__inside">

                <div class="advertisement__inside-item content<?= get_sub_field('image_left_or_right') ? ' right' : '' ?>">

                    <?php if (get_sub_field('title')) : ?>

                        <h1><?php the_sub_field('title'); ?></h1>

                    <?php else : ?>

                        <h1><?= $product->post_title; ?></h1>

                    <?php endif; ?>


                    <?php if (get_sub_field('desc')) : ?>

                        <?php the_sub_field('desc'); ?>

                    <?php else : ?>

                        <p><?= $product->post_content; ?></p>

                    <?php endif; ?>


                    <?php if (get_sub_field('link_address') || $product) : ?>

                        <a class="red--button" href="<?= get_sub_field('link_address') ? the_sub_field('link_address') : get_permalink($productId) ?>">
                        <?= get_sub_field('link_title') ? the_sub_field('link_title') : __('Vaata toodet', 'kewebshop') ?>
                        </a>

                    <?php endif; ?>

                </div>
                <div class="advertisement__inside-item">

                    <?php
                    $adImg = get_sub_field('image');
                    if ($adImg) : ?>
                        <img src="<?= esc_url($adImg['url']); ?>" alt="<?= esc_attr($adImg['alt']); ?>" />

                    <?php else :
                        $postThumbnail = get_the_post_thumbnail($productId);
                    ?>

                        <?php if (!$postThumbnail) : ?>
                            <img alt="Product thumbnail placeholder" src="<?= get_template_directory_uri(); ?>/assets/img/defaultimages/product_placeholder.jpg" width="400" height="400">
                        <?php else : ?>
                            <?= $postThumbnail; ?>
                        <?php endif; ?>

                    <?php endif; ?>

                </div>
            </div>

        <?php endwhile; ?>

    <?php endif; ?>



</section>