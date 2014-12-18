<?php
/*
Template Name: Services (All)
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
            display_ratings_listings('service');
            display_rating_sidebar('service');

            ?>

            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->
    <script>
        jQuery(document).ready(function () {
                jQuery("#overallScores-<?php echo strtolower(get_the_title()); ?>").tablesorter({sortList: [[1, 1]]});
            }
        );
    </script>
<?php get_footer(); ?>