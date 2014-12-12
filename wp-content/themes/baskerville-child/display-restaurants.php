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

        <div class="content section-inner" style="width: 85%">

            <?php
            display_page_title();
            display_page_block_copy();
            ?>


            <?php

            display_ratings_table('restaurant', 'PRS');
            display_ratings_table('restaurant', 'Allykc');

            ?>
            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>