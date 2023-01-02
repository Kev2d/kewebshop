<form class="shop-header__search js-search-bar" role="search" method="get" id="search-form" action="<?php echo esc_url(home_url('/')); ?>">
  <input class="js-search-input" type="text" value="<?= get_search_query(); ?>" name="s" id="s" placeholder="<?= __('Otsi tooteid', 'kewebshop'); ?>">
  <button class="js-search-products">
    <svg class="js-search-icon">
      <use xlink:href="<?= get_template_directory_uri(); ?>/assets/img/icons/search_icon.svg#search-icon" href="<?= get_template_directory_uri(); ?>/assets/img/icons/search_icon.svg#search-icon"></use>
    </svg>

    <span class="js-search-loader">
      <object data="<?= get_template_directory_uri(); ?>/assets/img/icons/loader.svg#loader" type="image/svg+xml" id="animation"></object>
    </span>
  </button>

  <span class="js-close-icon">
    <object data="<?= get_template_directory_uri(); ?>/assets/img/icons/close_icon.svg#close" type="image/svg+xml" id="animation"></object>
  </span>

</form>