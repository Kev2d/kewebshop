<!doctype html>
<html>

<head>
<script src="https://cdn.jsdelivr.net/npm/js-base64@3.7.3/base64.min.js"></script>
  <meta name="keywords" content="">
  <meta name="description" content="">

  <meta charset="utf-8">
  <title>Nupuke.ee | Küll sa välja nuputad</title>
  <?php wp_head(); ?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<div class="shop-header">

  <?php

  if (has_custom_logo()) :
    the_custom_logo();
  else : ?>
    <h1>
      <a href="<?= get_home_url(); ?>"><?= get_bloginfo('name'); ?></a>
    </h1>

  <?php
  endif;
  ?>

</div>

<nav class="navbar">

  <div class="navbar__hamburger">
    <div class="js-hamburger">
      <span></span><span></span><span></span>
    </div>
  </div>

  <div class="navbar__inside js-primary-menu">

    <?php
    if (has_nav_menu('primary')) {
      wp_nav_menu(array(
        'theme_location' => 'primary', // Defined when registering the menu
        'container'      => 'main-navigation',
        'depth'          => 3,
        'menu_class'     => 'primary-menu',
        'walker' => new kewebShop_menu()
      ));
    }
    ?>

  </div>

</nav>

<body>
