<?php

get_header();

$format = get_post_format();

?>

    <style type="text/css">

        .acf-map {
            width: 100%;
            height: 400px;
            border: #ccc solid 1px;
            margin: 20px 0;
        }

    </style>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script type="text/javascript">
        (function($) {

            /*
             *  render_map
             *
             *  This function will render a Google Map onto the selected jQuery element
             *
             *  @type	function
             *  @date	8/11/2013
             *  @since	4.3.0
             *
             *  @param	$el (jQuery element)
             *  @return	n/a
             */

            function render_map( $el ) {

                // var
                var $markers = $el.find('.marker');

                // vars
                var args = {
                    zoom		: 16,
                    center		: new google.maps.LatLng(0, 0),
                    mapTypeId	: google.maps.MapTypeId.ROADMAP
                };

                // create map
                var map = new google.maps.Map( $el[0], args);

                // add a markers reference
                map.markers = [];

                console.log($markers);

                // add markers
                $markers.each(function(){

                    add_marker( $(this), map );

                });

                // center map
                center_map( map );

            }

            /*
             *  add_marker
             *
             *  This function will add a marker to the selected Google Map
             *
             *  @type	function
             *  @date	8/11/2013
             *  @since	4.3.0
             *
             *  @param	$marker (jQuery element)
             *  @param	map (Google Map object)
             *  @return	n/a
             */

            function add_marker( $marker, map ) {

                // var
                var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

                // create marker
                var marker = new google.maps.Marker({
                    position	: latlng,
                    map			: map,
                    saddr: 'Current+Location'

                    });

                // add to array
                map.markers.push( marker );

                // if marker contains HTML, add it to an infoWindow
                if( $marker.html() )
                {
                    // create info window
                    var infowindow = new google.maps.InfoWindow({
                        content		: $marker.html()
                    });

                    // show info window when marker is clicked
                    google.maps.event.addListener(marker, 'click', function() {
                        console.log('clicked');
                        infowindow.open( map, marker );

                    });
                }

            }

            /*
             *  center_map
             *
             *  This function will center the map, showing all markers attached to this map
             *
             *  @type	function
             *  @date	8/11/2013
             *  @since	4.3.0
             *
             *  @param	map (Google Map object)
             *  @return	n/a
             */

            function center_map( map ) {

                // vars
                var bounds = new google.maps.LatLngBounds();

                // loop through all markers and create bounds
                $.each( map.markers, function( i, marker ){

                    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

                    bounds.extend( latlng );

                });

                // only 1 marker?
                if( map.markers.length == 1 )
                {
                    // set center of map
                    map.setCenter( bounds.getCenter() );
                    map.setZoom( 16 );
                }
                else
                {
                    // fit to bounds
                    map.fitBounds( bounds );
                }

            }

            /*
             *  document ready
             *
             *  This function will render each map when the document is ready (page has loaded)
             *
             *  @type	function
             *  @date	8/11/2013
             *  @since	5.0.0
             *
             *  @param	n/a
             *  @return	n/a
             */

            $(document).ready(function(){

                $('.acf-map').each(function(){

                    render_map( $(this) );

                });

            });

        })(jQuery);
    </script>

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

            echo "Overall Score: ".$scores['overallScore'];
            ?>
            <?php

            $location = get_field('location');
            if( !empty($location) ):
                ?>
                <div class="acf-map">
                    <h4><?php the_field('status'); ?></h4>
                    <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>">Hello everybody</div>
                </div>
            <?php endif; ?>

</h3>

    </div>
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

    <div class="post-content">

        <?php
        if($scores['incomplete'] == false) {
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

        <?php the_content(); ?>

        <div class="clear"></div>

        <div class="location-info-block">
            <div class="one-half"><h3><?php echo get_the_title(); ?></h3></div>
            <div class="one-half">Map</div>
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

                        <a class="author-link-website" title="<?php _e('Author website', 'baskerville'); ?>"
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