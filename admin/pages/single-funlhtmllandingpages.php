<?php
/*
Template Name: Statichtmlpages Page Display
Template Post Type: funlhtmllandingpages
*/

while ( have_posts() ) : the_post();
    $meta = get_post_meta(get_the_ID(), 'funlhtmllandingpages_meta_box', true);
    echo esc_html( stripslashes($meta['html_code']) );
endwhile; // End of the loop.