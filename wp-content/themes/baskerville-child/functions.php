<?php


add_action( 'init', 'create_restaurant_post_type' );
/**
 * Create Restaurant Custom Post Type
 */
function create_restaurant_post_type() {
	register_post_type( 'restaurant',
		array(
			'labels'        => array(
				'name'          => __( 'Restaurants' ),
				'singular_name' => __( 'Restaurant' )
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-location'
		)
	);
}

add_action( 'init', 'create_experience_post_type' );
/**
 * Create Experience Custom Post Type
 */
function create_experience_post_type() {
	register_post_type( 'experience',
		array(
			'labels'        => array(
				'name'          => __( 'Experiences' ),
				'singular_name' => __( 'Experience' )
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-smiley'

		)
	);
}

add_action( 'init', 'create_service_post_type' );
/**
 * Create Service Custom Post Type
 */
function create_service_post_type() {
	register_post_type( 'service',
		array(
			'labels'        => array(
				'name'          => __( 'Services' ),
				'singular_name' => __( 'Service' )
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-art'
		)
	);
}

add_action( 'init', 'create_shop_post_type' );
/**
 * Create Shop Custom Post Type
 */
function create_shop_post_type() {
	register_post_type( 'shop',
		array(
			'labels'        => array(
				'name'          => __( 'Shops' ),
				'singular_name' => __( 'Shop' )
			),
			'public'        => true,
			'has_archive'   => true,
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-cart',
            'rewrite'       => true
		)
	);

}

add_action( 'init', 'display_custom_restaurant_rankings' );

function display_custom_restaurant_rankings() {

	return '5';
}

?>
