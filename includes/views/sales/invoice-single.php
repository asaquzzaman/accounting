<?php
$company = new \WeDevs\ERP\Company();
$user    = new \WeDevs\ERP\People( intval( $transaction->user_id ) );
// var_dump( $transaction->items->toArray() );
// var_dump( $user );
?>
<div class="wrap">

    <h2><?php _e( 'Invoice', 'erp-accounting' ); ?></h2>

    <div class="invoice-preview-wrap">

        <div class="erp-grid-container">
            <div class="row invoice-buttons">
                <div class="col-6">
                    <a href="#" class="button button-primary button-large add-invoice-payment"><?php _e( 'Add Payment', 'erp-accounting' ); ?></a>
                    <a href="#" class="button button-large"><?php _e( 'Edit Invoice', 'erp-accounting' ); ?></a>
                    <a href="#" class="button button-large print-invoice"><?php _e( 'Print', 'erp-accounting' ); ?></a>
                </div>
            </div>
            <div class="row">
                <div class="invoice-number">
                    <?php printf( __( 'Invoice: <strong>%d</strong>', 'erp-accounting' ), $transaction->id ); ?>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    <div class="col-3 company-logo">
                        <?php echo $company->get_logo(); ?>
                    </div>

                    <div class="col-3 align-right">
                        <strong><?php echo $company->name ?></strong>
                        <div><?php echo $company->get_formatted_address(); ?></div>
                    </div>
                </div><!-- .row -->
            </div><!-- .page-header -->

            <hr>

            <div class="row">
                <div class="col-3">
                    <div class="bill-to"><?php _e( 'Bill to:', 'erp-accounting' ); ?></div>
                    <strong><?php echo $user->get_full_name(); ?></strong>
                    <div class="billing-address"><?php echo nl2br( $transaction->billing_address ); ?></div>
                </div>
                <div class="col-3 align-right">
                    <table class="table info-table">
                        <tbody>
                            <tr>
                                <th><?php _e( 'Invoice Number', 'erp-accounting' ); ?>:</th>
                                <td>31</td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Invoice Date', 'erp-accounting' ); ?>:</th>
                                <td><?php echo erp_ac_format_date( $transaction->issue_date ); ?></td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Due Date', 'erp-accounting' ); ?>:</th>
                                <td><?php echo erp_ac_format_date( $transaction->due_date ); ?></td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Amount Due', 'erp-accounting' ); ?>:</th>
                                <td><?php echo $transaction->due; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- .row -->

            <hr>

            <div class="row align-right">
                <table class="table fixed striped">
                    <thead>
                        <tr>
                            <th class="align-left product-name"><?php _e( 'Product', 'erp-accounting' ) ?></th>
                            <th><?php _e( 'Quantity', 'erp-accounting' ) ?></th>
                            <th><?php _e( 'Unit Price', 'erp-accounting' ) ?></th>
                            <th><?php _e( 'Discount', 'erp-accounting' ) ?></th>
                            <th><?php _e( 'Amount', 'erp-accounting' ) ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ( $transaction->items as $line ) { ?>
                            <tr>
                                <td class="align-left product-name">
                                    <strong><?php echo $line->journal->ledger->name; ?></strong>
                                    <div class="product-desc"><?php echo $line->description; ?></div>
                                </td>
                                <td><?php echo $line->qty; ?></td>
                                <td><?php echo $line->unit_price; ?></td>
                                <td><?php echo $line->discount; ?></td>
                                <td><?php echo $line->line_total; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div><!-- .row -->

            <div class="row">
                <div class="col-3">
                    <?php echo $transaction->summary; ?>
                </div>
                <div class="col-3">
                    <table class="table info-table align-right">
                        <tbody>
                            <tr>
                                <th><?php _e( 'Total', 'erp-accounting' ); ?></th>
                                <td><?php echo $transaction->total; ?></td>
                            </tr>
                            <tr>
                                <th><?php _e( 'Total Paid', 'erp-accounting' ); ?></th>
                                <td>
                                    <?php
                                    $total_paid = floatval( $transaction->total ) - floatval( $transaction->due );
                                    echo number_format_i18n( $total_paid, 2 );
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- .erp-grid-container -->
    </div>

</div>