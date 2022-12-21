<?php
get_header();
?>
<div class="content">
	<div class="product-cat">

		<div class="product-cat__sidemenu">

			<?php
			$object = get_queried_object();
			$objectId = $object->term_id;
			$args = array(
				'taxonomy' => 'product_cat',
				'hide_empty' => true,
				'parent' => 0
			);
			$cats = get_categories($args);
			?>
			<ul class="product-cat__sidemenu-main">
				<?php
				foreach ($cats as $cat) :
					$children = get_term_children($cat->term_id, 'product_cat');
				?>
					<li data-cat="<?= $cat->term_id; ?>">
						<?php if (empty($children)) : ?>
							<span class="js-cat<?= $objectId === $cat->term_id ? ' highlight--text' : '' ?>"><?= $cat->name; ?></span>
						<?php else : ?>
							<span class="js-cat<?= $objectId === $cat->term_id ? ' highlight--text' : '' ?>"><?= $cat->name; ?></span>
							<span class="plus-icon js-open-submenu<?= in_array($objectId, $children) || $objectId === $cat->term_id ? ' js-open' : '' ?>"></span>
						<?php endif; ?>
					</li>


					<?php
					$sub_args = array(
						'taxonomy' => 'product_cat',
						'hide_empty' => true,
						'parent'   => $cat->term_id
					);
					$child_cats = get_categories($sub_args);
					?>
					<ul class="product-cat__sidemenu-submenu js-submenu<?= in_array($objectId, $children) || $objectId === $cat->term_id ? ' js-open' : '' ?>">
						<?php
						foreach ($child_cats as $child_cat) :
						?>
							<li data-cat="<?= $child_cat->term_id; ?>">
							<span class="js-cat<?= $objectId === $child_cat->term_id ? ' highlight--text' : '' ?>"><?= $child_cat->name; ?></span>
							</li>
						<?php
						endforeach;
						?>
					</ul>
				<?php
				endforeach;
				?>
			</ul>

		</div>

		<section class="product-cat__products">
			<div class="product-cat__products-header js-products-header">
				<?php include('templates/all-products-header.php'); ?>
			</div>

			<div class="product-cat__products-inside js-all-products">
				<?php include('templates/all-products.php'); ?>
			</div>

		</section>

	</div>
</div>
<?php

get_footer();
