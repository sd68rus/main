<?php

add_filter( 'the_content', 'rcl_add_product_box', 10 );
function rcl_add_product_box( $content ) {
	global $post, $rmag_options;

	if ( $post->post_type != 'products' || doing_filter( 'get_the_excerpt' ) )
		return $content;

	$content = apply_filters( 'rcl_product_content', $content );

	$productCart = (isset( $rmag_options['cart_button_single_page'] )) ? $rmag_options['cart_button_single_page'] : array( 'top', 'bottom' );

	$productBox = '<div id="rcl-product-box">';

	if ( get_post_meta( $post->ID, 'recall_slider', 1 ) ) {

		$productBox .= '<div class="product-gallery">';

		$productBox .= rcl_get_product_gallery( $post->ID );

		$productBox .= '</div>';
	}

	if ( doing_filter( 'the_content' ) && $productCart && in_array( 'top', $productCart ) ) {

		$cartBox = new Rcl_Cart_Button_Form( array(
			'product_id' => $post->ID
			) );

		$productBox .= '<div class="product-metabox">';

		$productBox .= $cartBox->cart_form();

		$productBox .= '</div>';
	}

	$productBox .= '</div>';

	return $productBox . $content;
}

function rcl_get_product_box( $product_id ) {

	$cartBox = new Rcl_Cart_Button_Form( array(
		'product_id' => $product_id
	) );

	$content = '<div id="rcl-product-box">';

	if ( get_post_meta( $product_id, 'recall_slider', 1 ) ) {

		$content .= '<div class="product-gallery">';

		$content .= rcl_get_product_gallery( $product_id );

		$content .= '</div>';
	}

	$content .= '<div class="product-metabox">';

	$content .= $cartBox->cart_form();

	$content .= '</div>';

	$content .= '</div>';

	return $content;
}
