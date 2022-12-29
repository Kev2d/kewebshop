<?php

$objectId = isset($_POST['productCat']) ? (int)$_POST['productCat'] : $object->term_id;

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

$wp_query = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_archive_page' => 24,
    'paged' => $paged,
    'tax_query' => array_merge(
        array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $objectId
            )
        ),
        $finalattrArray
    ),
    'meta_query' => array('relation' => 'AND', $priceMetaQuery, $popularMetaQuery, $discountMetaQuery)
));
if ($wp_query->have_posts()) :
    $totalpost = $wp_query->found_posts;
    if ($totalpost >= 1) : ?>

        <?php
        while ($wp_query->have_posts()) : $wp_query->the_post();
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

            <div class="product-cat__products-inside-item">
                <a href="<?= get_permalink($productId); ?>">

                    <div class="product-cat__products-inside-item-thumbnail<?= $postThumbnail ? ' has-thumbnail' : '' ?>">
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

                    <div class="product-cat__products-inside-item-prices">

                        <?php if ($salePrice) : ?>

                            <span class="regular--price"><?= $regularPrice; ?>€</span>

                        <?php endif; ?>

                        <span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>€</span>

                    </div>
                </a>

            </div>

        <?php endwhile; ?>

    <?php endif; ?>

<?php
endif; ?>

<div class="pagination">

    <?php
    $totalpages = $wp_query->max_num_pages;
    $middlepage = (int)($totalpages / 2);

    $prev_page = '<a class="pagination-item pagination-prev js-pagination" data-page="' . ($paged - 1) . '"><svg>
<use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/arrow-left-icon.svg#arrow-left" href="' . get_template_directory_uri() . '/assets/img/icons/arrow-left-icon.svg#arrow-left"></use>
</svg></a>';
    $next_page = '<a class="pagination-item pagination-next js-pagination" data-page="' . ($paged + 1) . '"><svg>
<use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/arrow-right-icon.svg#arrow-right" href="' . get_template_directory_uri() . '/assets/img/icons/arrow-right-icon.svg#arrow-right"></use>
</svg></a>';

    $unactive_prev_page = '<span class="unactive-pagination-prev unactive-pagination"><svg>
<use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/arrow-left-icon.svg#arrow-left" href="' . get_template_directory_uri() . '/assets/img/icons/arrow-left-icon.svg#arrow-left"></use>
</svg></span>';
    $unactive_next_page = '<span class="unactive-pagination-next unactive-pagination"><svg>
<use xlink:href="' . get_template_directory_uri() . '/assets/img/icons/arrow-right-icon.svg#arrow-right" href="' . get_template_directory_uri() . '/assets/img/icons/arrow-right-icon.svg#arrow-right"></use>
</svg></span>';

    if ($wp_query->max_num_pages > 1) :
        if (1 === $paged) :
            echo $unactive_prev_page;
        else :
            echo $prev_page;
        endif;
        if (($wp_query->max_num_pages > 6) && ($paged !== 1 && $paged != $totalpages)) :
            if ($paged > 2) : ?>
                <div class="js-pagination" data-page="1">1</div>

                <?php if ($paged > 3) : ?>
                    <div class="js-pagination" data-page="<?= (int)($paged / 2); ?>">...</div>
            <?php endif;
            endif; ?>

            <div class="js-pagination" data-page="<?= $paged - 1; ?>"><?= $paged - 1; ?></div>
            <div class="js-pagination js-active" data-page="<?= $paged; ?>"><?= $paged; ?></div>
            <div class="js-pagination" data-page="<?= $paged + 1; ?>"><?= $paged + 1; ?></div>

            <?php if ($paged < ($totalpages - 1)) :
                if ($paged != ($totalpages - 2)) : ?>
                    <div class="js-pagination" data-page="<?= (int)(($totalpages + $paged) / 2); ?>">...</div>
                <?php endif; ?>

                <div class="js-pagination" data-page="<?= $totalpages ?>"><?= $totalpages ?></div>
            <?php endif;
        elseif ($wp_query->max_num_pages > 6) : ?>

            <div class="js-pagination <?= $paged == 1 ? "js-active" : ""; ?>" data-page="1">1</div>
            <div class="js-pagination" data-page="2">2</div>
            <div class="js-pagination" data-page="<?= $middlepage; ?>">...</div>
            <div class="js-pagination" data-page="<?= $totalpages - 1 ?>"><?= $totalpages - 1 ?></div>
            <div class="js-pagination <?= $paged == $totalpages ? "js-active" : ""; ?>" data-page="<?= $totalpages ?>"><?= $totalpages ?></div>

            <?php else :
            for ($i = 1; $i < $totalpages + 1; $i++) : ?>
                <div class="js-pagination <?= $paged == $i ? "js-active" : ""; ?>" data-page="<?= $i ?>"><?= $i ?></div>
    <?php endfor;
        endif;

        if ($wp_query->max_num_pages == $paged) :
            echo $unactive_next_page;
        else :
            echo $next_page;
        endif;
    endif;
    wp_reset_postdata(); ?>
</div>