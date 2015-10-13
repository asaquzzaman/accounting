<div class="wrap">
    <h2><?php _e( 'Payment Voucher', '$domain' ); ?></h2>

    <?php $dropdown = erp_ac_get_chart_dropdown([ 'exclude' => [1, 5] ]); ?>

    <form action="" class="erp-form" style="margin-top: 30px;">

        <ul class="form-fields erp-list no-style">

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label'       => __( 'Supplier', 'wp-erp' ),
                    'name'        => 'payee',
                    'placeholder' => __( 'Select a payee', 'textdomain' ),
                    'type'        => 'select',
                    'class'       => 'select2',
                    'options' => [
                        '' => '&#8212;',
                        '12' => 'Sabbir Ahmed'
                    ]
                ) );
                ?>
            </li>

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label'       => __( 'Payment Date', 'wp-erp' ),
                    'name'        => 'payment_date',
                    'placeholder' => date( 'Y-m-d' ),
                    'type'        => 'text',
                    'class'       => 'erp-date-field',
                ) );
                ?>
            </li>

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label' => __( 'Reference', 'wp-erp' ),
                    'name'  => 'ref_no',
                    'type'  => 'text',
                    'addon' => '#',
                ) );
                ?>
            </li>
        </ul>

        <table class="widefat erp-ac-form-table" style="margin: 20px 0;">
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
                            'name' => 'erp_ac_desc[]',
                            'type' => 'text',
                        ) );
                        ?>
                    </td>
                    <td class="col-qty">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'erp_ac_qty[]',
                            'type'        => 'number',
                            'placeholder' => 1
                        ) );
                        ?>
                    </td>
                    <td class="col-unit-price">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'erp_ac_unit_price[]',
                            'type'        => 'number',
                            'placeholder' => '0.00',
                        ) );
                        ?>
                    </td>
                    <td class="col-discount">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'erp_ac_discount[]',
                            'type'        => 'number',
                            'placeholder' => '0',
                            'addon'       => '%',
                            'addon_pos'   => 'after'
                        ) );
                        ?>
                    </td>
                    <td class="col-amount">
                        <?php
                        erp_html_form_input( array(
                            'name' => 'erp_ac_amount[]',
                            'type' => 'number',
                            'custom_attr' => [
                                'readonly' => 'readonly'
                            ]
                        ) );
                        ?>
                    </td>
                    <td class="col-action">
                        <a href="#" class="remove-row"><span class="dashicons dashicons-trash"></span></a>
                        <a href="#" class="move-row"><span class="dashicons dashicons-menu"></span></a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="align-right"><?php _e( 'Total', '$domain' ); ?></th>
                    <th class="col-amount"><input type="number" name="total" readonly value="1000.00"></th>
                    <th>&nbsp;</th>
                </tr>
            </tfoot>
        </table>

        <ul class="form-fields erp-list no-style">

            <li class="erp-form-field">
                <?php
                erp_html_form_input( array(
                    'label'       => __( 'Memo', 'wp-erp' ),
                    'name'        => 'memo',
                    'placeholder' => __( 'Internal information', 'textdomain' ),
                    'type'        => 'textarea',
                    'custom_attr' => [
                        'rows' => 3,
                        'cols' => 45
                    ]
                ) );
                ?>
            </li>

            <li class="erp-form-field">
                <label for="attachments"><?php _e( 'Attachments', '$domain' ); ?></label>
            </li>
        </ul>

        <?php submit_button( __( 'Create Voucher', 'domain' ), 'primary', 'submit'  ); ?>
    </form>

</div>