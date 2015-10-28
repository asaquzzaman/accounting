<div class="wrap erp-ac-form-wrap">
    <h2><?php _e( 'Receive Payment', '$domain' ); ?></h2>

    <?php
    $accounts_receivable_id = WeDevs\ERP\Accounting\Model\Ledger::code('120')->first()->id;
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
                            'class'       => 'select2',
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_ac_get_all_customer_array( ['type' => 'customer', 'number' => 100 ] )
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
                        erp_html_form_input( array(
                            'label'       => __( 'Deposit To', 'erp-accounting' ),
                            'name'        => 'account_id',
                            'placeholder' => __( 'Select an Account', 'erp-accounting' ),
                            'type'        => 'select',
                            'class'       => 'select2',
                            'required'    => true,
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_ac_get_bank_dropdown()
                        ) );
                        ?>
                    </li>
                </ul>
            </li>

        </ul>

        <?php include dirname( dirname( __FILE__ ) ) . '/common/transaction-table.php'; ?>
        <?php include dirname( dirname( __FILE__ ) ) . '/common/memo.php'; ?>

        <input type="hidden" name="field_id" value="0">
        <input type="hidden" name="account_id" value="<?php echo $accounts_receivable_id; ?>">
        <input type="hidden" name="type" value="sales">
        <input type="hidden" name="form_type" value="payment">
        <input type="hidden" name="page" value="erp-accounting-sales">
        <input type="hidden" name="erp-action" value="ac-new-sales-payment">

        <?php wp_nonce_field( 'erp-ac-trans-new' ); ?>
        <?php submit_button( __( 'Receive Payment', 'erp-accounting' ), 'primary', 'submit_erp_ac_trans' ); ?>
    </form>
</div>