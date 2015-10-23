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
        $sql = "SELECT ch.*, ct.class_id, ct.name as type_name FROM {$wpdb->prefix}erp_ac_ledger AS ch
            LEFT JOIN {$wpdb->prefix}erp_ac_chart_types AS ct ON ct.id = ch.type_id
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT {$args['offset']}, {$args['number']}";

        $items = $wpdb->get_results( $sql );

        wp_cache_set( $cache_key, $items, 'erp-accounting' );
    }

    return $items;
}


/**
 * Fetch all chart from database
 *
 * @return array
 */
function erp_ac_get_chart_count() {
    global $wpdb;

    return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'erp_ac_ledger' );
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

    return $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'erp_ac_ledger WHERE id = %d', $id ) );
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
    $table_name = $wpdb->prefix . 'erp_ac_ledger';

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

        // don't allow to change account type
        unset( $args['account_type_id'] );

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


function erp_ac_get_charts() {
    $raw = [
        1 => [],
        2 => [],
        3 => [],
        4 => [],
        5 => [],
    ];

    $cache_key = 'erp_account_charts';
    $accounts  = wp_cache_get( $cache_key );

    if ( false === $accounts ) {
        $accounts = \WeDevs\ERP\Accounting\Model\Chart_Of_Accounts::all()->toArray();
        wp_cache_set( $cache_key, $accounts );
    }

    // group by account_type_id
    foreach ($accounts as $ac) {
        $raw[ $ac['type_id'] ][] = $ac;
    }

    return $raw;
}

function erp_ac_get_chart_dropdown( $args = [] ) {
    $account_charts = [];
    $defaults       = [
        'select_text' => __( '&#8212; Select Account &#8212;', 'textdomain' ),
        'selected'    => '0',
        'name'        => 'chart-of-accounts',
        'exclude'     => false,
        'class'       => 'select2',
        'required'    => false
    ];
    $args         = wp_parse_args( $args, $defaults );

    global $wpdb;

    $cache_key = 'erp-account-ledgers-list';
    $ledgers = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $ledgers ) {
        $sql = "SELECT led.id, led.code, led.name, class.name as class_name, class.id as class_id from {$wpdb->prefix}erp_ac_ledger as led
            LEFT JOIN {$wpdb->prefix}erp_ac_chart_types as types on led.type_id = types.id
            LEFT JOIN {$wpdb->prefix}erp_ac_chart_classes as class on class.id = types.class_id
            ORDER BY led.id ASC";

        $ledgers = $wpdb->get_results( $sql );
        wp_cache_set( $cache_key, $ledgers, 'erp-accounting' );
    }

    // build the array
    if ( $ledgers ) {
        foreach ($ledgers as $ledger) {
            if ( ! isset( $account_charts[ $ledger->class_id ] ) ) {
                $account_charts[ $ledger->class_id ]['label'] = $ledger->class_name;
                $account_charts[ $ledger->class_id ]['options'][] = $ledger;
            } else {
                $account_charts[ $ledger->class_id ]['options'][] = $ledger;
            }

        }
    }

    $dropdown = sprintf( '<select name="%1$s" id="%1$s" class="%2$s"%3$s>', $args['name'], $args['class'], $args['required'] == true ? ' required="required"' : '' );

    // exclude charts
    if ( is_array( $args['exclude'] ) && $args['exclude'] ) {
        foreach ($args['exclude'] as $class_id) {
            if ( array_key_exists( $class_id, $account_charts ) ) {
                unset( $account_charts[ $class_id ] );
            }
        }
    }

    $dropdown .= '<option value=""' . selected( $args['selected'], '0', false ) . '>' . $args['select_text'] . '</option>';

    if ( $account_charts ) {
        foreach( $account_charts as $chart ) {
            $dropdown .= '<optgroup label="' . $chart['label'] . '">';
            foreach ($chart['options'] as $ledger) {
                $dropdown .= sprintf( '<option value="%s">%s - %s</option>', $ledger->id, $ledger->code, $ledger->name );
            }
            $dropdown .= '</optgroup>';
        }
    }

    $dropdown .= '</select>';

    return $dropdown;
}

/**
 * Print the chart table
 *
 * @param  string  $title
 * @param  array   $charts
 *
 * @return void
 */
function erp_ac_chart_print_table( $title, $charts = [] ) {
    $edit_url = admin_url( 'admin.php?page=erp-accounting-charts&action=edit&id=' );

    include dirname( __FILE__ ) . '/views/accounts/chart-table.php';
}
