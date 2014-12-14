<?php
/**
 * Plugin Name: AKC/PRS - Restaurants
 * Description: Plugin for creating custom post type for rating restaurants.
 * Version: 0.0.1
 * Author: prsolans
 * License: Yes, please
 */

/**
 * Functions specific to Restaurant custom post type
 * Created by PhpStorm.
 * User: prsolans
 * Date: 12/13/14
 * Time: 4:35 PM
 */


add_action('init', 'create_restaurant_post_type');
/**
 * Create Restaurant Custom Post Type
 */
function create_restaurant_post_type()
{
    register_post_type('restaurant',
        array(
            'labels' => array(
                'name' => __('Restaurants'),
                'singular_name' => __('Restaurant')
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-location',
            'taxonomies' => array('category', 'post_tag'),
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail')
        )
    );
}

/**
 * Display table of restaurant ratings for a specific author
 * @param array $posts - Collection of posts
 * @param string $username - Related to a specific author
 */
function display_restaurant_table($posts, $username)
{
    $usernameToLower = strtolower($username);

    if ($posts) {
        echo '<div class="rating-table">
                            <h1>' . $username . '</h1>
                            <table id="' . $usernameToLower . 'Scores">
                                <thead>
                                    <th>Restaurant</th><th class="center">Service</th><th class="center">Food</th><th class="center">Ambiance</th></tr>
                                </thead>
                                <tbody>';
        foreach ($posts as $post) {

            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td >';
            echo '<td class="center">' . get_field($usernameToLower . '_restaurant_service', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_restaurant_food', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_restaurant_ambiance', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table></div>';
    }
}

/**
 *  Display table of overall restaurant ratings
 */
function display_restaurants_overall()
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'restaurant'
    ));

    if ($posts) {
        echo '<div class="rating-table overall-rating-table">
        <table id="overallScores" class="tablesorter">
            <thead>
                <th>Restaurant</th>
                <th class="center">Overall</th>
                <th class="center">Food</th>
                <th class="center">Service</th>
                <th class="center">Ambiance</th>
                <th class="center">Date</th>
            </thead>
            <tbody>';
        foreach ($posts as $post) {
            $scores = get_all_ratings_for_a_restaurant($post->ID);
            $incomplete = '';
            if ($scores['incomplete'] == true) {
                $incomplete = '*';
            }
            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . $incomplete . '</a ></td >';
            echo '<td class="center">' . $scores['overallScore'] . '</td >';
            echo '<td class="center">' . $scores['foodScore'] . '</td >';
            echo '<td class="center">' . $scores['serviceScore'] . '</td >';
            echo '<td class="center">' . $scores['ambianceScore'] . '</td >';
            echo '<td class="center">' . get_the_date('F d, Y', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table><label>* - complete ratings to come</label></div>';
    }
}

/**
 * Create array of all ratings for a single restaurant
 * @param $postId
 * @return array
 */
function get_all_ratings_for_a_restaurant($postId)
{
    // Confirm whether both authors have submitted reviews
    // TODO: Create more thorough test to confirm if a user has submitted reviews, create a flag for all three or something
    $divideBy = 1;
    $scores['incomplete'] = true;
    if (get_field('allykc_restaurant_service', $postId) && get_field('prs_restaurant_service', $postId)) {
        $divideBy = 2;
        $scores['incomplete'] = false;
    }

    $serviceScore = (get_field('prs_restaurant_service', $postId) + get_field('allykc_restaurant_service', $postId)) / $divideBy;
    $foodScore = (get_field('prs_restaurant_food', $postId) + get_field('allykc_restaurant_food', $postId)) / $divideBy;
    $ambianceScore = (get_field('prs_restaurant_ambiance', $postId) + get_field('allykc_restaurant_ambiance', $postId)) / $divideBy;
    $totalScore = $serviceScore + $foodScore + $ambianceScore;

    if ($totalScore == 0) {
        $scores['serviceScore'] = '*';
        $scores['foodScore'] = '*';
        $scores['ambianceScore'] = '*';
        $scores['totalScore'] = '*';
        $scores['overallScore'] = '*';
    } else {
        $scores['serviceScore'] = $serviceScore;
        $scores['foodScore'] = $foodScore;
        $scores['ambianceScore'] = $ambianceScore;
        $scores['totalScore'] = $totalScore;
        $scores['overallScore'] = round($totalScore / 3, 1);
    }
    return $scores;
}