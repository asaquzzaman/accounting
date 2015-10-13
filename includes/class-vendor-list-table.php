<?php

namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Vendor_List_Table extends Customer_List_Table {

    function __construct() {

        parent::__construct( array(
            'singular' => 'vendor',
            'plural'   => 'vendors',
            'ajax'     => false
        ) );

        $this->slug = 'erp-accounting-vendors';
        $this->type = 'vendor';
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'No vendor found!', 'wp-erp-ac' );
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'customer'     => __( 'Vendor', 'wp-erp-ac' ),
            'company'      => __( 'Company', 'wp-erp-ac' ),
            'email'        => __( 'Email', 'wp-erp-ac' ),
            'phone'        => __( 'Phone', 'wp-erp-ac' ),
            'open_balance' => __( 'Open Balance', 'wp-erp-ac' ),
        );

        return $columns;
    }

    /**
     * Render the designation name column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_customer( $item ) {

        $actions            = array();
        $actions['edit']    = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=edit&id=' . $item->id ), $item->id, __( 'Edit this item', 'wp-erp-ac' ), __( 'Edit', 'wp-erp-ac' ) );
        $actions['invoice'] = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=edit&id=' . $item->id ), $item->id, __( 'Create Bill', 'wp-erp-ac' ), __( 'Create Bill', 'wp-erp-ac' ) );
        $actions['expense'] = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=edit&id=' . $item->id ), $item->id, __( 'Create Expense', 'wp-erp-ac' ), __( 'Create Expense', 'wp-erp-ac' ) );
        $actions['delete']  = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'wp-erp-ac' ), __( 'Delete', 'wp-erp-ac' ) );

        return get_avatar( $item->email, 32 ) . sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $item->first_name . ' ' . $item->last_name, $this->row_actions( $actions ) );
    }

}