<div class="wrap erp-ac-form-wrap">
    <h2><?php _e( 'Vendor Credit', 'erp-accounting' ); ?></h2>


    <?php
    $accounts_payable_id = WeDevs\ERP\Accounting\Model\Ledger::code('200')->first()->id;
    $dropdown = erp_ac_get_chart_dropdown([
        'exclude'  => [1, 4, 5],
        'required' => true,
        'name'     => 'line_account[]'
    ] );
    ?>

    <form action="" method="post" class="erp-form" style="margin-top: 30px;">

        <ul class="form-fields block" style="width:50%;">

            <li>
                <ul class="erp-form-fields two-col block">
                    <li class="erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label'       => __( 'Vendor', 'erp-accounting' ),
                            'name'        => 'user_id',
                            'type'        => 'select',
                            'class'       => 'select2',
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_get_peoples_array( ['type' => 'vendor', 'number' => 100 ] ),
                            'custom_attr' => [
                                'data-placeholder' => __( 'Select a payee', 'erp-accounting' )
                            ]
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
                            'label'       => __( 'Issue Date', 'erp-accounting' ),
                            'name'        => 'issue_date',
                            'placeholder' => date( 'Y-m-d' ),
                            'type'        => 'text',
                            'required' => true,
                            'class'       => 'erp-date-field',
                        ) );
                        ?>
                    </li>

                    <li class="erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label'       => __( 'Due Date', 'erp-accounting' ),
                            'name'        => 'due_date',
                            'placeholder' => date( 'Y-m-d' ),
                            'type'        => 'text',
                            'required'    => true,
                            'class'       => 'erp-date-field',
                        ) );
                        ?>
                    </li>
                </ul>
            </li>

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label'       => __( 'Billing Address', 'erp-accounting' ),
                    'name'        => 'billing_address',
                    'placeholder' => '',
                    'type'        => 'textarea',
                    'custom_attr' => [
                        'rows' => 3,
                        'cols' => 30
                    ],
                ) );
                ?>
            </li>

        </ul>

        <?php include dirname( dirname( __FILE__ ) ) . '/common/transaction-table.php'; ?>
        <?php include dirname( dirname( __FILE__ ) ) . '/common/memo.php'; ?>

        <input type="hidden" name="field_id" value="0">
        <input type="hidden" name="account_id" value="<?php echo $accounts_payable_id; ?>">
        <input type="hidden" name="status" value="awaiting_payment">
        <input type="hidden" name="type" value="expense">
        <input type="hidden" name="form_type" value="vendor_credit">
        <input type="hidden" name="page" value="erp-accounting-expense">
        <input type="hidden" name="erp-action" value="ac-new-payment-voucher">

        <?php wp_nonce_field( 'erp-ac-trans-new' ); ?>
        <?php submit_button( __( 'Create Vendor Credit', 'erp-accounting' ), 'primary', 'submit_erp_ac_trans' ); ?>
    </form>

</div>