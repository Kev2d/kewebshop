<?php
/*
Template Name: Search Results
*/
get_header(); ?>

<div class="content">

    <div class="search-page js-search-page" <?= get_search_query() ? 'data-searchquery="' . get_search_query() . '"' : '' ?>>

        <div class="search-page__sidemenu">

            <?php if (get_search_query()) :
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                    's' => get_search_query(),
                );
            else :
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'ignore_sticky_posts' => 1,
                );
            endif;

            $wpquery = new WP_Query($args);
            ?>

            <?php if ($wpquery->have_posts()) :
                $catArr = array();
            ?>

                <ul>

                    <?php while ($wpquery->have_posts()) : $wpquery->the_post();
                        $productId = get_the_ID();
                        $productCategories = wp_get_post_terms($productId, 'product_cat');
                    ?>

                        <?php foreach ($productCategories as $productCategorie) :
                            array_push($catArr, $productCategorie->term_id);
                        endforeach ?>

                    <?php endwhile;
                    $uniqueCatArr = array_unique($catArr);
                    ?>

                    <?php foreach ($uniqueCatArr as $cat_id) : ?>
                        <?php $product_category = get_term($cat_id, 'product_cat'); ?>

                        <?php if ($product_category->parent) :
                            $parent_category = get_term($product_category->parent, 'product_cat');
                            $children = get_term_children($parent_category->term_id, 'product_cat');
                        ?>
                            <li data-cat="<?= $parent_category->term_id; ?>">
                                <span class="js-cat"><?= $parent_category->name; ?></span>
                                <span class="plus-icon js-open-submenu"></span>
                            </li>

                            <?php if (!empty($children)) : ?>
                                <ul class="product-cat__sidemenu-submenu js-submenu">
                                    <?php foreach ($children as $child) :
                                        if (in_array($child, $uniqueCatArr)) :
                                            $term = get_term_by('id', $child, 'product_cat');
                                    ?>
                                            <li data-cat="<?= $term->term_id; ?>"><span class="js-cat"><?= $term->name; ?></span></li>
                                    <?php endif;
                                    endforeach; ?>
                                </ul>
                            <?php
                            endif; ?>

                        <?php else :
                            $children = get_term_children($product_category->term_id, 'product_cat');
                        ?>
                            <li data-cat="<?= $product_category->term_id; ?>">
                                <span class="js-cat"><?= $product_category->name; ?></span>
                                <?php if (!empty($children)) : ?>
                                    <span class="plus-icon js-open-submenu"></span>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>


                    <?php endforeach ?>

                </ul>

            <?php endif; ?>

        </div>

        <section class="search-page__results">

            <div class="search-page__results-header js-search-header">
                <?php include('search-filters.php'); ?>
            </div>

            <div class="search-page__results-inside js-all-results">
                <?php include('search-results.php'); ?>
            </div>

        </section>

    </div>

</div>
<?php get_footer(); ?>