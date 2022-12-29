<?php $objectId = isset($_POST['productCat']) ? (int)$_POST['productCat'] : $uniqueCatArr;
if (get_search_query()) :
	$searchQuery = get_search_query();
else :
	$searchQuery = $_POST['searchQuery'];
endif;
?>

<h3 class="js-category-title"><?= __('Sinu otsingusõna:' . $searchQuery . ' ', 'kewebshop'); ?></h3>

<div class="product-cat__products-filters">

	<?php


	$catargs = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts' => 1,
		's' => $searchQuery,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $objectId
			)
		),
	);
	$data = array();
	$prices = array();
	foreach (wc_get_products($catargs) as $product) {
		foreach ($product->get_attributes() as $taxonomy => $attribute) {
			$attribute_name = wc_attribute_label($taxonomy); // Attribute name
			foreach ($attribute->get_terms() as $term) {
				$data[$taxonomy][$term->term_id] = $term->name;
			}
		}
		array_push($prices, $product->get_price());
	}
	?>

	<div class="filter-item">
		<div class="filter-item__label js-filter-label"><?= __('Sort by price', 'kewebshop'); ?></div>
		<div class="filter-item__options js-filter-options">
			<div class="filter-item__options-priceValues">
				<input type="text" class="sliderValue js-slider-min" data-index="0" value="<?= min($prices); ?>" />
				<span>-</span>
				<input type="text" class="sliderValue js-slider-max" data-index="1" value="<?= max($prices); ?>" />
			</div>
			<br />
			<div id="js-price-slider"></div>
		</div>

	</div>

	<?php
	foreach ($data as $key => $value) :
		$i = 1;
		$taxonomy = get_taxonomy($key);
		$slug = $taxonomy->name;
	?>
		<div class="filter-item">
			<div class="filter-item__label js-filter-label"><?= wc_attribute_label($key); ?></div>

			<ul class="filter-item__options js-filter-options" data-attr="<?= $slug; ?>">
				<?php foreach ($value as $scnd_key => $scnd_value) : ?>
					<li>
						<label class="container"><?= $scnd_value; ?>
							<input type="checkbox" id="<?= $key . '_' . $i; ?>" name="<?= $key . '_' . $i; ?>" value="<?= $scnd_value; ?>">
							<span class="checkmark"></span>
						</label>
					</li>
				<?php $i++;
				endforeach; ?>

			</ul>
		</div>

	<?php
	endforeach;
	?>

	<div class="filter-item">
		<div class="filter-item__label js-filter-label">
			<label class="switch js-switch">
				<span><?= __('Popular?', 'kewebshop'); ?></span>
				<div class="switch__inside">
					<input type="checkbox" id="js-popular" name="popular">
					<span class="slider"></span>
				</div>
			</label>
		</div>
	</div>

	<div class="filter-item">
		<div class="filter-item__label js-filter-label">
			<label class="switch js-switch">
				<span><?= __('Allahindlus?', 'kewebshop'); ?></span>
				<div class="switch__inside">
					<input type="checkbox" id="js-discount" name="discount">
					<span class="slider"></span>
				</div>
			</label>
		</div>
	</div>

</div>