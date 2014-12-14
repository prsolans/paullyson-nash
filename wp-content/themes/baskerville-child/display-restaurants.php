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

        <div class="content section-inner" style="width: 85%">

            <?php
            display_page_title();
            display_page_block_copy();
            ?>

            <?php
            display_category_ratings_table('restaurant', 'Restaurants');
            display_category_ratings_table('restaurant', 'Bars');
            display_category_ratings_table('restaurant', 'Fast');
            display_category_ratings_table('experience', 'Sports');
            display_category_ratings_table('service', '');
            display_category_ratings_table('shop', '');

            ?>
            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->
    <script>
        jQuery(document).ready(function () {
                jQuery("#overallScores-Restaurants").tablesorter({sortList: [[1, 1]]});
                jQuery("#overallScores-Bars").tablesorter({sortList: [[1, 1]]});
            }
        );
    </script>
<?php get_footer(); ?>