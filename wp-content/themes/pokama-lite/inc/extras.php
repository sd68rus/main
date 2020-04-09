<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package lotus
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function pokama_lite_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'pokama_lite_body_classes' );

/**
 * Custom excerpt more
 */
function pokama_lite_custom_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}
	return ' &hellip; ';
}
add_filter( 'excerpt_more', 'pokama_lite_custom_excerpt_more' );

function pokama_lite_custom_excerpt_length( $length ) {
    return 22;
}
add_filter( 'excerpt_length', 'pokama_lite_custom_excerpt_length', 999 );

function pokama_lite_comment_count() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments"><i class="fa fa-comment"></i>';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'pokama-lite' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}
}