<form class="shop-header__search js-search-bar"  role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<input class="js-search-input" type="text" value="<?= get_search_query(); ?>" name="s" id="s"  placeholder="<?= __('Otsi tooteid', 'kewebshop'); ?>">
<button class="js-search-products">
  <svg>
    <use xlink:href="<?= get_template_directory_uri(); ?>/assets/img/icons/search_icon.svg#search-icon" href="<?= get_template_directory_uri(); ?>/assets/img/icons/search_icon.svg#search-icon"></use>
  </svg>
</button>

</form>