<?php

get_header();

$format = get_post_format();

?>

    <div class="wrapper section medium-padding">

        <div class="section-inner">

            <div class="content fleft">

                <?php if (have_posts()) : while (have_posts()) :
                the_post(); ?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="post-header">

                        <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"
                                                  title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

                        <h3>

                            <br/><?php $scores = get_all_ratings_for_a_restaurant(get_the_ID());

                            echo "Overall Score: " . $scores['overallScore'];
                            ?>
                            <hr/>


                        </h3>


                        <!-- /post-header -->



                        <?php
                        if (has_post_thumbnail()) : ?>

                            <div class="featured-media">

                                <?php the_post_thumbnail('post-image'); ?>

                                <?php if (!empty(get_post(get_post_thumbnail_id())->post_excerpt)) : ?>

                                    <div class="media-caption-container">

                                        <p class="media-caption"><?php echo get_post(get_post_thumbnail_id())->post_excerpt; ?></p>

                                    </div>

                                <?php endif; ?>

                            </div> <!-- /featured-media -->

                        <?php endif; ?>

                        <?php
                        if ($scores['incomplete'] == false) {
                            echo '<div class="rating-block"><h3>PRS says</h3>';
                            echo '<label>Service:</label> ' . get_field('prs_restaurant_service');
                            echo '<br/><label>Food:</label> ' . get_field('prs_restaurant_food');
                            echo '<br/><label>Ambiance:</label> ' . get_field('prs_restaurant_ambiance');
                            echo '</div>';
                            echo '<div class="rating-block"><h3>Allykc says</h3>';
                            echo '<label>Service:</label> ' . get_field('allykc_restaurant_service');
                            echo '<br/><label>Food:</label> ' . get_field('allykc_restaurant_food');
                            echo '<br/><label>Ambiance:</label> ' . get_field('allykc_restaurant_ambiance');
                            echo '</div>';
                        }
                        ?>
                        <div class="post-content">


                        </div>

                        <div class="clear"></div>

                        <?php the_content(); ?>


                        <div class="location-info-block">

                            <?php

                            $location = get_field('location');
                            if (!empty($location)):
                                ?>
                                <h4><?php echo get_location_address($location); ?></h4>
                                <div class="acf-map">
                                    <div class="marker" data-lat="<?php echo $location['lat']; ?>"
                                         data-lng="<?php echo $location['lng']; ?>">Hello everybody
                                    </div>
                                </div>
                            <?php endif; ?>


                        </div>

                        <?php wp_link_pages(); ?>

                        <div class="clear"></div>

                    </div>
                    <!-- /post-content -->

                    <div class="post-meta-container">

                        <div class="post-author">

                            <div class="post-author-content">

                                <h4><?php the_author_meta('display_name'); ?></h4>

                                <p><?php the_author_meta('description'); ?></p>

                                <?php
                                if (isset($_GET['author_name'])) :
                                    $curauth = get_userdatabylogin($author_name);
                                else :
                                    $curauth = get_userdata(intval($author));
                                endif;
                                ?>

                                <div class="author-links">

                                    <a class="author-link-posts" title="<?php _e('Author archive', 'baskerville'); ?>"
                                       href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php _e('Author archive', 'baskerville'); ?></a>

                                    <?php $author_url = get_the_author_meta('user_url');

                                    $author_url = preg_replace('#^https?://#', '', rtrim($author_url, '/'));

                                    if (!empty($author_url)) : ?>

                                        <a class="author-link-website"
                                           title="<?php _e('Author website', 'baskerville'); ?>"
                                           href="<?php the_author_meta('user_url'); ?>"><?php _e('Author website', 'baskerville'); ?></a>

                                    <?php endif;

                                    $author_mail = get_the_author_meta('email');

                                    $show_mail = get_the_author_meta('showemail');

                                    if (!empty($author_mail) && ($show_mail == "yes")) : ?>

                                        <a class="author-link-mail" title="<?php echo $author_mail; ?>"
                                           href="mailto:<?php echo $author_mail ?>"><?php echo $author_mail; ?></a>

                                    <?php endif;

                                    $author_twitter = get_the_author_meta('twitter');

                                    if (!empty($author_twitter)) : ?>

                                        <a class="author-link-twitter"
                                           title="<?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter', 'baskerville'); ?>"
                                           href="http://www.twitter.com/<?php echo $author_twitter ?>"><?php echo '@' . $author_twitter . ' '; ?><?php _e('on Twitter', 'baskerville'); ?></a>

                                    <?php endif; ?>

                                </div>
                                <!-- /author-links -->

                            </div>
                            <!-- /post-author-content -->

                        </div>
                        <!-- /post-author -->

                        <div class="post-meta">

                            <p class="post-date"><?php the_time(get_option('date_format')); ?></p>

                            <?php if (function_exists('zilla_likes')) zilla_likes(); ?>

                            <p class="post-categories"><?php the_category(', '); ?></p>

                            <?php if (has_tag()) : ?>

                                <p class="post-tags"><?php the_tags('', ', '); ?></p>

                            <?php endif; ?>

                            <div class="clear"></div>

                            <div class="post-nav">

                                <?php
                                $prev_post = get_previous_post();
                                if (!empty($prev_post)): ?>

                                    <a class="post-nav-prev" title="<?php _e('Previous post:', 'baskerville');
                                    echo ' ' . esc_attr(get_the_title($prev_post)); ?>"
                                       href="<?php echo get_permalink($prev_post->ID); ?>"><?php _e('Previous post', 'baskerville'); ?></a>

                                <?php endif; ?>

                                <?php
                                $next_post = get_next_post();
                                if (!empty($next_post)): ?>

                                    <a class="post-nav-next" title="<?php _e('Next post:', 'baskerville');
                                    echo ' ' . esc_attr(get_the_title($next_post)); ?>"
                                       href="<?php echo get_permalink($next_post->ID); ?>"><?php _e('Next post', 'baskerville'); ?></a>

                                <?php endif; ?>

                                <?php edit_post_link(__('Edit post', 'baskerville')); ?>

                                <div class="clear"></div>

                            </div>

                        </div>
                        <!-- /post-meta -->

                        <div class="clear"></div>

                    </div>
                    <!-- /post-meta-container -->

                    <?php comments_template('', true); ?>

                    <?php endwhile; else: ?>

                        <p><?php _e("We couldn't find any posts that matched your query. Please try again.", "baskerville"); ?></p>

                    <?php
                    endif; ?>

                </div>
                <!-- /post -->

            </div>
            <!-- /content -->

            <?php get_sidebar(); ?>

            <div class="clear"></div>

        </div>
        <!-- /section-inner -->

    </div> <!-- /wrapper -->

<?php get_footer(); ?>