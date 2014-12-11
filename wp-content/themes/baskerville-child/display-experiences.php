<?php
/*
Template Name: Experiences (All)
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
                'post_type' => 'experience'
            ));

            if ($posts) {
                echo '<ul>';

                foreach ($posts as $post) {
                    echo '<li><a href="' . get_permalink($post->ID) . '">' . get_the_title($post->ID) . '</a></li>';
                }

                echo '</ul>';
            }

            ?>

        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>