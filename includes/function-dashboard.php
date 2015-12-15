<?php
function erp_ac_db_get_overdue_income() {
	// $classes   = erp_ac_get_chart_classes();
	// $income_id = false;

	// foreach ( $classes as $key => $label ) {
	// 	if ( strtolower( $label ) == 'income' ) {
	// 		$income_id = intval( $key );
	// 	}
	// }

	// if ( ! $income_id ) {
	// 	return array();
	// }

	// $get_chart_types = erp_ac_get_chart_types_by_class_id( $income_id );

	global $wpdb;

	$transaction = new \WeDevs\ERP\Accounting\Model\Transaction();
	$db          = new \WeDevs\ORM\Eloquent\Database();
	$today       = current_time( 'mysql' );

	$transaction_res = $transaction->select( array( $db->raw('SUM(due) as due_sum') ) );
	$transaction_res = $transaction_res->where( function( $condition ) use( $today ) {
        $condition->where( 'due_date', '<', $today );
        $condition->where( 'form_type', '=', 'invoice' );
        $condition->where( 'status', '=', 'awaiting_payment' );
    } );

    $due = $transaction_res->pluck('due_sum');
    return intval( $due );
}

function erp_ac_get_open_invoice() {

	global $wpdb;

	$transaction = new \WeDevs\ERP\Accounting\Model\Transaction();
	$db          = new \WeDevs\ORM\Eloquent\Database();

	$transaction_res = $transaction->select( array( $db->raw('SUM(due) as due_sum') ) );
	$transaction_res = $transaction_res->where( function( $condition ) {
        $condition->where( 'form_type', '=', 'invoice' );
        $condition->where( 'status', '=', 'awaiting_payment' );
    } );

    $due = $transaction_res->pluck('due_sum');
    return intval( $due );
}

function erp_ac_get_paid_last_30_days() {
	$transaction = new \WeDevs\ERP\Accounting\Model\Transaction();
	$db          = new \WeDevs\ORM\Eloquent\Database();
	$last_date   = current_time( 'mysql' );
	$first_date  = date( 'Y-m-d', strtotime( '-30 day', strtotime( $last_date ) ) );
	
	$transaction_res = $transaction->select( array( $db->raw('SUM(trans_total) as trans_total') ) );
	$transaction_res = $transaction_res->where( function( $condition ) use( $first_date, $last_date ) {
		$condition->where( 'issue_date', '>', $first_date );
        $condition->where( 'form_type', '=', 'payment' );
        $condition->where( 'status', '=', 'closed' );
    } );
    $due = $transaction_res->pluck('trans_total');
    return intval( $due );
}

function erp_ac_get_expenses_last_30_days() {
	$transaction = new \WeDevs\ERP\Accounting\Model\Transaction();
	$journal     = new \WeDevs\ERP\Accounting\Model\Journal();
	$db          = new \WeDevs\ORM\Eloquent\Database();
	$last_date   = current_time( 'mysql' );
	$first_date  = date( 'Y-m-d', strtotime( '-30 day', strtotime( $last_date ) ) );
	
	$transaction_res = $transaction->select( array( $db->raw('SUM(trans_total) as trans_total') ) );
	$transaction_res = $transaction_res->where( function( $condition ) use( $first_date, $last_date ) {
		$condition->where( 'issue_date', '>', $first_date );
        $condition->where( 'form_type', '=', 'payment_voucher' );
        $condition->where( 'status', '=', 'paid' );
    } );
    $due = $transaction_res->pluck('trans_total');
    return intval( $due );
}

function erp_ac_get_expense_plot() {
	global $wpdb;
	
	$journal_table     = $wpdb->prefix . 'erp_ac_journals';
	$transaction_table = $wpdb->prefix . 'erp_ac_transaction';
	$transaction       = new \WeDevs\ERP\Accounting\Model\Transaction();
	$last_date         = current_time( 'mysql' );
	$first_date        = date( 'Y-m-d', strtotime( '-30 day', strtotime( $last_date ) ) );
	$transaction_res   = $transaction->with( ['journals' => function($q){
			$q->where( 'type', '=', 'main' );
	}]);

	$transaction_res = $transaction_res->where( function( $condition ) use( $first_date, $last_date ) {
		$condition->where( 'issue_date', '>', $first_date );
        $condition->where( 'form_type', '=', 'payment_voucher' );
        $condition->where( 'status', '=', 'paid' );
    } );

    $results = $transaction_res->get()->toArray();
    
    return $results;
}



