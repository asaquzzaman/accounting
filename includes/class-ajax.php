<?php
namespace WeDevs\ERP\Accounting;

use WeDevs\ERP\Framework\Traits\Ajax;
use WeDevs\ERP\Framework\Traits\Hooker;

/**
 * Ajax Class
 *
 * @package WP-ERP
 * @subpackage Accounting
 */
class Ajax_Handler {

    use Ajax;
    use Hooker;

    function __construct() {
        $this->action( 'wp_ajax_erp_ac_ledger_check_code', 'check_ledger_code' );
        $this->action( 'wp_ajax_erp_ac_payment_receive', 'receive_payment' );
    }

    function receive_payment() {
        $this->verify_nonce( 'erp-ac-nonce' );
        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : false;
        $account_id = isset( $_POST['account_id'] ) ? intval( $_POST['account_id'] ) : false;

        if ( ! $user_id ) {
            $this->send_error();
        } 

        $transaction = new \WeDevs\ERP\Accounting\Model\Transaction();
        $today       = current_time( 'mysql' );
        // $transaction_res   = $transaction->with(['items', 'journals' => function($q) {
        //     $q->where( 'type', '=', 'line_item' );
        // }])
        $transaction_res   = $transaction->where( function( $condition ) use( $today, $user_id ) {
            $condition->where( 'due_date', '<', $today );
            $condition->where( 'form_type', '=', 'invoice' );
            $condition->where( 'status', '=', 'awaiting_payment' );
            $condition->where( 'user_id', '=', $user_id );
            $condition->where( 'parent', '=', 0 );
        } );

        $results = $transaction_res->get()->toArray();
        $total_due = 0;
        ob_start();
        ?>
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
           <input type="number" name="line_total[]" class="line_total erp-ac-line-due" value="<?php echo esc_attr( $result['due'] ); ?>" min="0">
            <input type="hidden" name="transaction_id[]" value="<?php echo esc_attr( $result['id'] ); ?>">
            <input type="hidden" name="line_account[]" value="1">
            <!-- <input type="hidden" name="invoice_payment" value="1"> -->    
            <input type="hidden" value="" readonly="" name="line_desc[]">
            <input type="hidden" value="1" readonly="" name="line_qty[]">
            <input type="hidden" value="0" readonly="" name="line_unit_price[]">
            <input type="hidden" value="0" readonly="" name="line_discount[]">         
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
            <input class="erp-ac-total-due" type="number" value="<?php echo $total_due; ?>" readonly="" name="price_total">
        </th>
        </tr>
    </tfoot>
</table>

        <?php    
    $this->send_success( ob_get_clean() );
    }

    public function check_ledger_code() {
        $this->verify_nonce( 'erp-ac-nonce' );

        $code = isset( $_POST['code'] ) ? intval( $_POST['code'] ) : '';

        if ( Model\Ledger::code( $code )->get()->first() === null ) {
            $this->send_success();
        }

        $this->send_error();
    }
}