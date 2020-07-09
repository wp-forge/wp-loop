<?php

if ( ! function_exists( 'wp_loop' ) ) {

	/**
	 * Simplifies the WordPress loop.
	 *
	 * @param WP_Query|Iterator|array|null $iterable WordPress query object or an array of posts.
	 *
	 * @return Generator
	 *
	 * @global WP_Query                    $wp_query WordPress query object.
	 * @global WP_Post                     $post     WordPress post object.
	 *
	 */
	function wp_loop( $iterable = null ) {

		// If no iterable was passed, use the global query
		if ( null === $iterable ) {
			$iterable = $GLOBALS['wp_query'];
		}

		// If the iterable contains a posts property, use that as the iterable
		if ( is_object( $iterable ) && property_exists( $iterable, 'posts' ) ) {
			$iterable = $iterable->posts;
		}

		// If we don't have a valid iterable, return an empty array
		if ( ! is_iterable( $iterable ) ) {
			/* translators: Data type */
			_doing_it_wrong( __FUNCTION__, sprintf( __( 'Expected an iterable, received %s instead.' ), gettype( $iterable ) ), '5.x' );

			return array();
		}

		global $post;

		// Save the global post object so we can restore it later
		$save_post = $post;

		try {
			foreach ( $iterable as $post ) {

				// Ensure that we always yield a WP_Post object.
				if ( ! is_a( $post, WP_Post::class ) ) {
					$post = get_post( $post );
				}

				// If post is valid then return it, otherwise skip it.
				if ( is_a( $post, WP_Post::class ) ) {
					setup_postdata( $post );
					yield $post;
				} else {
					/* translators: Data type */
					_doing_it_wrong( __FUNCTION__, sprintf( __( 'Expected a WP_Post object, received %s instead.' ), gettype( $iterable ) ), '5.x' );
				}
			}
		} finally {
			wp_reset_postdata();
			// Restore the global post object
			$post = $save_post;
		}
	}

}
