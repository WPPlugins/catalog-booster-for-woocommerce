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
add_action( 'wp', 'ic_woo_ic_listing_enable' );
add_action( 'ic_ajax_self_submit', 'ic_woo_ic_listing_enable' );

function ic_woo_ic_listing_enable() {
	$enabled = ic_is_listing_for_woo_enabled();
	if ( !empty( $enabled ) ) {
		add_filter( 'product_post_type_array', 'ic_woo_ic_post_type_enable' );
		add_filter( 'product_taxonomy_array', 'ic_woo_ic_listing_tax_enable' );
		add_filter( 'current_product_post_type', 'ic_woo_ic_listing_post_type' );
		add_filter( 'ic_current_product_tax', 'ic_woo_ic_listing_taxonomy' );
		add_filter( 'current_product_catalog_taxonomy', 'ic_woo_ic_listing_taxonomy' );
		add_filter( 'show_categories_taxonomy', 'ic_woo_ic_listing_taxonomy' );
		add_filter( 'price_format', 'ic_woo_price_format', 10, 2 );
		add_filter( 'product_listing_id', 'ic_listing_product_listing_id' );
		add_filter( 'widget_product_categories_dropdown_args', 'ic_listing_category_widget_tax' );
		add_filter( 'widget_product_categories_args', 'ic_listing_category_widget_tax' );
		add_filter( 'shortcode_query', 'ic_listing_shortcode_query_post_type' );
		add_filter( 'ic_category_image_id', 'ic_woo_category_image_id', 10, 2 );
	}
}

function ic_woo_ic_post_type_enable( $array ) {
	if ( ic_woocat_enable_on_listing() ) {
		$array[] = 'product';
	}
	return $array;
}

function ic_woo_ic_listing_tax_enable( $array ) {
	if ( ic_woocat_enable_on_listing() ) {
		$array[] = 'product_cat';
	}
	return $array;
}

function ic_woo_ic_listing_post_type( $post_type ) {
	if ( ic_woocat_enable_on_listing() ) {
		return 'product';
	}
	return $post_type;
}

function ic_listing_category_widget_tax( $widget_args ) {
	if ( ic_woocat_enable_on_listing() ) {
		$widget_args[ 'taxonomy' ] = 'product_cat';
	}
	return $widget_args;
}

function ic_woo_ic_listing_taxonomy( $taxonomy ) {
	if ( ic_woocat_enable_on_listing() ) {
		return 'product_cat';
	}
	return $taxonomy;
}

function ic_woo_price_format( $formatted, $raw ) {
	if ( ic_woocat_enable_on_listing() ) {
		$formatted = wc_price( $raw );
	}
	return $formatted;
}

function ic_listing_product_listing_id( $id ) {
	$ic_woocat = ic_woocat_settings();
	if ( empty( $ic_woocat[ 'catalog' ][ 'enable' ] ) || ic_woocat_enable_on_listing() ) {
		$id = get_option( 'woocommerce_shop_page_id' );
	}
	return $id;
}

function ic_listing_shortcode_query_post_type( $query ) {
	$ic_woocat = ic_woocat_settings();
	if ( empty( $ic_woocat[ 'catalog' ][ 'enable' ] ) || ic_woocat_enable_on_listing() ) {
		$query[ 'post_type' ] = 'product';
	}
	return $query;
}

function ic_woocat_enable_on_listing() {
	global $ic_ajax_query_vars;
	if ( is_shop() || is_product_category() || is_product_taxonomy() || (is_ic_ajax() && !empty( $ic_ajax_query_vars[ 'post_type' ] ) && $ic_ajax_query_vars[ 'post_type' ] === 'product') ) {
		return true;
	}
	return false;
}

function ic_woo_category_image_id( $img_id, $cat_id ) {
	if ( empty( $img_id ) && function_exists( 'get_woocommerce_term_meta' ) ) {
		$img_id = get_woocommerce_term_meta( $cat_id, 'thumbnail_id', true );
	}
	return $img_id;
}
