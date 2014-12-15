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
            ?>

            <?php
            display_category_ratings_table('service', '');
            ?>
            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->
    <script>
        jQuery(document).ready(function () {
                jQuery("#overallScores-").tablesorter({sortList: [[1, 1]]});
            }
        );
    </script>
<?php get_footer(); ?>