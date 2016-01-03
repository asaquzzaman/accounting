<?php
$account = isset( $_GET['spend_money'] ) && $_GET['spend_money'] == 'true' ? true : false;
$account_id = $account && isset( $_GET['bank'] ) ? intval( $_GET['bank'] ) : false;
?>
<div class="wrap erp-ac-form-wrap">
    <h2><?php _e( 'Payment Voucher', 'erp-accounting' ); ?></h2>

    <?php
    $selected_account_id = isset( $_GET['account_id'] ) ? intval( $_GET['account_id'] ) : 0;
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

                    <li class="cols erp-form-field">
                        <?php
                        erp_html_form_input( array(
                            'label'       => __( 'From Account', 'erp-accounting' ),
                            'name'        => 'account_id',
                            'placeholder' => __( 'Select an Account', 'erp-accounting' ),
                            'type'        => 'select',
                            'class'       => 'select2',
                            'value'       => $account_id ? $account_id : $selected_account_id,
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_ac_get_bank_dropdown()
                        ) );
                        ?>

                        <span class="balance-wrap">
                            <strong><?php _e( 'Balance:', 'erp-accounting' ); ?></strong>
                        </span>
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
                            'required' => true,
                            'class'       => 'erp-date-field',
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

        </ul>

        <?php include dirname( dirname( __FILE__ ) ) . '/common/transaction-table.php'; ?>
        <?php include dirname( dirname( __FILE__ ) ) . '/common/memo.php'; ?>

        <input type="hidden" name="field_id" value="0">
        <input type="hidden" name="type" value="expense">
        <input type="hidden" name="status" value="paid">
        <input type="hidden" name="form_type" value="payment_voucher">
        <input type="hidden" name="page" value="erp-accounting-expense">
        <input type="hidden" name="erp-action" value="ac-new-payment-voucher">

        <?php wp_nonce_field( 'erp-ac-trans-new' ); ?>
        <?php submit_button( __( 'Create Voucher', 'erp-accounting' ), 'primary', 'submit_erp_ac_trans' ); ?>
    </form>

</div>