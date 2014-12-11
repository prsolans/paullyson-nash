<?php
/*
Template Name: Restaurants (All)
*/

/**
 * Created by PhpStorm.
 * User: prsolans
 * Date: 12/11/14
 * Time: 4:48 PM
 */
?>

<?php get_header(); ?>

    <div class="wrapper section medium-padding">

        <div class="page-title section-inner">

        </div>
        <!-- /page-title -->

        <div class="content section-inner">

            <?php


            $posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => 'restaurant'
            ));

            if ($posts) {

                foreach ($posts as $post) {
                    echo '<a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a>';
                    echo '<div class="rating-block">Category: PRS/Allykc<br/>';
                    echo '<br/>Service:'. get_field('prs_restaurant_service') . '/'.get_field('allykc_restaurant_service');
                    echo '<br/>Food:'. get_field('prs_restaurant_food') . '/'.get_field('allykc_restaurant_food');
                    echo '<br/>Ambiance: '. get_field('prs_restaurant_ambiance') . '/' .get_field('allykc_restaurant_ambiance');
                    echo '</div>';
                }

            }

            ?>

        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>