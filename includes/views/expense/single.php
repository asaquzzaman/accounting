<?php

// var_dump( $transaction->toArray() );
// $items = $transaction->items->toArray();
// var_dump( $items );
?>
<div class="wrap">

    <div class="transaction-to">
        <ul>
            <li><?php _e( 'Billing Address', 'erp-accounting' ); ?>: <?php echo $transaction->billing_address; ?></li>
            <li><?php _e( 'Issue Date', 'erp-accounting' ); ?>: <?php echo $transaction->issue_date; ?></li>
            <li><?php _e( 'Summary', 'erp-accounting' ); ?>: <?php echo $transaction->summary; ?></li>
        </ul>
    </div>

    <table class="widefat striped">
        <thead>
            <tr>
                <th><?php _e( 'Account', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Product', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Description', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Qty', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Unit Price', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Discount', 'erp-accounting' ); ?></th>
                <th><?php _e( 'Amount', 'erp-accounting' ); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach( $transaction->items as $item ) { ?>
                <tr>
                    <td><?php echo $item->journal->ledger->name; ?></td>
                    <td><?php echo $item->product_id; ?></td>
                    <td><?php echo esc_html( $item->description ); ?></td>
                    <td><?php echo $item->qty; ?></td>
                    <td><?php echo $item->unit_price; ?></td>
                    <td><?php echo $item->discount; ?></td>
                    <td><?php echo $item->line_total; ?></td>
                </tr>
            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <th colspan="6" class="align-right"><?php _e( 'Total', 'erp-accounting' ); ?></th>
                <th><strong><?php echo $transaction->total; ?></strong></th>
            </tr>
        </tfoot>
    </table>
</div>