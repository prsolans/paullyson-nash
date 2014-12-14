<?php
/**
 * Plugin Name: AKC/PRS - Services
 * Description: Plugin for creating custom post type for rating services.
 * Version: 0.0.1
 * Author: prsolans
 * License: Yes, please
 */
/**
 * Functions specific to Service custom post type
 * Created by PhpStorm.
 * User: prsolans
 * Date: 12/13/14
 * Time: 4:41 PM
 */

add_action('init', 'create_service_post_type');
/**
 * Create Service Custom Post Type
 */
function create_service_post_type()
{
    register_post_type('service',
        array(
            'labels' => array(
                'name' => __('Services'),
                'singular_name' => __('Service')
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-art',
            'taxonomies' => array('category', 'post_tag'),
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail')
        )
    );
}

/**
 * Display table of service ratings for a specific author
 * @param array $posts - Collection of posts
 * @param string $username - Related to a specific author
 */
function display_service_table($posts, $username)
{
    $usernameToLower = strtolower($username);

    if ($posts) {
        echo '<div class="ratingTable">
            <h1>' . $username . '</h1>
            <table>
                <thead>
                    <th>Service</th><th class="center">Ease</th><th class="center">Quality</th><th class="center">People</th></tr>
                </thead>
                <tbody>';
        foreach ($posts as $post) {
            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td >';
            echo '<td class="center">' . get_field($usernameToLower . '_service_ease', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_service_quality', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_service_people', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table></div>';
    }
}


/**
 *  Display table of overall services ratings
 */
function display_services_overall()
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => 'service'
    ));

    if ($posts) {
        echo '<div class="ratingTable overallRatingTable">
        <table id="overallScores" class="tablesorter">
            <thead>
                <th>Restaurant</th>
                <th class="center">Overall</th>
                <th class="center">Ease</th>
                <th class="center">Quality</th>
                <th class="center">People</th>
                <th class="center">Date</th>
            </thead>
            <tbody>';
        foreach ($posts as $post) {
            $scores = get_all_ratings_for_a_service($post->ID);
            $incomplete = '';
            if ($scores['incomplete'] == true) {
                $incomplete = '*';
            }
            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . $incomplete . '</a ></td >';
            echo '<td class="center">' . $scores['overallScore'] . '</td >';
            echo '<td class="center">' . $scores['easeScore'] . '</td >';
            echo '<td class="center">' . $scores['qualityScore'] . '</td >';
            echo '<td class="center">' . $scores['peopleScore'] . '</td >';
            echo '<td class="center">' . get_the_date('F d, Y', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table><label>* - indicates rating is TBD</label></div>';
    }
}

/**
 * Create array of all ratings for a single service
 * @param $postId
 * @return array
 */
function get_all_ratings_for_a_service($postId)
{
    // Confirm whether both authors have submitted reviews
    // TODO: Create more thorough test to confirm if a user has submitted reviews, create a flag for all three or something
    $divideBy = 1;
    $scores['incomplete'] = true;
    if (get_field('prs_service_ease', $postId) && get_field('allykc_service_ease', $postId)) {
        $divideBy = 2;
        $scores['incomplete'] = false;
    }

    $easeScore = (get_field('prs_service_ease', $postId) + get_field('allykc_service_ease', $postId)) / $divideBy;
    $qualityScore = (get_field('prs_service_quality', $postId) + get_field('allykc_service_quality', $postId)) / $divideBy;
    $peopleScore = (get_field('prs_service_people', $postId) + get_field('allykc_service_people', $postId)) / $divideBy;
    $totalScore = $easeScore + $qualityScore + $peopleScore;

    if ($totalScore == 0) {
        $scores['easeScore'] = '*';
        $scores['qualityScore'] = '*';
        $scores['peopleScore'] = '*';
        $scores['totalScore'] = '*';
        $scores['overallScore'] = '*';
    } else {
        $scores['easeScore'] = $easeScore;
        $scores['qualityScore'] = $qualityScore;
        $scores['peopleScore'] = $peopleScore;
        $scores['totalScore'] = $totalScore;
        $scores['overallScore'] = round($totalScore / 3, 1);
    }
    return $scores;
}