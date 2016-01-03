<?php
$account = isset( $_GET['receive_payment'] ) && $_GET['receive_payment'] == 'true' ? true : false;
$account_id = $account && isset( $_GET['bank'] ) ? intval( $_GET['bank'] ) : false;
$customer_class = $account_id ? 'erp-ac-payment-receive' : '';
?>
<div class="wrap erp-ac-form-wrap">
    <h2><?php _e( 'Receive Payment', '$domain' ); ?></h2>

    <?php
    $dropdown = erp_ac_get_chart_dropdown([
        'exclude'  => [1, 2, 3],
        'required' => true,
        'name'     => 'line_account[]'
    ] ); ?>

    <form action="" method="post" class="erp-form" style="margin-top: 30px;">

        <ul class="form-fields block" style="width:50%;">

            <li>
                <ul class="erp-form-fields two-col block">
                    <li class="erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label'       => __( 'Customer', 'erp-accounting' ),
                            'name'        => 'user_id',
                            'placeholder' => __( 'Select a payee', 'erp-accounting' ),
                            'type'        => 'select',
                            'class'       => 'select2 erp-ac-payment-receive',
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_get_peoples_array( ['type' => 'customer', 'number' => 100 ] )
                        ) );
                        ?>
                    </li>

                    <li class="erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label' => __( 'Reference', 'erp-accounting' ),
                            'name'  => 'ref',
                            'type'  => 'text',
                            'addon' => '#',
                        ) );
                        ?>
                    </li>
                </ul>
            </li>

            <li>
                <ul class="erp-form-fields two-col block clearfix">
                    <li class="erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label'       => __( 'Payment Date', 'erp-accounting' ),
                            'name'        => 'issue_date',
                            'placeholder' => date( 'Y-m-d' ),
                            'type'        => 'text',
                            'required'    => true,
                            'class'       => 'erp-date-field',
                        ) );
                        ?>
                    </li>

                    <li class="cols erp-form-field">
                        <?php
                        // if ( $account_id ) {
                        //     $bank = WeDevs\ERP\Accounting\Model\Ledger::bank()->find($account_id);
                        //     erp_html_form_input( array(
                        //         'name'  => 'account_id',
                        //         'type'  => 'hidden',
                        //         'value' => $account_id,
                        //     ) );
                        //     erp_html_form_input( array(
                        //         'label' => __( 'Deposit To', 'erp-accounting' ),
                        //         'type'  => 'text',
                        //         'custom_attr' => array( 'disabled' => 'disabled' ),
                        //         'value' => $bank->name,
                        //     ) );
                        // } else {
                            erp_html_form_input( array(
                                'label'       => __( 'Deposit To', 'erp-accounting' ),
                                'name'        => 'account_id',
                                'placeholder' => __( 'Select an Account', 'erp-accounting' ),
                                'type'        => 'select',
                                'class'       => 'select2 erp-ac-deposit-dropdown',
                                'value'    => $account_id ? $account_id : '',
                                'required'    => true,
                                'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_ac_get_bank_dropdown()
                            ) );    
                        // }
                        
                        ?>
                    </li>
                </ul>
            </li>

        </ul>

        
        <div class="erp-ac-receive-payment-table"><?php include dirname( dirname( __FILE__ ) ) . '/common/transaction-table.php';?></div>
            
        <?php include dirname( dirname( __FILE__ ) ) . '/common/memo.php'; ?>

        <input type="hidden" name="field_id" value="0">
        <input type="hidden" name="status" value="closed">
        <input type="hidden" name="type" value="sales">
        <input type="hidden" name="form_type" value="payment">
        <input type="hidden" name="page" value="erp-accounting-sales">
        <input type="hidden" name="erp-action" value="ac-new-sales-payment">

        <?php wp_nonce_field( 'erp-ac-trans-new' ); ?>
        <?php submit_button( __( 'Receive Payment', 'erp-accounting' ), 'primary', 'submit_erp_ac_trans' ); ?>
    </form>
    <div class="erp-ac-receive-payment-table-clone" style="display: none;">
        <?php 

    $dropdown = erp_ac_get_chart_dropdown([
        'exclude'  => [1, 2, 3],
        'required' => true,
        'name'     => 'line_account[]',
        'class'    => 'erp-ac-selece-custom'
    ] );
        include dirname( dirname( __FILE__ ) ) . '/common/transaction-table.php';?>
    </div>
</div>