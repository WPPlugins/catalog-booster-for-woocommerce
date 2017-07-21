<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Manages Discounts payment form
 *
 * Here Discounts payment form is defined and managed.
 *
 * @version		1.0.0
 * @package		woocommerce-catalog-booster/includes
 * @author 		Norbert Dreszer
 */
add_action( 'wp', 'ic_woo_ic_page_enable', 5 );

function ic_woo_ic_page_enable() {
	$enabled = ic_is_page_for_woo_enabled();
	if ( !empty( $enabled ) ) {
		add_filter( 'product_post_type_array', 'ic_woo_ic_post_type_page_enable' );
		add_filter( 'product_taxonomy_array', 'ic_woo_ic_page_tax_enable' );
		add_filter( 'current_product_post_type', 'ic_woo_ic_page_post_type' );
		add_filter( 'ic_current_product_tax', 'ic_woo_ic_page_taxonomy' );
		add_filter( 'current_product_catalog_taxonomy', 'ic_woo_ic_page_taxonomy' );
		add_filter( 'show_categories_taxonomy', 'ic_woo_ic_page_taxonomy' );
		add_filter( 'price_format', 'ic_page_woo_price_format', 10, 2 );
		add_filter( 'product_listing_id', 'ic_page_product_listing_id' );
		add_filter( 'widget_product_categories_dropdown_args', 'ic_page_category_widget_tax' );
		add_filter( 'widget_product_categories_args', 'ic_page_category_widget_tax' );
	}
}

function ic_woo_ic_post_type_page_enable( $array ) {
	if ( is_product() ) {
		$array[] = 'product';
	}
	return $array;
}

function ic_woo_ic_page_tax_enable( $array ) {
	if ( is_product() ) {
		$array[] = 'product_cat';
	}
	return $array;
}

function ic_woo_ic_page_post_type( $post_type ) {
	if ( is_product() ) {
		return 'product';
	}
	return $post_type;
}

function ic_woo_ic_page_taxonomy( $taxonomy ) {
	if ( is_product() ) {
		return 'product_cat';
	}
	return $taxonomy;
}

function ic_page_woo_price_format( $formatted, $raw ) {
	if ( is_product() ) {
		$formatted = wc_price( $raw );
	}
	return $formatted;
}

function ic_page_product_listing_id( $id ) {
	if ( is_product() ) {
		$id = get_option( 'woocommerce_shop_page_id' );
	}
	return $id;
}

function ic_page_category_widget_tax( $widget_args ) {
	if ( is_product() ) {
		$widget_args[ 'taxonomy' ] = 'product_cat';
	}
	return $widget_args;
}
