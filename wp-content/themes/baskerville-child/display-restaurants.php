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


            <div class="two-thirds-left">
                <h2>Ratings</h2>
                <?php

            $catID = get_category_by_slug(get_the_title());

            if ($catID->parent == 0) {

                $args = array(
                    'parent' => $catID->term_id,
                    'taxonomy' => 'category'
                );

                $category = get_categories($args);

            foreach ($category AS $item) {
                display_category_ratings_table('restaurant', $item->cat_name);
                ?>
                <script>
                    jQuery(document).ready(function () {
                            jQuery("#overallScores-<?php echo str_replace(' ', '-', strtolower($item->cat_name)); ?>").tablesorter({sortList: [[1, 1]]});
                        }
                    );
                </script>
            <?php
            }
            } else {
            display_category_ratings_table('restaurant', get_the_title());
            ?>
                <script>
                    jQuery(document).ready(function () {
                            jQuery("#overallScores-<?php echo $catID->slug; ?>").tablesorter({sortList: [[1, 1]]});
                        }
                    );
                </script>
            <?php
            }

            ?>
            </div>

            <div class="one-third-right">
                <?php
                display_category_to_do_list('restaurant', get_the_title());
                ?>
            </div>

            <div class="cleardiv">&nbsp;</div>
        </div>
        <!-- /content -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>