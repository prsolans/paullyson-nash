<?php
/**
 * Plugin Name: AKC/PRS - Custom Ratings Posts
 * Description: Plugin for creating custom post type for rating experiences.
 * Version: 0.1
 * Author: prsolans
 * License: Yes, please
 *
 * Date: 12/14/14
 * Time: 9:40 PM
 */

require_once('admin/options.php');

require_once('post-types/Experiences.php');
require_once('post-types/Restaurants.php');
require_once('post-types/Services.php');
require_once('post-types/Shops.php');

/**
 * Collect posts and send to appropriate display function
 *
 * @param string $posttype - Post type for the ratings
 * @param string $username - Author name whose table will be displayes
 */
function display_user_ratings_table($posttype, $username)
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => $posttype
    ));

    if ($posttype == 'restaurant') {
        display_restaurant_table($posts, $username);
    } elseif ($posttype == 'experience') {
        display_experience_table($posts, $username);
    } elseif ($posttype == 'shop') {
        display_shop_table($posts, $username);
    } elseif ($posttype == 'service') {
        display_service_table($posts, $username);
    }

}

function display_category_to_do_list($posttype, $category)
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => $posttype,
        'category_name' => $category,
        'meta_query' => array(
            array(
                'key' => 'status',
                'value' => 'Upcoming'
            )
        )
    ));

    echo "<div class='rating-sidebar-block shadow-box-border'><h2>Upcoming</h2>";

    if ($posts) {

        echo "<ul>";
        foreach ($posts AS $item) {
            $upcomingDate = get_upcoming_post_date($item->ID);
            echo "<li>" . $upcomingDate . " - <a href='" . get_permalink($item->ID) . "'>" . get_the_title($item->ID) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No " . $category . " Upcoming";
    }
    echo "</div>";

    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => $posttype,
        'category_name' => $category,
        'meta_query' => array(
            array(
                'key' => 'status',
                'value' => 'On the Radar'
            )
        )
    ));

    echo "<div class='rating-sidebar-block shadow-box-border'><h2>On the Radar</h2>";


    if ($posts) {
        echo "<ul>";
        foreach ($posts AS $item) {
            echo "<li><a href='" . get_permalink($item->ID) . "'>" . get_the_title($item->ID) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "No " . $category . " On the Radar";
    }

    echo "</div>";

}

function get_upcoming_post_date($postID)
{

    $post = get_post_meta($postID);

    if ($post) {

        $upcomingDate = new DateTime($post['upcoming_date'][0]);

        return $upcomingDate->format('m/d');
    }
}

/**
 * Get headings for table presentation of posttype ratings
 * @param $posttype
 * @return array
 */
function get_table_headings($posttype)
{

    $headings = array();

    if ($posttype == 'restaurant') {
        $headings = array('Food', 'Service', 'Ambiance');
    } elseif ($posttype == 'experience') {
        $headings = array('Venue', 'Fun', 'Intangibles');
    } elseif ($posttype == 'service') {
        $headings = array('Ease', 'Quality', 'People');
    } elseif ($posttype == 'shop') {
        $headings = array('Ease', 'Quality', 'Ambiance');
    }

    return $headings;
}

/**
 * Get rating names for postype
 * @param $posttype
 * @return array
 */
function get_posttype_rating_types($posttype)
{
    $ratings = array();

    if ($posttype == 'restaurant') {
        $ratings = array('foodScore', 'serviceScore', 'ambianceScore');
    } elseif ($posttype == 'experience') {
        $ratings = array('venueScore', 'funScore', 'intangiblesScore');
    } elseif ($posttype == 'service') {
        $ratings = array('easeScore', 'qualityScore', 'peopleScore');
    } elseif ($posttype == 'shop') {
        $ratings = array('easeScore', 'qualityScore', 'ambianceScore');
    }

    return $ratings;
}

/**
 * Display table of overall ratings
 * @param string $posttype - Name of custom posttype being requested
 * @param string $category - Name of category name being requested
 */
function display_category_ratings_table($posttype, $category)
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => $posttype,
        'category_name' => $category,
        'meta_query' => array(
            array(
                'key' => 'status',
                'value' => 'Been There, Done That',
            )
        )
    ));

    $cleanCategory = str_replace(' ', '-', strtolower($category));

    $heading = get_table_headings($posttype);
    $ratings = get_posttype_rating_types($posttype);

    if ($posts) {
        echo '<div class="rating-table overall-rating-table">
        <table id="overallScores-' . $cleanCategory . '" class="tablesorter">
            <thead>
                <th>' . $category . '</th>
                <th class="center">Overall</th>
                <th class="center collapsible">' . $heading[0] . '</th>
                <th class="center collapsible">' . $heading[1] . '</th>
                <th class="center collapsible">' . $heading[2] . '</th>
                <th class="center collapsible">Date</th>
            </thead>
            <tbody>';
        foreach ($posts as $post) {
            $scores = get_all_ratings($heading, $ratings, $posttype, $post->ID);

            $incomplete = '';
            if ($scores['incomplete'] == true) {
                $incomplete = '*';
            }

            echo '<tr ><td class="name-cell"><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . $incomplete . '</a ></td >';
            echo '<td class="center">' . $scores['overallScore'] . '</td >';
            echo '<td class="center collapsible">' . $scores[$ratings[0]] . '</td >';
            echo '<td class="center collapsible">' . $scores[$ratings[1]] . '</td >';
            echo '<td class="center collapsible">' . $scores[$ratings[2]] . '</td >';
            echo '<td class="center collapsible">' . get_the_date('m/d/y', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table><label>* - complete ratings to come</label></div>';
    } else {
//        echo $posttype;
    }
}

/**
 * Create array of all ratings for a single item
 * @param $postId
 * @return array
 */
function get_all_ratings($heading, $ratings, $posttype, $postId)
{
    $authors = array('prs', 'allykc');

    $fieldnames = array();

    foreach ($authors as $author) {
        array_push($fieldnames, $author . '_' . $posttype . '_' . strtolower($heading[0]));
        array_push($fieldnames, $author . '_' . $posttype . '_' . strtolower($heading[1]));
        array_push($fieldnames, $author . '_' . $posttype . '_' . strtolower($heading[2]));
    }

    $ratingsSubmitted = 0;

    $scores = array();

    foreach ($fieldnames as $rating) {
        if (get_field($rating, $postId)) {
            array_push($scores, get_field($rating, $postId));
            $ratingsSubmitted++;
        } else {
            array_push($scores, '0');
        }
    }

    //calculate combined scores
    $score1 = $scores[0] + $scores[3];
    $score2 = $scores[1] + $scores[4];
    $score3 = $scores[2] + $scores[5];
    $totalScore = $score1 + $score2 + $score3;

    if ($ratingsSubmitted == 6) {
        $calculatedScores[$ratings[0]] = $score1 / 2;
        $calculatedScores[$ratings[1]] = $score2 / 2;
        $calculatedScores[$ratings[2]] = $score3 / 2;
        $calculatedScores['totalScore'] = $totalScore / 2;
        $calculatedScores['overallScore'] = round($totalScore / 6, 1);
        $calculatedScores['incomplete'] = false;
    } elseif ($ratingsSubmitted == 3) {
        $calculatedScores[$ratings[0]] = $score1;
        $calculatedScores[$ratings[1]] = $score2;
        $calculatedScores[$ratings[2]] = $score3;
        $calculatedScores['totalScore'] = $totalScore;
        $calculatedScores['overallScore'] = round($totalScore / 3, 1);
        $calculatedScores['incomplete'] = true;
    } else {
        $calculatedScores[$ratings[0]] = '*';
        $calculatedScores[$ratings[1]] = '*';
        $calculatedScores[$ratings[2]] = '*';
        $calculatedScores['totalScore'] = '*';
        $calculatedScores['overallScore'] = '*';
        $calculatedScores['incomplete'] = true;
    }
    return $calculatedScores;
}

function display_ratings_listings($posttype)
{

    echo "<div class='two-thirds-left'><h2>Ratings</h2>";

    $catID = get_category_by_slug(get_the_title());

    if ($catID->parent == 0) {

        $args = array(
            'parent' => $catID->term_id,
            'taxonomy' => 'category'
        );

        $category = get_categories($args);

        if ($category) {

            foreach ($category AS $item) {
                display_category_ratings_table($posttype, $item->cat_name);
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
            display_category_ratings_table($posttype, get_the_title());
            ?>
            <script>
                jQuery(document).ready(function () {
                        jQuery("#overallScores-<?php echo $catID->slug; ?>").tablesorter({sortList: [[1, 1]]});
                    }
                );
            </script>
        <?php
        }
    } else {

        display_category_ratings_table($posttype, get_the_title());
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
<?php
}

function display_rating_sidebar($posttype)
{

    echo '<div class="one-third-right">';

    display_category_to_do_list($posttype, get_the_title());

    echo '</div>';
}

function display_recent_ratings($lastMonth = false)
{
    $monthToDisplay = date('F');
    $offset = 0;
    if($lastMonth == true){ $offset = 1; $monthToDisplay = date('F', strtotime('-1 months')); }

    echo "<h2>Best of " . $monthToDisplay . "</h2>";

    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => array('restaurant', 'experience', 'service', 'shop'),
        'meta_query' => array(
            array(
                'key' => 'status',
                'value' => 'Been There, Done That',
            )
        ),
        'orderby' => 'post_date',
        'order' => 'ASC',
        'date_query' => array(
            array(
                'year' => date('Y'),
                'month' => date('m') - $offset,
            ),
        ),

    ));
    echo "<ul>";
    if ($posts) {
        foreach ($posts AS $item) {
            echo "<li><a href='".get_permalink($item->ID)."'> " . $item->post_title . "</a></li>";
        }
    }
    echo "</ul>";
}

function display_upcoming_events()
{

    echo "<h2>Upcoming Fun Stuff</h2>";

    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => array('restaurant', 'experience', 'service', 'shop'),
//        'category_name' => $category,
        'meta_query' => array(
            array(
                'key' => 'status',
                'value' => 'Upcoming',
            )
        ),
        'orderby' => 'post_date',
        'order' => 'ASC'
    ));

    echo "<ul>";
    if ($posts) {
        foreach ($posts AS $item) {
            echo "<li><a href='".get_permalink($item->ID)."'> " . $item->post_title . "</a></li>";
        }
    }
    echo "</ul>";
}