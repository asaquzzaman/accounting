<div class="wrap">
    <h2><?php echo $customer->get_full_name(); ?> <a href="#" class="add-new-h2"><?php _e( 'Edit', 'erp-accounting' ); ?></a></h2>

    <?php
    // var_dump( $customer );
    $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'transactions';

    $trans = WeDevs\ERP\Accounting\Model\Transaction::OfUser( $customer->id )->with( 'items' )->get();

    if ( $trans ) {
        foreach ($trans as $transaction) {
            // var_dump( $transaction->items );
        }
    }

    // var_dump( $trans->toArray() );
    ?>

    <h2 class="nav-tab-wrapper erp-nav-tab-wrapper" style="margin: 20px 0;">
        <a class="nav-tab<?php echo $current_tab == 'transactions' ? ' nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=erp-accounting-customers&action=view&id=' . $id . '&tab=transactions' ); ?>"><?php _e( 'Transactions', 'erp-accounting' ); ?></a>
        <a class="nav-tab<?php echo $current_tab == 'details' ? ' nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=erp-accounting-customers&action=view&id=' . $id . '&tab=details' ); ?>"><?php _e( 'User Details', 'erp-accounting' ); ?></a>
    </h2>

    <?php
    if ( 'transactions' == $current_tab ) {

        include dirname( __FILE__ ) . '/user-transactions.php';

    } elseif ( 'details' == $current_tab ) {

        include dirname( __FILE__ ) . '/user-details.php';

    }
    ?>
</div>