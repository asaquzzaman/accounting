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