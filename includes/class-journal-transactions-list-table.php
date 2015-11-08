<?php

namespace WeDevs\ERP\Accounting;

if ( ! class_exists ( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List table class
 */
class Journal_Transactions extends \WP_List_Table {

    protected $slug = null;

    function __construct() {

        $this->slug = 'erp-accounting-charts';

        parent::__construct( array(
            'singular' => 'entry',
            'plural'   => 'entries',
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
        _e( 'No entry found!', 'wp-erp-ac' );
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

            case 'transaction_id':
                return $item->transaction_id;

            case 'type':
                $type = $item->type;

                if ( 'expense' == $item->type ) {
                    $type = __( 'Expense', 'erp-accounting' );
                } elseif ( 'sales' == $item->type ) {
                    $type = __( 'Sales', 'erp-accounting' );
                } elseif ( 'journal' == $item->type ) {
                    $type = __( 'Journal Entry', 'erp-accounting' );
                }

                return $type;

            case 'form_type':
                return $item->form_type;

            case 'ref':
                return $item->ref;

            case 'summary':
                return $item->summary;

            case 'debit':
                return $item->debit;

            case 'credit':
                return $item->credit;

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
            'cb'             => '<input type="checkbox" />',
            'issue_date'     => __( 'Date', 'erp-accounting' ),
            'transaction_id' => __( 'Transaction', 'erp-accounting' ),
            'type'           => __( 'Type', 'erp-accounting' ),
            'form_type'      => __( 'Entry Type', 'erp-accounting' ),
            'ref'            => __( 'Ref', 'erp-accounting' ),
            'summary'        => __( 'Summary', 'erp-accounting' ),
            'debit'          => __( 'Debit', 'erp-accounting' ),
            'credit'         => __( 'Credit', 'erp-accounting' ),
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
            'issue_date'     => array( 'issue_date', true ),
            'transaction_id' => array( 'transaction_id', true ),
            'type'           => array( 'type', true ),
            'form_type'      => array( 'form_type', true ),
            'debit'          => array( 'debit', true ),
            'credit'         => array( 'credit', true ),
        );

        return $sortable_columns;
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
            $type       = '';
            $form_type  = '';

            if ( isset( $_REQUEST['start_date'] ) && !empty( $_REQUEST['start_date'] ) ) {
                $start_date = $_REQUEST['start_date'];
            }

            if ( isset( $_REQUEST['end_date'] ) && !empty( $_REQUEST['end_date'] ) ) {
                $end_date = $_REQUEST['end_date'];
            }

            if ( isset( $_REQUEST['type'] ) && !empty( $_REQUEST['type'] ) ) {
                $type = $_REQUEST['type'];
            }

            if ( isset( $_REQUEST['form_type'] ) && !empty( $_REQUEST['form_type'] ) ) {
                $form_type = $_REQUEST['form_type'];
            }

            erp_html_form_input([
                'name'        => 'type',
                'value'       => $type,
                'type'       => 'select',
                'options' => [
                    ''        => __( 'All Types', 'erp-accounting' ),
                    'sales'   => __( 'Sales', 'erp-accounting' ),
                    'expense' => __( 'Expense', 'erp-accounting' ),
                    'journal' => __( 'Journal Entries', 'erp-accounting' )
                ],
                'placeholder' => __( 'Start Date', 'erp-accounting' )
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
     * Set the bulk actions
     *
     * @return array
     */
    function get_bulk_actions() {
        $actions = array(
            // 'trash'  => __( 'Move to Trash', 'wp-erp-ac' ),
            // 'email'  => __( 'Send Email', 'wp-erp-ac' ),
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
     * Prepare the class items
     *
     * @return void
     */
    function prepare_items() {

        $ledger_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
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
            'offset'  => $offset,
            'number'  => $per_page,
            'orderby' => 'issue_date',
            'order'   => 'DESC'
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        if ( isset( $_REQUEST['start_date'] ) && !empty( $_REQUEST['start_date'] ) ) {
            $args['start_date'] = $_REQUEST['start_date'];
        }

        if ( isset( $_REQUEST['end_date'] ) && !empty( $_REQUEST['end_date'] ) ) {
            $args['end_date'] = $_REQUEST['end_date'];
        }

        if ( isset( $_REQUEST['type'] ) && !empty( $_REQUEST['type'] ) ) {
            $args['type'] = $_REQUEST['type'];
        }

        if ( isset( $_REQUEST['form_type'] ) && !empty( $_REQUEST['form_type'] ) ) {
            $args['form_type'] = $_REQUEST['form_type'];
        }

        $this->items  = erp_ac_get_ledger_transactions( $ledger_id, $args );

        // count = -1
        $args['number'] = '-1';
        $all_items = erp_ac_get_ledger_transactions( $args );

        $this->set_pagination_args( array(
            'total_items' => count( $all_items ),
            'per_page'    => $per_page
        ) );
    }
}