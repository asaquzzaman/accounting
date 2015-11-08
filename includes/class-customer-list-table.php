<?php

namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Customer_List_Table extends \WP_List_Table {

    protected $slug = null;
    protected $type = null;

    function __construct() {

        $this->slug = 'erp-accounting-customers';
        $this->type = 'customer';

        parent::__construct( array(
            'singular' => 'customer',
            'plural'   => 'customers',
            'ajax'     => false
        ) );
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', 'vendor-list-table', $this->_args['plural'] );
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'No customer found!', 'wp-erp-ac' );
    }

    /**
     * Default column values if no callback found
     *
     * @param  object  $item
     * @param  string  $column_name
     *
     * @return string
     */
    function column_default( $item, $column_name ) {

        switch ( $column_name ) {
            case 'customer':
                return $item->first_name . ' ' . $item->last_name;

            case 'company':
                return $item->company;

            case 'email':
                return $item->email;

            case 'phone':
                return $item->phone;

            case 'open_balance':
                return '$0';

            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    /**
     * Get the column names
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb'           => '<input type="checkbox" />',
            'customer'     => __( 'Customer', 'wp-erp-ac' ),
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
        $actions['invoice'] = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=edit&id=' . $item->id ), $item->id, __( 'Create Invoice', 'wp-erp-ac' ), __( 'Create Invoice', 'wp-erp-ac' ) );
        $actions['expense'] = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=edit&id=' . $item->id ), $item->id, __( 'Create Expense', 'wp-erp-ac' ), __( 'Create Expense', 'wp-erp-ac' ) );
        $actions['delete']  = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'wp-erp-ac' ), __( 'Delete', 'wp-erp-ac' ) );

        return get_avatar( $item->email, 32 ) . sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $item->first_name . ' ' . $item->last_name, $this->row_actions( $actions ) );
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'customer' => array( 'first_name', true ),
        );

        return $sortable_columns;
    }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', 'wp-erp-ac' ),
            'email'  => __( 'Send Email', 'wp-erp-ac' ),
        );
        return $actions;
    }

    /**
     * Render the checkbox column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="customer_id[]" value="%d" />', $item->id
        );
    }

    /**
     * Set the views
     *
     * @return array
     */
    public function get_views_() {
        $status_links   = array();
        $base_link      = admin_url( 'admin.php?page=sample-page' );

        foreach ($this->counts as $key => $value) {
            $class = ( $key == $this->page_status ) ? 'current' : 'status-' . $key;
            $status_links[ $key ] = sprintf( '<a href="%s" class="%s">%s <span class="count">(%s)</span></a>', add_query_arg( array( 'status' => $key ), $base_link ), $class, $value['label'], $value['count'] );
        }

        return $status_links;
    }

    /**
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $columns               = $this->get_columns();
        $hidden                = array( );
        $sortable              = $this->get_sortable_columns();
        $this->_column_headers = array( $columns, $hidden, $sortable );

        $per_page              = 20;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'offset' => $offset,
            'number' => $per_page,
            'type'   => $this->type
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        $this->items  = erp_get_peoples( $args );

        $this->set_pagination_args( array(
            'total_items' => erp_get_peoples_count( $this->type ),
            'per_page'    => $per_page
        ) );
    }
}