<?php
/**
 * Plugin Name: WP ERP - Accounting Module
 * Description: Accounting solution for WP ERP
 * Plugin URI: http://wedevs.com/plugin/erp/
 * Author: Tareq Hasan
 * Author URI: http://wedevs.com
 * Version: 1.0
 * License: GPL2
 * Text Domain: erp-accounting
 * Domain Path: languages
 *
 * Copyright (c) 2014 Tareq Hasan (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WeDevs_ERP_Accounting class
 *
 * @class WeDevs_ERP_Accounting The class that holds the entire WeDevs_ERP_Accounting plugin
 */
class WeDevs_ERP_Accounting {

    /**
     * @var string
     */
    public $version = '0.1';

    /**
     * Initializes the WeDevs_ERP_Accounting() class
     *
     * Checks for an existing WeDevs_ERP_Accounting() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Constructor for the WeDevs_ERP_Accounting class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @return void
     */
    public function __construct() {

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        // register the module
        add_filter( 'erp_get_modules', array( $this, 'register_module' ) );

        // load the module
        add_action( 'wp-erp-load-module_erp-accounting', array( $this, 'plugin_init' ) );
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'erp-accounting', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Init the accounting module
     *
     * @return void
     */
    public function plugin_init() {
        // Define constants
        $this->define_constants();

        // Include required files
        $this->includes();

        $this->init_actions();
    }

    /**
     * Define the plugin constants
     *
     * @return void
     */
    private function define_constants() {
        define( 'WPERP_ACCOUNTING_PATH', dirname( __FILE__ ) );
        define( 'WPERP_ACCOUNTING_URL', plugins_url( '', __FILE__ ) );
        define( 'WPERP_ACCOUNTING_ASSETS', WPERP_ACCOUNTING_URL . '/assets' );
    }

    /**
     * Include the required files
     *
     * @return void
     */
    private function includes() {

    }

    /**
     * Init the plugin actions
     *
     * @return void
     */
    public function init_actions() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Register the accounting module
     *
     * @param  array $modules the array of registered modules
     *
     * @return array updated module list
     */
    public function register_module( $modules ) {
        $modules['accounting'] = array(
            'title'   => __( 'Accounting', 'wp-erp' ),
            'slug'    => 'erp-accounting',
            'modules' => apply_filters( 'erp_accounting_modules', array() )
        );

        return $modules;
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
        add_submenu_page( 'erp-accounting', __( 'Sales', 'wp-erp' ), __( 'Sales', 'wp-erp' ), 'manage_options', 'erp-accounting-invoices', array( $this, 'page_invoices' ) );
        add_submenu_page( 'erp-accounting', __( 'Purchases', 'wp-erp' ), __( 'Purchases', 'wp-erp' ), 'manage_options', 'erp-accounting-purchases', array( $this, 'page_purchase' ) );
        add_submenu_page( 'erp-accounting', __( 'Chart of Accounts', 'wp-erp' ), __( 'Chart of Accounts', 'wp-erp' ), 'manage_options', 'erp-accounting-charts', array( $this, 'page_chart_of_accounting' ) );
        add_submenu_page( 'erp-accounting', __( 'Sales Tax', 'wp-erp' ), __( 'Sales Tax', 'wp-erp' ), 'manage_options', 'erp-accounting-tax', array( $this, 'page_tax' ) );
        add_submenu_page( 'erp-accounting', __( 'Bank Accounts', 'wp-erp' ), __( 'Bank Accounts', 'wp-erp' ), 'manage_options', 'erp-accounting-bank', array( $this, 'page_bank' ) );
        add_submenu_page( 'erp-accounting', __( 'Reports', 'wp-erp' ), __( 'Reports', 'wp-erp' ), 'manage_options', 'erp-accounting-reports', array( $this, 'page_reports' ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'wp-erp-acc-styles', WPERP_ACCOUNTING_ASSETS . '/css/accounting.css', false, date( 'Ymd' ) );
    }

    public function dashboard_page() {
        include dirname( __FILE__ ) . '/views/dashboard.php';
    }

    public function page_customers() {
        include dirname( __FILE__ ) . '/views/customers.php';
    }

    public function page_vendors() {
        include dirname( __FILE__ ) . '/views/vendors.php';
    }

    public function page_invoices() {
        include dirname( __FILE__ ) . '/views/invoices.php';
    }

    public function page_purchase() {
        include dirname( __FILE__ ) . '/views/purchases.php';
    }

    public function page_chart_of_accounting() {
        include dirname( __FILE__ ) . '/views/chart-of-accounts.php';
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

} // WeDevs_ERP_Accounting


WeDevs_ERP_Accounting::init();