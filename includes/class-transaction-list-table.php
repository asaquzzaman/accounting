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
                return erp_ac_format_date( $item->issue_date );

            case 'due_date':
                return !empty( $item->due_date ) ? erp_ac_format_date( $item->due_date ) : '&mdash;';

            case 'form_type':
                return $item->form_type;

            case 'user_id':
                return $item->user_id;

            case 'due':
                return number_format_i18n( $item->due, 2 );

            case 'total':
                return $item->total;

            case 'status':
                return erp_ac_get_status_label( $item->status );

            default:
                return isset( $item->$column_name ) && !empty( $item->$column_name ) ? $item->$column_name : '&mdash;';
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
            'issue_date' => __( 'Date', 'erp-accounting' ),
            'form_type'  => __( 'Type', 'erp-accounting' ),
            'user_id'    => __( 'Vendor', 'erp-accounting' ),
            'ref'        => __( 'Ref', 'erp-accounting' ),
            'due'        => __( 'Due', 'erp-accounting' ),
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
            'issue_date' => array( 'issue_date', true ),
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
        // $actions['view']   = sprintf( '<a href="%s" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), $item->id, __( 'View this transaction', 'erp-accounting' ), __( 'View', 'erp-accounting' ) );
        // $actions['delete'] = sprintf( '<a href="%s" class="submitdelete" data-id="%d" title="%s">%s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=delete&id=' . $item->id ), $item->id, __( 'Delete this item', 'erp-accounting' ), __( 'Delete', 'erp-accounting' ) );

        if ( ! $item->user_id ) {
            $user_display_name = __( '(no vendor)', 'erp-accounting' );
        } else {
            $transaction = \WeDevs\ERP\Accounting\Model\Transaction::find( $item->id );
            $user_display_name = $transaction->user->first_name . ' ' . $transaction->user->last_name;
        }

        return sprintf( '<a href="%1$s">%2$s</a> %3$s', $url, $user_display_name, $this->row_actions( $actions ) );
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
        return sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->slug . '&action=view&id=' . $item->id ), erp_ac_format_date( $item->issue_date ) );
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

    public function get_form_types() {
        return [];
    }

    /**
     * Filters
     *
     * @param  string  $which
     *
     * @return void
     */
    public function extra_tablenav( $which ) {
        if ( 'top' == $which ) {
            echo '<div class="alignleft actions">';

            $start_date = '';
            $end_date   = '';

            if ( isset( $_REQUEST['start_date'] ) && !empty( $_REQUEST['start_date'] ) ) {
                $start_date = $_REQUEST['start_date'];
            }

            if ( isset( $_REQUEST['end_date'] ) && !empty( $_REQUEST['end_date'] ) ) {
                $end_date = $_REQUEST['end_date'];
            }

            $all_types = $this->get_form_types();
            $types = [];

            foreach ($all_types as $key => $type) {
                $types[ $key ] = $type['label'];
            }

            erp_html_form_input([
                'name'    => 'form_type',
                'type'    => 'select',
                'options' => [ '' => __( 'All Types', 'erp-accounting' ) ] + $types
            ]);

            erp_html_form_input([
                'name'        => 'user_id',
                'type'        => 'hidden',
                'class'       => 'erp-ac-customer-search',
                'placeholder' => __( 'Search for Customer', 'erp-accounting' ),
            ]);

            erp_html_form_input([
                'name'        => 'start_date',
                'class'       => 'erp-date-field',
                'value'       => $start_date,
                'placeholder' => __( 'Start Date', 'erp-accounting' )
            ]);

            erp_html_form_input([
                'name'        => 'end_date',
                'class'       => 'erp-date-field',
                'value'       => $end_date,
                'placeholder' => __( 'End Date', 'erp-accounting' )
            ]);

            submit_button( __( 'Filter' ), 'button', 'submit_filter_sales', false );

            echo '</div>';
        }
    }

    /**
     * Get all transactions
     *
     * @param  array  $args
     *
     * @return array
     */
    protected function get_transactions( $args ) {
        return erp_ac_get_all_transaction( $args );
    }

    /**
     * Get transaction count
     *
     * @param  array  $args
     *
     * @return int
     */
    protected function get_transaction_count( $args ) {
        return erp_ac_get_transaction_count( $args['type'] );
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

        // search params
        if ( isset( $_REQUEST['start_date'] ) && !empty( $_REQUEST['start_date'] ) ) {
            $args['start_date'] = $_REQUEST['start_date'];
        }

        if ( isset( $_REQUEST['end_date'] ) && !empty( $_REQUEST['end_date'] ) ) {
            $args['end_date'] = $_REQUEST['end_date'];
        }

        $this->items = $this->get_transactions( $args );

        $this->set_pagination_args( array(
            'total_items' => $this->get_transaction_count( $args ),
            'per_page'    => $per_page
        ) );
    }
}