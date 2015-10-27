<?php

/**
 * Get all transaction
 *
 * @param $args array
 *
 * @return array
 */
function erp_ac_get_all_transaction( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'type'    => 'expense',
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'erp-ac-transaction-all-' . md5( serialize( $args ) );
    $items     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $items ) {
        $transaction = new WeDevs\ERP\Accounting\Model\Transaction();
        $items = $transaction->skip( $args['offset'] )
                ->take( $args['number'] )
                ->type( $args['type'] )
                ->orderBy( $args['orderby'], $args['order'] )
                ->get()
                ->toArray();

        $items = erp_array_to_object( $items );

        wp_cache_set( $cache_key, $items, 'erp-accounting' );
    }

    return $items;
}

/**
 * Fetch all transaction from database
 *
 * @return array
 */
function erp_ac_get_transaction_count( $type = 'expense' ) {
    $cache_key = 'erp-ac-' . $type . '-count';
    $count     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $count ) {
        $count = WeDevs\ERP\Accounting\Model\Transaction::type( $type )->count();
    }

    return (int) $count;
}

/**
 * Fetch a single transaction from database
 *
 * @param int   $id
 *
 * @return array
 */
function erp_ac_get_transaction( $id = 0 ) {
    $cache_key   = 'erp-ac-transaction' . $id;
    $transaction = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $count ) {
        $transaction = WeDevs\ERP\Accounting\Model\Transaction::find( $id );
    }

    return erp_array_to_object( $transaction );
}

/**
 * Insert a new transaction
 *
 * @param array $args
 */
function erp_ac_insert_transaction( $args = [], $items = [] ) {
    global $wpdb;

    if ( ! $items ) {
        return new WP_Error( 'no-items', __( 'No transaction items found', 'erp-accounting' ) );
    }

    $defaults = array(
        'id'              => null,
        'type'            => '',
        'form_type'       => '',
        'account_id'      => '',
        'status'          => '',
        'user_id'         => '',
        'billing_address' => '',
        'ref'             => '',
        'issue_date'      => '',
        'summary'         => '',
        'total'           => '',
        'files'           => '',
        'currency'        => '',
        'created_by'      => get_current_user_id(),
        'created_at'      => current_time( 'mysql' )
    );

    $args       = wp_parse_args( $args, $defaults );
    $table_name = $wpdb->prefix . 'erp_ac_transactions';

    // get valid transaction type and form type
    if ( ! in_array( $args['type'], [ 'expense', 'sales' ] ) ) {
        return new WP_Error( 'invalid-trans-type', __( 'Error: Invalid transaction type.', 'erp-accounting' ) );
    }

    $form_types = ( $args['type'] == 'expense' ) ? erp_ac_get_expense_form_types() : erp_ac_get_sales_form_types();

    if ( ! array_key_exists( $args['form_type'], $form_types ) ) {
        return new WP_Error( 'invalid-form-type', __( 'Error: Invalid form type', 'erp-accounting' ) );
    }

    $form_type = $form_types[ $args['form_type'] ];

    // some basic validation
    if ( empty( $args['issue_date'] ) ) {
        return new WP_Error( 'no-issue_date', __( 'No Issue Date provided.', 'erp-accounting' ) );
    }
    if ( empty( $args['total'] ) ) {
        return new WP_Error( 'no-total', __( 'No Total provided.', 'erp-accounting' ) );
    }

    // remove row id to determine if new or update
    $row_id          = (int) $args['id'];
    $main_account_id = (int) $args['account_id'];
    unset( $args['id'] );
    unset( $args['account_id'] );

    // BEGIN INSERTION
    try {
        $wpdb->query( 'START TRANSACTION' );

        $transaction = new WeDevs\ERP\Accounting\Model\Transaction();
        $trans = $transaction->create( $args );

        if ( ! $trans->id ) {
            throw new Exception( __( 'Could not create transaction', 'erp-accounting' ) );
        }

        // create the main journal entry
        $main_journal = $trans->journals()->create([
            'ledger_id'        => $main_account_id,
            'type'             => 'main',
            $form_type['type'] => $args['total']
        ]);

        if ( ! $main_journal->id ) {
            throw new Exception( __( 'Could not insert main journal item', 'erp-accounting' ) );
        }

        // enter the transaction items
        $order           = 1;
        $item_entry_type = ( $form_type['type'] == 'credit' ) ? 'debit' : 'credit';
        foreach ($items as $item) {
            $journal = $trans->journals()->create([
                'ledger_id'      => $item['account_id'],
                'type'           => 'line_item',
                $item_entry_type => $item['line_total']
            ]);

            if ( ! $journal->id ) {
                throw new Exception( __( 'Could not insert journal item', 'erp-accounting' ) );
            }

            $trans_item = $trans->items()->create([
                'journal_id'  => $journal->id,
                'product_id'  => null,
                'description' => $item['description'],
                'qty'         => $item['qty'],
                'unit_price'  => $item['unit_price'],
                'discount'    => $item['discount'],
                'line_total'  => $item['line_total'],
                'order'       => $order,
            ]);

            if ( ! $trans_item->id ) {
                throw new Exception( __( 'Could not insert transaction item', 'erp-accounting' ) );
            }

            $order++;
        }

        $wpdb->query( 'COMMIT' );

        return $trans->id;

    } catch (Exception $e) {
        $wpdb->query( 'ROLLBACK' );
        return new WP_error( 'final-exception', $e->getMessage() );
    }

    return false;
}