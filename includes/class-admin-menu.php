<?php
namespace WeDevs\ERP\Accounting;

/**
 * Admin Menu
 */
class Admin_Menu {

    /**
     * Kick-in the class
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Register the admin menu
     *
     * @return void
     */
    public function admin_menu() {
        add_menu_page( __( 'Accounting', 'wp-erp' ), __( 'Accounting', 'wp-erp' ), 'manage_options', 'erp-accounting', array( $this, 'dashboard_page' ), 'dashicons-chart-pie', null );

        add_submenu_page( 'erp-accounting', __( 'Dashboard', 'wp-erp' ), __( 'Dashboard', 'wp-erp' ), 'manage_options', 'erp-accounting', array( $this, 'dashboard_page' ) );
        add_submenu_page( 'erp-accounting', __( 'Customers', 'wp-erp' ), __( 'Customers', 'wp-erp' ), 'manage_options', 'erp-accounting-customers', array( $this, 'page_customers' ) );
        add_submenu_page( 'erp-accounting', __( 'Vendors', 'wp-erp' ), __( 'Vendors', 'wp-erp' ), 'manage_options', 'erp-accounting-vendors', array( $this, 'page_vendors' ) );
        add_submenu_page( 'erp-accounting', __( 'Sales', 'wp-erp' ), __( 'Sales', 'wp-erp' ), 'manage_options', 'erp-accounting-invoice', array( $this, 'page_sales' ) );
        add_submenu_page( 'erp-accounting', __( 'Expenses', 'wp-erp' ), __( 'Expenses', 'wp-erp' ), 'manage_options', 'erp-accounting-expense', array( $this, 'page_expenses' ) );
        add_submenu_page( 'erp-accounting', __( 'Chart of Accounts', 'wp-erp' ), __( 'Chart of Accounts', 'wp-erp' ), 'manage_options', 'erp-accounting-charts', array( $this, 'page_chart_of_accounting' ) );
        add_submenu_page( 'erp-accounting', __( 'Sales Tax', 'wp-erp' ), __( 'Sales Tax', 'wp-erp' ), 'manage_options', 'erp-accounting-tax', array( $this, 'page_tax' ) );
        add_submenu_page( 'erp-accounting', __( 'Bank Accounts', 'wp-erp' ), __( 'Bank Accounts', 'wp-erp' ), 'manage_options', 'erp-accounting-bank', array( $this, 'page_bank' ) );
        add_submenu_page( 'erp-accounting', __( 'Reports', 'wp-erp' ), __( 'Reports', 'wp-erp' ), 'manage_options', 'erp-accounting-reports', array( $this, 'page_reports' ) );
    }

    public function dashboard_page() {
        include dirname( __FILE__ ) . '/views/dashboard.php';
    }

    public function page_sales() {
        include dirname( __FILE__ ) . '/views/sales.php';
    }

    public function page_expenses() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $type   = isset( $_GET['type'] ) ? $_GET['type'] : 'pv';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            case 'new':

                if ( $type == 'pv' ) {
                    $template = dirname( __FILE__ ) . '/views/forms/payment-voucher.php';
                }

                break;

            default:
                $template = dirname( __FILE__ ) . '/views/expenses.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function page_chart_of_accounting() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            case 'view':

                $template = dirname( __FILE__ ) . '/views/accounts/single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/accounts/edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/accounts/new.php';
                break;

            default:
                $template = dirname( __FILE__ ) . '/views/chart-of-accounts.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    public function page_tax() {
        include dirname( __FILE__ ) . '/views/tax.php';
    }

    public function page_bank() {
        include dirname( __FILE__ ) . '/views/bank.php';
    }

    public function page_reports() {
        include dirname( __FILE__ ) . '/views/reports.php';
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function page_customers() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            case 'view':

                $template = dirname( __FILE__ ) . '/views/customer/single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/customer/edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/customer/new.php';
                break;

            default:
                $template = dirname( __FILE__ ) . '/views/customer/list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handles the plugin page
     *
     * @return void
     */
    public function page_vendors() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ($action) {
            case 'view':

                $template = dirname( __FILE__ ) . '/views/vendor/single.php';
                break;

            case 'edit':
                $template = dirname( __FILE__ ) . '/views/vendor/edit.php';
                break;

            case 'new':
                $template = dirname( __FILE__ ) . '/views/vendor/new.php';
                break;

            default:
                $template = dirname( __FILE__ ) . '/views/vendor/list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}