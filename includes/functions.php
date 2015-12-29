<?php

function erp_ac_get_expense_form_types() {
    $form_types = [
        'payment_voucher' => [
            'name'        => 'payment_voucher',
            'label'       => __( 'Payment Voucher', 'erp-accounting' ),
            'description' => __( 'A purchase that has been made through bank or cash.', 'erp-accounting' ),
            'type'        => 'credit'
        ],
        'vendor_credit' => [
            'name'        => 'vendor_credit',
            'label'       => __( 'Vendor Credit', 'erp-accounting' ),
            'description' => __( 'A purchase that has been made as credit from vendor.', 'erp-accounting' ),
            'type'        => 'credit'
        ],
    ];

    return apply_filters( 'erp_ac_get_expense_form_types', $form_types );
}

function erp_ac_get_sales_form_types() {
    $form_types = [
        'payment' => [
            'name'        => 'payment',
            'label'       => __( 'Payment', 'erp-accounting' ),
            'description' => __( '', 'erp-accounting' ),
            'type'        => 'debit'
        ],
        'invoice' => [
            'name'        => 'invoice',
            'label'       => __( 'Invoice', 'erp-accounting' ),
            'description' => __( '', 'erp-accounting' ),
            'type'        => 'debit'
        ],
    ];

    return apply_filters( 'erp_ac_get_sales_form_types', $form_types );
}

/**
 * Format date
 *
 * @param  string  $date
 *
 * @return string
 */
function erp_ac_format_date( $date ) {
    $format = erp_get_option( 'date_format', 'erp_settings_accounting', 'd-m-Y' );

    return date_i18n( $format, strtotime( $date ) );
}

/**
 * Get transaction status label
 *
 * @param  string  $status
 *
 * @return string
 */
function erp_ac_get_status_label( $status ) {
    $label = '';

    switch ( $status ) {
        case 'closed':
            $label = __( 'Closed', 'erp-accounting' );
            break;

        case 'paid':
            $label = __( 'Paid', 'erp-accounting' );
            break;

        case 'awaiting_payment':
            $label = __( 'Awaiting Payment', 'erp-accounting' );
            break;

        case 'overdue':
            $label = __( 'Overdue', 'erp-accounting' );
            break;
    }

    return apply_filters( 'erp_ac_status_labels', $label, $status );
}

function erp_ac_get_currency_symbole() {
    $currencty = erp_get_option( 'base_currency', 'erp_settings_accounting', false );
    
    if ( $currencty ) {
       return erp_get_currency_symbol( $currencty ); 
    } else {
        return '$';
    }
}

function erp_ac_get_currency() {
    $currency = erp_get_option( 'base_currency', 'erp_settings_accounting', false );
    
    if ( $currency ) {
        return $currency; 
    } else {
        return 'AUD';
    }
}
