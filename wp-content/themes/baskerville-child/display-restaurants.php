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


        <!-- /page-title -->

        <div class="content section-inner">

            <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"
                                      title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>
            <?php

            $prs_posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => 'restaurant'
            ));

            if ($prs_posts) {
                echo '<div class="ratingTable">
                            <h1>PRS</h1>
                            <table>
                                <thead>
                                    <th>Restaurant</th><th class="center">Service</th><th class="center">Food</th><th class="center">Ambiance</th></tr>
                                </thead>
                                <tbody>';
                foreach ($prs_posts as $post) {


                    echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td >';
                    echo '<td class="center">' . get_field('prs_restaurant_service') . '</td >';
                    echo '<td class="center">' . get_field('prs_restaurant_food') . '</td >';
                    echo '<td class="center">' . get_field('prs_restaurant_ambiance') . '</td ></tr >';
                }

                echo '</tbody>
                            </table>
                            </div>';
            }

            $allykc_posts = get_posts(array(
                'numberposts' => -1,
                'post_type' => 'restaurant'
            ));

            if ($allykc_posts) {
                echo '<div class="ratingTable">
                            <h1 > Allykc</h1>
                            <table>
                                <thead>
                                    <th> Restaurant</th ><th class="center">Service</th ><th class="center">Food</th ><th class="center">Ambiance</th ></tr >
                                </thead>
                                <tbody>';
                foreach ($allykc_posts as $post) {

                    echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td>';
                    echo '<td class="center">' . get_field('allykc_restaurant_service') . '</td >';
                    echo '<td class="center">' . get_field('allykc_restaurant_food') . '</td >';
                    echo '<td class="center">' . get_field('allykc_restaurant_ambiance') . '</td ></tr >';
                }

                echo '</tbody >
                            </table >
                            </div > ';

            }


            ?>
            <div style="clear: both; height: 20px">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>