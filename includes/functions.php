<?php

function erp_ac_get_charts() {
    $raw = [
        1 => [],
        2 => [],
        3 => [],
        4 => [],
        5 => [],
    ];

    $accounts = \WeDevs\ERP\Accounting\Model\Chart_Of_Accounts::all()->toArray();

    // group by account_type_id
    foreach ($accounts as $ac) {
        $raw[ $ac['account_type_id'] ][] = $ac;
    }

    return $raw;
}

function erp_ac_get_chart_dropdown( $chart_id = null, $args = [] ) {
    $walker       = new WeDevs\ERP\Accounting\Walker_Charts();
    $dropdown     = '<select name="chart-of-accounts" id="">';
    $raw          = erp_ac_get_charts();
    $defaults     = [
        'select_text' => __( '&#8212; Select Account &#8212;', 'textdomain' ),
        'selected'    => -1
    ];
    $args = wp_parse_args( $args, $defaults );
    $account_charts = [
        1 => [
            'label' => __( 'Assets', 'textdomain' ),
            'options' => ''
        ],
        2 => [
            'label' => __( 'Liabilities', 'textdomain' ),
            'options' => ''
        ],
        3 => [
            'label' => __( 'Expenses', 'textdomain' ),
            'options' => ''
        ],
        4 => [
            'label' => __( 'Income', 'textdomain' ),
            'options' => ''
        ],
        5 => [
            'label' => __( 'Equity', 'textdomain' ),
            'options' => ''
        ],
    ];

    // build tree on each account type
    foreach ($raw as $id => $charts) {
        $account_charts[ $id ]['options'] = $walker->walk( erp_array_to_object( $charts ), 3, $args );
    }

    if ( $chart_id && array_key_exists( $chart_id, $account_charts ) ) {
        $account_charts = [ $account_charts[ $chart_id ] ];
    }

    $dropdown .= '<option value="-1"' . selected( $args['selected'], '-1', false ) . '>' . $args['select_text'] . '</option>';

    if ( $account_charts ) {
        foreach( $account_charts as $chart ) {
            $dropdown .= '<optgroup label="' . $chart['label'] . '">';
            $dropdown .= $chart['options'];
            $dropdown .= '</optgroup>';
        }
    }

    $dropdown .= '</select>';

    return $dropdown;
}
