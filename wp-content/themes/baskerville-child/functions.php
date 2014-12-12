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
			'menu_icon'     => 'dashicons-location',
            'taxonomies' => array('category', 'post_tag')

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
			'menu_icon'     => 'dashicons-smiley',
            'taxonomies' => array('category', 'post_tag')
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
			'menu_icon'     => 'dashicons-art',
            'taxonomies' => array('category', 'post_tag')
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
            'taxonomies' => array('category', 'post_tag')
		)
	);

}


?>
