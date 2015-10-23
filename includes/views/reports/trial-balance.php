<?php
global $wpdb;

$sql = "SELECT led.id, led.code, led.name, led.type_id, types.name as type_name, types.class_id, class.name as class_name, sum(jour.debit) as debit, sum(jour.credit) as credit
FROM wp_erp_ac_ledger as led
LEFT JOIN wp_erp_ac_chart_types as types ON types.id = led.type_id
LEFT JOIN wp_erp_ac_chart_classes as class ON class.id = types.class_id
LEFT JOIN wp_erp_ac_journals as jour ON jour.ledger_id = led.id
GROUP BY led.id";

$ledgers = $wpdb->get_results( $sql );
$charts  = [];

if ( $ledgers ) {
    foreach ($ledgers as $ledger) {

        if ( ! isset( $charts[ $ledger->class_id ] ) ) {
            $charts[ $ledger->class_id ]['label'] = $ledger->class_name;
            $charts[ $ledger->class_id ]['ledgers'][] = $ledger;
        } else {
            $charts[ $ledger->class_id ]['ledgers'][] = $ledger;
        }
    }
}

// echo "<pre>";
// print_r( $charts );
// echo "</pre>";
$debit_total = 0.00;
$credit_total = 0.00;
?>

<div class="wrap">
    <h2><?php _e( 'Trial Balance', 'wp-erp' ); ?></h2>

    <table class="table widefat striped">
        <thead>
            <tr>
                <th><?php _e( 'Account Name', 'erp-accounting' ); ?></th>
                <th class="col-price"><?php _e( 'Debit Total', 'erp-accounting' ); ?></th>
                <th class="col-price"><?php _e( 'Credit Total', 'erp-accounting' ); ?></th>
            </tr>
        </thead>

        <tbody>
            <?php if ( $charts ) { ?>

                <?php foreach ($charts as $class) { ?>
                    <tr class="chart-head">
                        <td colspan="3"><strong><?php echo $class['label'] ?></strong></td>
                    </tr>

                    <?php foreach ($class['ledgers'] as $ledger) {
                        $debit        = floatval( $ledger->debit );
                        $credit       = floatval( $ledger->credit );

                        $debit_total  += $debit;
                        $credit_total += $credit;
                        ?>
                        <tr>
                            <td>
                                <a href="#"><?php printf( '&nbsp; &nbsp; &nbsp;%s (%s)', $ledger->name, $ledger->code ); ?></a>
                            </td>
                            <td class="col-price"><?php echo number_format( $ledger->debit, 2 ); ?></td>
                            <td class="col-price"><?php echo number_format( $ledger->credit, 2 ); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>

            <?php } else { ?>

            <?php } ?>
        </tbody>

        <tfoot>
            <tr>
                <th><?php _e( 'Total', 'erp-accounting' ); ?></th>
                <th class="col-price"><?php echo number_format( $debit_total, 2 ); ?></th>
                <th class="col-price"><?php echo number_format( $credit_total, 2 ); ?></th>
            </tr>
        </tfoot>
    </table>

</div>

<style>
    td.col-price,
    th.col-price {
        text-align: right;
    }
</style>