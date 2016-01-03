<table class="widefat erp-ac-transaction-table payment-voucher-table" style="margin: 20px 0;">
    <thead>
        <tr>
            <th class="col-ac"><?php _e( 'Invoice ID', 'erp-accounting' ); ?></th>
            <th class="col-ac"><?php _e( 'Due', 'erp-accounting' ); ?></th>
            <th class="col-ac"><?php _e( 'Total', 'erp-accounting' ); ?></th>
            <th class="col-desc"><?php _e( 'Amount', 'erp-accounting' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $results as $key => $result  ) { //var_dump( $result ); die();?>
        <tr>
            <td class="col-ac">
                <?php echo '#' . $result['id']; ?>
            </td>
            <td>
            <?php echo $result['due']; ?>
            </td>
            <td>
            <?php echo $result['trans_total']; ?>
            </td>
            <td class="col-desc col-amount">
                <?php erp_html_form_input( array(
                    'type'        => 'number',
                    'name'        => 'line_total[]',
                    'class'       => 'line_total erp-ac-line-due',
                    'value'       => esc_attr( $result['due'] ),
                    'custom_attr' => array( 'min' => '0' )
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'transaction_id[]',
                    'value'       => esc_attr( $result['id'] ),
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'line_account[]',
                    'value'       => 1,
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'line_desc[]',
                    'value'       => '',
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'line_qty[]',
                    'value'       => '1',
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'line_unit_price[]',
                    'value'       => '0',
                ) ); ?>

                <?php erp_html_form_input( array(
                    'type'        => 'hidden',
                    'name'        => 'line_discount[]',
                    'value'       => '0',
                ) ); ?>        
            </td>
        </tr>
        <?php 
        $total_due = $result['due'] + $total_due; 
        } ?>
    </tbody>
    <tfoot>
        <tr>
            <th>&nbsp;</th>
            <th class="align-right"></th>
            <th class="col-amount"><?php _e( 'Total', 'erp-accounting' ); ?></th>
            <th class="erp-ac-total-due col-amount">

            <?php erp_html_form_input( array(
                'type'        => 'number',
                'name'        => 'price_total',
                'value'       => $total_due,
                'class'       => 'erp-ac-total-due',
                'custom_attr' => array( 'readonly' => '' )
            ) ); ?>
        </th>
        </tr>
    </tfoot>
</table>