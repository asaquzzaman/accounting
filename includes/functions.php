<?php

function erp_ac_get_expense_form_types() {
    $form_types = [
        'payment_voucher' => [
            'name'        => 'payment_voucher',
            'label'       => __( 'Payment Voucher', 'erp-accounting' ),
            'description' => __( 'A purchase that has been made through bank or cash.', 'erp-accounting' ),
            'type'        => 'credit'
        ]
    ];

    return apply_filters( 'erp_ac_get_expense_form_types', $form_types );
}

function erp_ac_get_sales_form_types() {
    $form_types = [

    ];

    return apply_filters( 'erp_ac_get_sales_form_types', $form_types );
}