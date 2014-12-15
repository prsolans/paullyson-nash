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
        'category_name' => $category
    ));

    $heading = get_table_headings($posttype);
    $ratings = get_posttype_rating_types($posttype);

    if ($posts) {
        echo '<div class="rating-table overall-rating-table">
        <table id="overallScores-' . $category . '" class="tablesorter">
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
            echo '<td class="center collapsible">' . get_the_date('F d, Y', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table><label>* - complete ratings to come</label></div>';
    } else {
        echo $posttype;
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
