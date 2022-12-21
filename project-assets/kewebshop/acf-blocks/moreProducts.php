<section class="more-products">

    <?php if (get_field('title')) : ?>

        <h2><?= get_field('title'); ?></h2>

    <?php endif; ?>


    <div class="more-products__inside js-more-products">

    <?php require('templates/more-products.php'); ?>

    </div>

    <span class="js-loader">
        
    </span>

</section>