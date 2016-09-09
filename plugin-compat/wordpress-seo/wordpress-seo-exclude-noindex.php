<?php

/**
 * Tell SearchWP to automatically exclude any entries that have
 * been marked as noindex in WordPress SEO
 *
 * @param  array   $ids     Excluded post IDs
 * @param  string  $engine  The engine being used
 * @param  array   $terms   The search terms
 * @return array            Post IDs to exclude
 */
function my_searchwp_wordpress_seo_exclude_noindex( $ids, $engine, $terms ) {

	$entries_to_exclude = get_posts(
		array(
			'post_type'  => 'any',
			'nopaging'   => true,
			'fields'     => 'ids',
			'meta_query' => array(
				array(
					'key'      => '_yoast_wpseo_meta-robots-noindex',
					'value'    => true,
				),
			),
		)
	);

	$ids = array_unique( array_merge( $ids, array_map( 'absint', $entries_to_exclude ) ) );

	return $ids;
}

add_filter( 'searchwp_exclude', 'my_searchwp_wordpress_seo_exclude_noindex', 10, 3 );
