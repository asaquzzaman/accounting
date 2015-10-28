<?php
namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Sales_Transaction_List_Table extends Transaction_List_Table {

    function __construct() {
        parent::__construct();

        $this->type = 'sales';
        $this->slug = 'erp-accounting-sales';
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'         => '<input type="checkbox" />',
            'issue_date' => __( 'Date', 'erp-accounting' ),
            'form_type'  => __( 'Type', 'erp-accounting' ),
            'ref'        => __( 'Ref', 'erp-accounting' ),
            'user_id'    => __( 'Customer', 'erp-accounting' ),
            'due_date'   => __( 'Due Date', 'erp-accounting' ),
            'total'      => __( 'Total', 'erp-accounting' ),
            'status'     => __( 'Status', 'erp-accounting' ),
        );

        return $columns;
    }

    /**
     * Get form types
     *
     * @return array
     */
    public function get_form_types() {
        return erp_ac_get_sales_form_types();
    }

    public function column_form_type( $item ) {
        $types = erp_ac_get_sales_form_types();

        if ( array_key_exists( $item->form_type, $types ) ) {
            return sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $types[ $item->form_type ]['label'] );
        }
    }
}