<?php get_header(); ?>

<div class="content">

	<div class="single-product">

		<?php woocommerce_breadcrumb() ?>

		<div class="single-product__inside">

			<div class="single-product__inside-left js-gallery-images">

				<?php
				global $product;
				$productId = get_the_ID();
				$postThumbnail = get_the_post_thumbnail($productId, 'single-thumb');
				$product = wc_get_product($productId);
				$image_ids = $product->get_gallery_image_ids();
				?>

				<div class="single-product__inside-thumbnail js-product-gallery">
					<?php if (!$postThumbnail) : ?>
						<img alt="Product thumbnail placeholder" src="<?= get_template_directory_uri(); ?>/assets/img/defaultimages/product_placeholder.jpg" width="400" height="400">
					<?php else : ?>
						<?php
						$i = 1;

						// Add a data attribute to the img tag
						$postThumbnail = str_replace(
							'<img',
							'<img data-imgid="' . $i . '"',
							$postThumbnail
						);
						?>
						<span style="display:inline-block">
							<?= $postThumbnail; ?>
						</span>
						<?php
						if (!empty($image_ids)) : ?>

							<?php
							foreach ($image_ids as $image_id) :
								$i++;
								$attachmentImg = wp_get_attachment_image($image_id, 'single-thumb');
								$attachmentImg = str_replace(
									'<img',
									'<img data-imgid="' . $i . '"',
									$attachmentImg
								);
							?>
								<span style="display:inline-block">
									<?= $attachmentImg; ?>
								</span>
							<?php endforeach; ?>

						<?php endif; ?>
					<?php endif; ?>
				</div>

				<?php

				$regularPrice = $product->get_regular_price();
				$salePrice = $product->get_sale_price();
				$currentPrice = $product->get_price();
				if ($salePrice) {
					$discountPrice = round((($regularPrice - $salePrice) / $regularPrice) * 100);
				}

				?>


				<?php if ($postThumbnail) : ?>

					<div class="single-product__inside-gallery js-thumbnails">
						<?php
						$i = 1;
						$postThumbnail = get_the_post_thumbnail($productId, 'product-thumb');
						// Add a data attribute to the img tag
						$postThumbnail = str_replace(
							'<img',
							'<img data-imgid="' . $i . '" class="js-active"',
							$postThumbnail
						);

						?>
						<div class="product-gallery-image">
							<?= $postThumbnail; ?>
						</div>
						<?php
						if (!empty($image_ids)) : ?>
							<?php
							foreach ($image_ids as $image_id) :
								$i++;
								$attachmentImg = wp_get_attachment_image($image_id, 'product-thumb');
								$attachmentImg = str_replace(
									'<img',
									'<img data-imgid="' . $i . '"',
									$attachmentImg
								);
							?>
								<div class="product-gallery-image">
									<?= $attachmentImg; ?>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>


			</div>

			<div class="single-product__inside-right js-single-content-parent">
				<div class="single-product__inside-right-content js-single-content">
					<h1><?= the_title(); ?></h1>

					<div class="single-product__inside-prices">

						<?php if ($salePrice) : ?>

							<span class="regular--price"><?= $regularPrice; ?>€</span>

						<?php endif; ?>

						<span class="current--price<?= $salePrice ? ' discount' : '' ?>"><?= $currentPrice; ?>€</span>

					</div>

					<?php if (get_field('popular_product', $productId)) : ?>

						<span class="popular--product"><?= __('Populaarne', 'kewebshop'); ?></span>

					<?php endif; ?>

					<?php if ($salePrice) : ?>

						<span class="discount--percentage">-<?= $discountPrice; ?>%</span>

					<?php endif; ?>

					<div class="single-product-content">

						<?= the_content(); ?>

					</div>


					<?php
					// Check if the product is in stock
					if ($product->is_in_stock()) {
						// Output the Add to Cart button with a quantity field
					?>
						<form class="make-order" method="post" enctype="multipart/form-data">
							<div class="make-order__quantity">
								<span class="js-min-qty"><svg>
										<use xlink:href="<?= get_template_directory_uri(); ?>/assets/img/icons/minus_icon.svg#minus" href="<?= get_template_directory_uri(); ?>/assets/img/icons/minus_icon.svg#minus"></use>
									</svg></span>
								<input type="number" step="1" min="1" max="" name="quantity" value="1" title="Quantity" class="js-single-qty" size="4" pattern="[0-9]*" inputmode="numeric">
								<span class="js-plus-qty"><svg>
										<use xlink:href="<?= get_template_directory_uri(); ?>/assets/img/icons/plus_icon.svg#plus" href="<?= get_template_directory_uri(); ?>/assets/img/icons/plus_icon.svg#plus"></use>
									</svg></span>
							</div>

							<input type="hidden" name="add-to-cart" value="<?= get_the_ID(); ?>">

							<button type="submit" class="make-order__button"> <?= __('Lisa ostukorvi', 'kewebshop'); ?> </button>
						</form>
					<?php
					} else {
						// Output a notice if the product is out of stock
						echo '<p class="stock out-of-stock">' . __('This product is currently out of stock.', 'woocommerce') . '</p>';
					}
					?>
				</div>
			</div>

		</div>

		<?php
		// Include the customized tabs template
		wc_get_template('/single-product/tabs/tabs.php');
		?>

		<?php
		// Include the customized tabs template
		wc_get_template('/templates/latest-products.php');
		?>

	</div>
</div>
<?php
get_footer(); ?>