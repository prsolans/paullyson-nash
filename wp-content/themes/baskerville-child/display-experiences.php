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


        <div class="content section-inner">

            <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"
                                      title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; endif; ?>

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