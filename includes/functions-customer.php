<?php

/**
 * Get all customer
 *
 * @param $args array
 *
 * @return array
 */
function erp_ac_get_all_customer( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'type'    => 'customer',
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'customer-' . $args['type'] . '-all';
    $items     = wp_cache_get( $cache_key, 'wp-erp-ac' );

    if ( false === $items ) {
        $items = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'erp_ac_customers WHERE type = "' . $args['type'] . '" ORDER BY ' . $args['orderby'] .' ' . $args['order'] .' LIMIT ' . $args['offset'] . ', ' . $args['number'] );

        wp_cache_set( $cache_key, $items, 'wp-erp-ac' );
    }

    return $items;
}

/**
 * Get users as array
 *
 * @param  array   $args
 *
 * @return array
 */
function erp_ac_get_all_customer_array( $args = [] ) {
    $users = [];
    $customers = erp_ac_get_all_customer( $args );

    foreach ($customers as $user) {
        $users[ $user->id ] = $user->first_name . ' ' . $user->last_name;
    }

    return $users;
}

/**
 * Fetch all customer from database
 *
 * @return array
 */
function erp_ac_get_customer_count( $type = 'customer' ) {
    global $wpdb;

    return (int) $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'erp_ac_customers WHERE type = %s', $type ) );
}

/**
 * Fetch a single customer from database
 *
 * @param int   $id
 *
 * @return array
 */
function erp_ac_get_customer( $id = 0 ) {
    global $wpdb;

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'erp_ac_customers WHERE id = %d', $id ) );
}

/**
 * Insert a new customer
 *
 * @param array $args
 */
function erp_ac_insert_customer( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'id'          => null,
        'first_name'  => '',
        'last_name'   => '',
        'email'       => '',
        'company'     => '',
        'phone'       => '',
        'mobile'      => '',
        'other'       => '',
        'website'     => '',
        'fax'         => '',
        'notes'       => '',
        'street_1'    => '',
        'city'        => '',
        'state'       => '',
        'postal_code' => '',
        'country'     => '',
        'currency'    => '',
        'type'        => '',
    );

    $args       = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'erp_ac_customers';

    // some basic validation
    if ( empty( $args['first_name'] ) ) {
        return new WP_Error( 'no-first_name', __( 'No First Name provided.', 'erp-accounting' ) );
    }
    if ( empty( $args['last_name'] ) ) {
        return new WP_Error( 'no-last_name', __( 'No Last Name provided.', 'erp-accounting' ) );
    }

    // remove row id to determine if new or update
    $row_id = (int) $args['id'];
    unset( $args['id'] );

    if ( ! $row_id ) {

        $args['created'] = current_time( 'mysql' );

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