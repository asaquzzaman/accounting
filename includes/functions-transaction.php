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
        'orderby' => 'issue_date',
        'order'   => 'DESC',
    );

    $args      = wp_parse_args( $args, $defaults );
    $cache_key = 'erp-ac-transaction-all-' . md5( serialize( $args ) );
    $items     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $items ) {
        $transaction = new WeDevs\ERP\Accounting\Model\Transaction();

        if ( isset( $args['user_id'] ) && ! empty( $args['user_id'] ) ) {
            $transaction = $transaction->where( 'user_id', '=', $args['user_id'] );
        }

        if ( isset( $args['start_date'] ) && ! empty( $args['start_date'] ) ) {
            $transaction = $transaction->where( 'issue_date', '>=', $args['start_date'] );
        }

        if ( isset( $args['end_date'] ) && ! empty( $args['end_date'] ) ) {
            $transaction = $transaction->where( 'issue_date', '<=', $args['end_date'] );
        }

        $items = $transaction->skip( $args['offset'] )
                ->take( $args['number'] )
                ->type( $args['type'] )
                ->orderBy( $args['orderby'], $args['order'] )
                ->orderBy( 'created_at', $args['order'] )
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
function erp_ac_get_transaction_count( $type = 'expense', $user_id = 0 ) {
    $cache_key = 'erp-ac-' . $type . '-' . $user_id . '-count';
    $count     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $count ) {
        $trans = new WeDevs\ERP\Accounting\Model\Transaction();

        if ( $user_id ) {
            $trans = $trans->where( 'user_id', '=', $user_id );
        }

        $count = $trans->type( $type )->count();
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

    if ( false === $transaction ) {
        $transaction = WeDevs\ERP\Accounting\Model\Transaction::find( $id )->toArray();
    }

    return $transaction;
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
        //$item_entry_type = $args['invoice_payment'] ? 'credit' : $item_entry_type;
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


/**
 * Get transactions for a ledger
 *
 * @param  int  $ledger_id
 * @param  array   $args
 *
 * @return array
 */
function erp_ac_get_ledger_transactions( $ledger_id, $args = [] ) {
    global $wpdb;

    $defaults = [
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'issue_date',
        'order'   => 'DESC',
    ];

    $args = wp_parse_args( $args, $defaults );

    $cache_key = 'erp-ac-ledger-transactions-' . md5( serialize( $args ) );
    $items     = wp_cache_get( $cache_key, 'erp-accounting' );

    if ( false === $items ) {
        $where = sprintf( 'WHERE jour.ledger_id = %d', absint( $ledger_id ) );
        $limit = ( $args['number'] == '-1' ) ? '' : sprintf( 'LIMIT %d, %d', $args['offset'], $args['number'] );

        if ( isset( $args['start_date'] ) && !empty( $args['start_date'] ) ) {
            $where .= " AND trans.issue_date >= '{$args['start_date']}' ";
        }

        if ( isset( $args['end_date'] ) && !empty( $args['end_date'] ) ) {
            $where .= " AND trans.issue_date <= '{$args['end_date']}' ";
        }

        if ( isset( $args['type'] ) && !empty( $args['type'] ) ) {
            $where .= " AND trans.type = '{$args['type']}' ";
        }

        if ( isset( $args['form_type'] ) && !empty( $args['form_type'] ) ) {
            $where .= " AND trans.form_type = '{$args['form_type']}' ";
        }

        $sql = "SELECT * FROM {$wpdb->prefix}erp_ac_journals as jour
            LEFT JOIN {$wpdb->prefix}erp_ac_transactions as trans ON trans.id = jour.transaction_id
            $where
            ORDER BY {$args['orderby']} {$args['order']}
            $limit";

        $items = $wpdb->get_results( $sql );
        wp_cache_set( $cache_key, $items, 'erp-accounting' );
    }

    return $items;
}

function erp_ac_get_customer_received_money( $customer_transaction ) {
    $customer_transaction = is_array( $customer_transaction ) ? $customer_transaction : array();
    $total_amount = 0;

    foreach ( $customer_transaction as $key => $transaction ) {
        if ( $transaction->status == 'closed' ) {
            $total_amount = $total_amount + $transaction->trans_total;
        }
    }

    return $total_amount;
}

function erp_ac_get_customer_due_amount( $customer_transaction ) {
    $customer_transaction = is_array( $customer_transaction ) ? $customer_transaction : array();
    $total_amount = 0;

    foreach ( $customer_transaction as $key => $transaction ) {
        if ( $transaction->status == 'awaiting_payment' ) {
            $total_amount = $total_amount + $transaction->trans_total;
        }
    }

    return $total_amount;
}


