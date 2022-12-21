<?php $productId = get_the_ID();
$product = wc_get_product($productId);
$attributes = $product->get_attributes();
$weight = get_post_meta($productId, '_weight', true);
$dimensions = $product->get_dimensions();
$length = $product->get_length();
$width = $product->get_width();
$height = $product->get_height();
$postThumbnail = get_the_post_thumbnail($productId, 'single-thumb');
$theContent = apply_filters('the_content', get_the_content());
$dimensionUnit = get_option('woocommerce_dimension_unit');
$weightUnit = get_option('woocommerce_weight_unit');;
?>

<div class="additional-information">

  <div class="additional-information-left">
    <?php if (!empty($dimensions) || !empty($weight)) : ?>
      <div class="product-info">

        <h4><?= __('Saadetise info', 'kewebshop'); ?></h4>

        <ul>
          <?php if (!empty($weight)) : ?>
            <li><strong>Kaal:</strong> <?= $weight . $weightUnit; ?></li>
          <?php endif; ?>
          <?php if (!empty($length)) : ?>
            <li><strong>Pikkus:</strong> <?= $length . $dimensionUnit; ?></li>
          <?php endif; ?>
          <?php if (!empty($width)) : ?>
            <li><strong>Laius:</strong> <?= $width . $dimensionUnit; ?></li>
          <?php endif; ?>
          <?php if (!empty($height)) : ?>
            <li><strong>Kõrgus:</strong> <?= $height . $dimensionUnit; ?></li>
          <?php endif; ?>
            <li><strong>Tootekood:</strong> <?= $productId; ?></li>
        </ul>
      </div>
    <?php endif; ?>

    <?php
    if (!empty($attributes)) :
    ?>
      <div class="product-info">
        <h4><?= __('Muud omadused', 'kewebshop'); ?></h4>
        <ul>

          <?php
          foreach ($attributes as $attribute) :
            $name = wc_attribute_label($attribute['name']);
            $values = $product->get_attribute($attribute['name']);
          ?>
            <li><strong><?= $name . ': ' ?></strong>
              <?= $values; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php
    endif;
    ?>


<?php
    if (!empty($theContent)) :
    ?>
    <div class="product-info">
      <h4><?= __('Toote kirjeldus', 'kewebshop'); ?></h4>
      <?= $theContent; ?>

    </div>
    <?php
    endif;
    ?>

  </div>

  <div class="additional-information-right">

    <?php if ($postThumbnail) : ?>
      <?= $postThumbnail; ?>
    <?php endif; ?>
  </div>



</div>