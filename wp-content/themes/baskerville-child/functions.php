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
