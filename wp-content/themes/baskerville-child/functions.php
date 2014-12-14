<?php

add_action('wp_enqueue_scripts', 'add_custom_theme_assets');
/**
 * Add scripts and stylesheets specific to this child theme
 */
function add_custom_theme_assets()
{

    wp_register_script(
        'tablesorter',
        get_template_directory_uri() . '-child/js/tablesorter.js',
        array('jquery'),
        '2.0',
        true
    );

    wp_enqueue_script('tablesorter');
    wp_enqueue_style('tablesorter', get_template_directory_uri() . '-child/styles/tablesorter/tablesorter.css');
}

/**
 *  Get title and link for a page and format/display
 *  For use in templates
 */
function display_page_title()
{
    echo '<h2 class="post-title"><a href="' . get_permalink() . '" rel="bookmark" title="' . get_the_title() . '">' . get_the_title() . '</a>';
    echo '</h2>';
}

/**
 *  Get main page text content and display within
 *  unique template content
 */
function display_page_block_copy()
{
    if (have_posts()) : while (have_posts()) : the_post();
        the_content();
    endwhile; endif;
}

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

add_filter('pre_get_posts', 'query_post_type');

/**
 * Allow custom post types to appear on tag/category pages
 * https://wordpress.org/support/topic/custom-post-type-tagscategories-archive-page
 * @param $query
 * @return null
 */
function query_post_type($query)
{
    if (is_category() || is_tag()) {
        $post_type = get_query_var('post_type');
        if (!$post_type) {
            $post_type = array('restaurant', 'service', 'shop', 'experience', 'nav_menu_item');
        }
        if (!empty($query)) {
            $query->set('post_type', $post_type);
        }
        return $query;
    }

    return null;
}

/**
 * @return mixed
 */
function pronamic_google_maps_address()
{
    global $post;

    $address = get_post_meta($post->ID, Pronamic_Google_Maps_Post::META_KEY_ADDRESS, true);
    return $address;
}

/**
 *
 */
function pronamic_google_maps_link()
{

    $q = get_the_title() . ' ' . strip_tags(pronamic_google_maps_address());

    printf(
        '<a href="%s" target="_blank">%s</a>',
        add_query_arg('q', $q, 'https://www.google.com/maps'),
        __('View Map on Google', 'text_domain')
    );

}
