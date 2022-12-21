<footer class="footer">

    <div class="footer__upper">

        <div class="footer__upper-item">

            <?php if (get_field('logo', 'option')) : $image = get_field('logo', 'option'); ?>

                <img src="<?= $image['url']; ?>" alt="<?= $image['alt']; ?>" />

            <?php else : ?>

                <h1><?= get_bloginfo('name'); ?></h1>

            <?php endif; ?>

            <?php if (get_field('slogan', 'option')) : ?>
                <?= get_field('slogan', 'option'); ?>
            <?php endif; ?>

        </div>


        <?php if (have_rows('categories', 'option')) : ?>

            <div class="footer__upper-item">


                <h4><?= __('kategooriad', 'kewebshop'); ?></h4>

                <?php while (have_rows('categories', 'option')) : the_row();
                    $termCat = get_sub_field('category', 'option');
                ?>
                    <?php foreach ($termCat as &$id) :
                        if ($term = get_term_by('id', $id, 'product_cat')) :
                    ?>
                            <a href="<?= get_term_link($id); ?>"><?= $term->name; ?></a>
                    <?php
                        endif;
                    endforeach; ?>

                <?php endwhile; ?>

            </div>

        <?php endif; ?>

        <?php if (have_rows('social', 'option')) : ?>

            <div class="footer__upper-item">

                <h4><?= __('Sotsiaalmeedia', 'kewebshop'); ?></h4>

                <?php while (have_rows('social', 'option')) : the_row(); ?>

                    <?php
                    $link = get_sub_field('social_link', 'option');
                    if ($link) :
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                        <a href="<?= esc_url($link_url); ?>" target="<?= esc_attr($link_target); ?>"><?= esc_html($link_title); ?></a>
                    <?php endif; ?>
                <?php endwhile; ?>

            </div>

        <?php endif; ?>


        <?php if (have_rows('legal_links', 'option')) : ?>

            <div class="footer__upper-item">

                <h4><?= __('Juriidiline info', 'kewebshop'); ?></h4>

                <?php while (have_rows('legal_links', 'option')) : the_row(); ?>

                    <?php
                    $link = get_sub_field('link', 'option');
                    if ($link) :
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                    ?>
                        <a href="<?= esc_url($link_url); ?>" target="<?= esc_attr($link_target); ?>"><?= esc_html($link_title); ?></a>
                    <?php endif; ?>
                <?php endwhile; ?>

            </div>

        <?php endif; ?>


        <?php if (get_field('sale_products', 'option')) : ?>

            <?php
            $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => 6,
                'meta_query' => WC()->query->get_meta_query(),
                'post__in' => array_merge(array(0), wc_get_product_ids_on_sale())
            );
            $products = new WP_Query($args);
            ?>

            <?php if ($products->have_posts()) : ?>
                <div class="footer__upper-item">
                    <h4 class="highlight--text"><?= __('Soodusmüük!', 'kewebshop'); ?></h4>

                    <?php
                    while ($products->have_posts()) : $products->the_post();
                        $productId = get_the_ID();
                    ?>

                        <a href="<?= get_permalink($productId); ?>" target="_blank"><?= get_the_title($productId); ?></a>

                    <?php endwhile; ?>
                </div>
            <?php
            endif; ?>

        <?php endif; ?>


        <?php if (get_field('show_recently_viewed_products', 'option') && $_COOKIE['woocommerce_recently_viewed']) : ?>

            <div class="footer__upper-item">
                <h4><?= __('Viimati vaadatud tooted', 'kewebshop'); ?></h4>


                <?php $recentlyViewed = explode("|", $_COOKIE['woocommerce_recently_viewed']); ?>

                <?php foreach ($recentlyViewed as &$productId) : ?>

                    <a href="<?= get_permalink($productId); ?>" target="_blank"><?= get_the_title($productId); ?></a>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>


    </div>

    <div class="footer__bottom">

        <p>© <?= __('Kõik õigused malepood.ee', 'kewebshop'); ?></p>

    </div>

</footer>
<?php wp_footer(); ?>
</body>

</html>