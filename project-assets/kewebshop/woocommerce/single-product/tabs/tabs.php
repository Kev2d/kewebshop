<section class="single-product__information">

	<div class="single-product__information-tabs js-all-tabs">

		<a href="#tab_1" class="single-product__information-tabs-tab js-tab js-active">
			<h4><?= __('Lisainfo', 'kewebshop'); ?></h4>
		</a>

		<a href="#tab_2" class="single-product__information-tabs-tab js-tab">
			<h4><?= __('Tagasiside', 'kewebshop'); ?></h4>
		</a>

	</div>

	<div class="single-product__information-content">

		<div class="single-product__information-content-item" id="tab_1">
			<?php
			wc_get_template('/single-product/tabs/additional-description.php');
			?>
		</div>

		<div class="single-product__information-content-item js-hidden" id="tab_2">
			<?php
			comments_template();
			?>
		</div>



	</div>

</section>