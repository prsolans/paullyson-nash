<?php


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
            'taxonomies' => array('category', 'post_tag')

        )
    );
}

add_action('init', 'create_experience_post_type');
/**
 * Create Experience Custom Post Type
 */
function create_experience_post_type()
{
    register_post_type('experience',
        array(
            'labels' => array(
                'name' => __('Experiences'),
                'singular_name' => __('Experience')
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-smiley',
            'taxonomies' => array('category', 'post_tag')
        )
    );
}

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
            'taxonomies' => array('category', 'post_tag')
        )
    );
}

add_action('init', 'create_shop_post_type');
/**
 * Create Shop Custom Post Type
 */
function create_shop_post_type()
{
    register_post_type('shop',
        array(
            'labels' => array(
                'name' => __('Shops'),
                'singular_name' => __('Shop')
            ),
            'public' => true,
            'has_archive' => true,
            'menu_position' => 4,
            'menu_icon' => 'dashicons-cart',
            'taxonomies' => array('category', 'post_tag')
        )
    );

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
 * @string $posttype - Post type for the ratings
 * @string $username - Author name whose table will be displayes
 */
function display_ratings_table($posttype, $username)
{
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_type' => $posttype
    ));


    if($posttype == 'restaurant'){
        display_restaurant_table($posts, $username);
    }
    elseif($posttype == 'experience'){
        display_experience_table($posts, $username);
    }

}

/**
 * Display table of ratings for restaurants
 * @array $posts - Collection of posts
 * @param $username - Related to a specific author
 */
function display_restaurant_table($posts, $username) {
    $usernameToLower = strtolower($username);

    if ($posts) {
        echo '<div class="ratingTable">
                            <h1>' . $username . '</h1>
                            <table>
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
 * Display table of ratings for experiences
 * @array $posts - Collection of posts
 * @param $username - Related to a specific author
 */
function display_experience_table($posts, $username) {
    $usernameToLower = strtolower($username);

    if ($posts) {
        echo '<div class="ratingTable">
            <h1>' . $username . '</h1>
            <table>
                <thead>
                    <th>Experience</th><th class="center">Venue</th><th class="center">Fun</th><th class="center">Ease</th><th class="center">Intangibles</th></tr>
                </thead>
                <tbody>';
        foreach ($posts as $post) {
            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td >';
            echo '<td class="center">' . get_field($usernameToLower . '_experience_venue', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_experience_fun', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_experience_ease', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_experience_intangibles', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table></div>';
    }
}