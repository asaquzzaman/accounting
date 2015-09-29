<?php

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
        $raw[ $ac['account_type_id'] ][] = $ac;
    }

    return $raw;
}

function erp_ac_get_chart_dropdown( $args = [] ) {
    $walker       = new WeDevs\ERP\Accounting\Walker_Charts();
    $raw          = erp_ac_get_charts();
    $defaults     = [
        'select_text' => __( '&#8212; Select Account &#8212;', 'textdomain' ),
        'selected'    => -1,
        'name'        => 'chart-of-accounts',
        'exclude'     => false,
        'class'       => 'select2'
    ];
    $args         = wp_parse_args( $args, $defaults );
    $dropdown     = sprintf( '<select name="%1$s" id="%1$s" class="%2$s">', $args['name'], $args['class'] );
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

    // exclude charts
    if ( is_array( $args['exclude'] ) && $args['exclude'] ) {
        foreach ($args['exclude'] as $chart_id) {
            if ( array_key_exists( $chart_id, $account_charts ) ) {
                unset( $account_charts[ $chart_id ] );
            }
        }
    }

    // build tree on each account type
    foreach ($raw as $id => $charts) {
        if ( isset( $account_charts[ $id ] ) ) {
            $account_charts[ $id ]['options'] = $walker->walk( erp_array_to_object( $charts ), 3, $args );
        }
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
