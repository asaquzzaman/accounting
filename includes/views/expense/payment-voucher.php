<div class="wrap erp-ac-form-wrap">
    <h2><?php _e( 'Payment Voucher', '$domain' ); ?></h2>

    <?php $dropdown = erp_ac_get_chart_dropdown([
        'exclude'  => [1, 4, 5],
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
                            'label'       => __( 'Supplier', 'erp-accounting' ),
                            'name'        => 'customer',
                            'placeholder' => __( 'Select a payee', 'erp-accounting' ),
                            'type'        => 'select',
                            'class'       => 'select2',
                            'options'     => [ '' => __( '&mdash; Select &mdash;', 'erp-accounting' ) ] + erp_ac_get_all_customer_array( ['type' => 'vendor', 'number' => 100 ] )
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

        <table class="widefat erp-ac-transaction-table payment-voucher-table" style="margin: 20px 0;">
            <thead>
                <tr>
                    <th class="col-ac"><?php _e( 'Account', '$domain' ); ?></th>
                    <th class="col-desc"><?php _e( 'Description', '$domain' ); ?></th>
                    <th class="col-qty"><?php _e( 'Qty', '$domain' ); ?></th>
                    <th class="col-unit-price"><?php _e( 'Unit Price', '$domain' ); ?></th>
                    <th class="col-discount"><?php _e( 'Discount', '$domain' ); ?></th>
                    <th class="col-amount"><?php _e( 'Amount', '$domain' ); ?></th>
                    <th class="col-action">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < 2; $i++) { ?>
                <tr>
                    <td class="col-ac">
                        <?php echo $dropdown; ?>
                    </td>
                    <td class="col-desc">
                        <?php
                        erp_html_form_input( array(
                            'name' => 'line_desc[]',
                            'type' => 'text',
                        ) );
                        ?>
                    </td>
                    <td class="col-qty">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_qty[]',
                            'type'        => 'number',
                            'placeholder' => 1,
                            'value'       => 1,
                            'class'       => 'line_qty'
                        ) );
                        ?>
                    </td>
                    <td class="col-unit-price">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_unit_price[]',
                            'type'        => 'number',
                            'placeholder' => '0.00',
                            'class'       => 'line_price'
                        ) );
                        ?>
                    </td>
                    <td class="col-discount">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_discount[]',
                            'type'        => 'number',
                            'placeholder' => '0',
                            'addon'       => '%',
                            'addon_pos'   => 'after',
                            'class'       => 'line_dis'
                        ) );
                        ?>
                    </td>
                    <td class="col-amount">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_total[]',
                            'type'        => 'number',
                            'class'       => 'line_total',
                            'custom_attr' => [
                                'readonly' => 'readonly'
                            ]
                        ) );
                        ?>
                    </td>
                    <td class="col-action">
                        <a href="#" class="remove-line"><span class="dashicons dashicons-trash"></span></a>
                        <a href="#" class="move-line"><span class="dashicons dashicons-menu"></span></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th><a href="#" class="button add-line"><?php _e( '+ Add Line', 'erp-accounting' ); ?></a></th>
                    <th colspan="4" class="align-right"><?php _e( 'Total', 'erp-accounting' ); ?></th>
                    <th class="col-amount">
                        <input type="number" name="price_total" class="price-total" readonly value="0.00">
                    </th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>
        </table>

        <ul class="form-fields erp-list no-style">

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label'       => __( 'Memo', 'erp-accounting' ),
                    'name'        => 'summary',
                    'placeholder' => __( 'Internal information', 'erp-accounting' ),
                    'type'        => 'textarea',
                    'custom_attr' => [
                        'rows' => 3,
                        'cols' => 45
                    ]
                ) );
                ?>
            </li>

            <li class="erp-form-field erp-ac-attachment-field">
                <label for="attachments"><?php _e( 'Attachments', '$domain' ); ?></label>

                <div class="erp-ac-attachment-wrap">
                    <div class="erp-ac-upload-filelist"></div>
                    To attach, <a id="erp-ac-upload-pickfiles" href="#">select files</a> from your computer.
                </div>
            </li>
        </ul>

        <input type="hidden" name="field_id" value="0">
        <input type="hidden" name="type" value="expense">
        <input type="hidden" name="form_type" value="payment_voucher">

        <?php wp_nonce_field( 'erp-ac-trans-new' ); ?>
        <?php submit_button( __( 'Create Voucher', 'erp-accounting' ), 'primary', 'submit_erp_ac_trans' ); ?>
    </form>

</div>