<?php
namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Expense_Transaction_List_Table extends Transaction_List_Table {

    function __construct() {
        parent::__construct();

        $this->type = 'expense';
        $this->slug = 'erp-accounting-expense';
    }
}