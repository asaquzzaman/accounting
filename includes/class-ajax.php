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
        $this->action( 'wp_ajax_ac_transfer_money', 'transfer_money' );
    }

    function transfer_money() {
        $this->verify_nonce( 'erp-ac-nonce' );
        $from   = intval( $_POST['form_account_id'] );
        $to     = intval( $_POST['to_account_id'] ); 
        $amount = floatval( $_POST['amount'] );

        $debit_credit = erp_ac_bank_credit_total_amount( $from ); 
        $ledger_amount = abs( $debit_credit['debit'] - $debit_credit['credit'] );
        
        if ( $ledger_amount < $to ) {
            $this->send_error( __( 'No enough money from your transfer account', 'wp-account' ) );
        }


        $args = array(
            'type'            => 'transfer',
            'form_type'       => 'bank',
            'status'          => 'closed',
            'account_id'      => $from,
            'user_id'         => get_current_user_id(),
            'billing_address' => '',
            'ref'             => '',
            'issue_date'      => $_POST['date'],
            'summary'         => sanitize_text_field( $_POST['memo'] ),
            'total'           => $amount,
            'currency'        => erp_ac_get_currency(),
            'created_by'      => get_current_user_id(),
            'created_at'      => current_time( 'mysql' )

        );

        $items[] = array(
            'account_id' => $to,
            'type'      => 'line_item',
            'line_total'    => $amount,
            'description' => '',
            'qty'        => 1,
            'unit_price' => $amount,
            'discount'   => 0
        );
        
        $transaction_id = erp_ac_insert_transaction( $args, $items );

        if ( $transaction_id ) {
            $this->send_success();
        }

        $this->send_error();
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

        $transaction_res   = $transaction->where( function( $condition ) use( $today, $user_id ) {
            $condition->where( 'due_date', '<', $today );
            $condition->where( 'form_type', '=', 'invoice' );
            $condition->where( 'status', '=', 'awaiting_payment' );
            $condition->where( 'user_id', '=', $user_id );
            $condition->where( 'parent', '=', 0 );
        } );

        $results = $transaction_res->get()->toArray();

        if ( ! $results ) {
            $this->send_error();
        } 
        
        $total_due = 0;
        ob_start();
        include_once WPERP_ACCOUNTING_VIEWS . '/bank-ransfer-form.php';  
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