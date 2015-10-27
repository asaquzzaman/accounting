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