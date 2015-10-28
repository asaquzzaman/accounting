<div class="wrap erp-accounting chart-of-accounts">

    <h2><?php _e( 'Chart of Accounts', 'erp-accounting' ); ?> <a href="<?php echo admin_url( 'admin.php?page=erp-accounting-charts&action=new' ); ?>" class="add-new-h2"><?php _e( 'Add New', 'erp-accounting' ); ?></a></h2>

    <?php
    if ( isset( $_GET['msg'] ) ) {
        switch ( $_GET['msg'] ) {
            case 'update':
                erp_html_show_notice( __( 'Account has been updated!', 'erp-accounting' ) );
                break;

            case 'new':
                erp_html_show_notice( __( 'New account has been added!', 'erp-accounting' ) );
                break;
        }
    }

    $charts     = [];
    $all_charts = erp_ac_get_all_chart( [ 'number' => 200 ]);

    foreach ($all_charts as $chart) {
        $charts[ $chart->class_id ][] = $chart;
    }

    erp_ac_chart_print_table( __( 'Assets', 'erp-accounting' ), $charts['1'] );
    erp_ac_chart_print_table( __( 'Liabilities', 'erp-accounting' ), $charts['2'] );
    erp_ac_chart_print_table( __( 'Expenses', 'erp-accounting' ), $charts['3'] );
    erp_ac_chart_print_table( __( 'Income', 'erp-accounting' ), $charts['4'] );
    erp_ac_chart_print_table( __( 'Equity', 'erp-accounting' ), $charts['5'] );
    ?>
</div>