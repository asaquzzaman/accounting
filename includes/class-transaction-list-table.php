<?php
namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Transaction_List_Table extends \WP_List_Table {

    protected $type = '';
    protected $slug = '';

    function __construct() {
        parent::__construct( array(
            'singular' => 'transaction',
            'plural'   => 'transactions',
            'ajax'     => false
        ) );
    }

    function get_table_classes() {
        return array( 'widefat', 'fixed', 'striped', $this->_args['plural'] );
    }

    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items() {
        _e( 'No transaction found!', 'erp-accounting' );
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
            case 'issue_date':
                return $item->issue_date;

            case 'form_type':
                return $item->form_type;

            case 'user_id':
                return $item->user_id;

            case 'total':
                return $item->total;

            case 'status':
                return $item->status;

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
            'cb'         => '<input type="checkbox" />',
            'user_id'    => __( 'From', 'erp-accounting' ),
            'form_type'  => __( 'Type', 'erp-accounting' ),
            'ref'        => __( 'Ref', 'erp-accounting' ),
            'issue_date' => __( 'Date', 'erp-accounting' ),
            'total'      => __( 'Total', 'erp-accounting' ),
            'status'     => __( 'Status', 'erp-accounting' ),
        );

        return $columns;
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array( 'name', true ),
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
            'trash'  => __( 'Move to Trash', 'erp-accounting' ),
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
            '<input type="checkbox" name="transaction_id[]" value="%d" />', $item->id
        );
    }

    public function column_user_id( $item ) {
        $url               = admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id );
        $user_display_name = '';
        $actions           = array();
        $actions['view']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $item->id, __( 'View this transaction', 'erp-accounting' ), __( 'View', 'erp-accounting' ) );
        // $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'erp-accounting' ), __( 'Delete', 'erp-accounting' ) );

        if ( ! $item->user_id ) {
            $user_display_name = __( '(no vendor)', 'erp-accounting' );
        }

        return sprintf( '<a href="%1$s"><strong>%2$s</strong></a> %3$s', $url, $user_display_name, $this->row_actions( $actions ) );
    }

    public function column_form_type( $item ) {
        $types = erp_ac_get_expense_form_types();

        if ( array_key_exists( $item->form_type, $types ) ) {
            return sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $types[ $item->form_type ]['label'] );
        }
    }

    /**
     * Render the issue date column
     *
     * @param  object  $item
     *
     * @return string
     */
    function column_issue_date( $item ) {
        return sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $item->issue_date );
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

        $per_page              = 25;
        $current_page          = $this->get_pagenum();
        $offset                = ( $current_page -1 ) * $per_page;
        $this->page_status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '2';

        // only ncessary because we have sample data
        $args = array(
            'type'   => $this->type,
            'offset' => $offset,
            'number' => $per_page,
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        $this->items  = erp_ac_get_all_transaction( $args );

        $this->set_pagination_args( array(
            'total_items' => erp_ac_get_transaction_count( $this->type ),
            'per_page'    => $per_page
        ) );
    }
}