<?php get_header(); ?>

    <div class="wrapper section medium-padding">

        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $total_post_count = wp_count_posts();
        $published_post_count = $total_post_count->publish;
        $total_pages = ceil($published_post_count / $posts_per_page);

        if ("1" < $paged) : ?>

            <div class="page-title section-inner">

                <h5><?php printf(__('Page %s of %s', 'baskerville'), $paged, $wp_query->max_num_pages); ?></h5>

            </div>

            <div class="clear"></div>

        <?php endif; ?>

        <div class="content section-inner">

            <div class="one-half shadow-box-border">
                <?php display_recent_ratings();
                display_recent_ratings('true'); ?>
            </div>
            <div class="one-half shadow-box-border">
                <?php display_upcoming_events(); ?>
            </div>

        </div>
        <!-- /content -->


        <div class="clear"></div>

    </div> <!-- /wrapper -->

<?php get_footer(); ?>