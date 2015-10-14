<?php

/**
 * Get all chart classes
 *
 * @return array
 */
function erp_ac_get_chart_classes() {
    $classes = [
        1 => __( 'Assets', 'erp-accounting' ),
        2 => __( 'Liabilities', 'erp-accounting' ),
        3 => __( 'Expenses', 'erp-accounting' ),
        4 => __( 'Income', 'erp-accounting' ),
        5 => __( 'Equity', 'erp-accounting' ),
    ];

    return $classes;
}

/**
 * Get all chart
 *
 * @param $args array
 *
 * @return array
 */
function erp_ac_get_all_chart( $args = [] ) {
    global $wpdb;

    $defaults = array(
        'number'     => 20,
        'offset'     => 0,
        'orderby'    => 'id',
        'order'      => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'erp-ac-chart-all-' . md5( serialize( $args ) );
    $items     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $items ) {
        $sql = "SELECT ch.*, ct.class_id FROM {$wpdb->prefix}erp_ac_charts AS ch
            LEFT JOIN {$wpdb->prefix}erp_ac_chart_types AS ct ON ct.id = ch.account_type_id
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT {$args['offset']}, {$args['number']}";

        $items = $wpdb->get_results( $sql );

        wp_cache_set( $cache_key, $items, 'erp-accounting' );
    }

    return $items;
}

function erp_ac_get_all_chart_array( $args = [] ) {
    $charts  = erp_ac_get_all_chart( $args );
    $classes = erp_ac_get_chart_classes();
}

/**
 * Fetch all chart from database
 *
 * @return array
 */
function erp_ac_get_chart_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'erp_ac_charts' );
}

/**
 * Fetch a single chart from database
 *
 * @param int   $id
 *
 * @return array
 */
function erp_ac_get_chart( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'erp_ac_charts WHERE id = %d', $id ) );
}

/**
 * Insert a new chart
 *
 * @param array $args
 */
function erp_ac_insert_chart( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'id'              => null,
        'name'            => '',
        'account_type_id' => '',
        'active'          => 1,
    );

    $args       = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'erp_ac_charts';

    // some basic validation
    if ( empty( $args['name'] ) ) {
        return new WP_Error( 'no-name', __( 'No Name provided.', 'erp-accounting' ) );
    }

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( ! $row_id ) {

        // insert a new
        if ( $wpdb->insert( $table_name, $args ) ) {
            return $wpdb->insert_id;
        }

    } else {

        // do update method here
        if ( $wpdb->update( $table_name, $args, array( 'id' => $row_id ) ) ) {
            return $row_id;
        }
    }

    return false;
}

/**
 * Get all chart types
 *
 * @param $args array
 *
 * @return array
 */
function erp_ac_get_all_chart_types() {
    global $wpdb;

    $cache_key = 'erp-ac-chart-type-all';
    $items     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'erp_ac_chart_types ORDER BY class_id ASC' );

        wp_cache_set( $cache_key, $items, 'erp-accounting' );
    }

    return $items;
}

/**
 * Get chart type as array
 *
 * @return array
 */
function erp_ac_get_all_chart_types_array() {
    $classes   = erp_ac_get_chart_classes();
    $all_types = erp_ac_get_all_chart_types();
    $types     = [];

    foreach ($all_types as $type) {
        $types[ $type->class_id ][ $type->id ] = $type->name;
    }

    return $types;
}
