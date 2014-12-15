<?php
/**
 * Functions specific to Shop custom post type
 * Created by PhpStorm.
 * User: prsolans
 * Date: 12/13/14
 * Time: 4:42 PM
 */

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
            'taxonomies' => array('category', 'post_tag'),
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail')
        )
    );

}

/**
 * Display table of shop ratings for a specific author
 * @param array $posts - Collection of posts
 * @param string $username - Related to a specific author
 */
function display_shop_table($posts, $username)
{
    $usernameToLower = strtolower($username);

    if ($posts) {
        echo '<div class="ratingTable">
            <h1>' . $username . '</h1>
            <table>
                <thead>
                    <th>Shop</th><th class="center">Ease</th><th class="center">Quality</th><th class="center">Ambiance</th></tr>
                </thead>
                <tbody>';
        foreach ($posts as $post) {
            echo '<tr ><td ><a href = "' . get_permalink($post->ID) . '" > ' . get_the_title($post->ID) . '</a ></td >';
            echo '<td class="center">' . get_field($usernameToLower . '_shop_ease', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_shop_quality', $post->ID) . '</td >';
            echo '<td class="center">' . get_field($usernameToLower . '_shop_ambiance', $post->ID) . '</td ></tr >';
        }

        echo '</tbody></table></div>';
    }
}