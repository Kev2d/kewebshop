<?php
/*
Template Name: Search Results
*/
get_header(); ?>

<div class="content">

<?php if (get_search_query()) :
    $args = array(
        'post_type'             => 'product',
        'post_status'           => 'publish',
        'ignore_sticky_posts'   => 1,
        's' => get_search_query(),
    );
  
else:

    $args = array();

endif;

$products = new WP_Query($args);
?>

<?php if ( $products->have_posts() && get_search_query() ) : ?>

<h1>Search Results</h1>

<ul>

<?php while ( $products->have_posts()) : $products->the_post(); ?>

<li>

<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

</li>

<?php endwhile; ?>

</ul>

<?php else : ?>

<p>No search results found.</p>

<?php endif; ?>


</div>
<?php get_footer(); ?>