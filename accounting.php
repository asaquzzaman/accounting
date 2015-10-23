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

include dirname( __FILE__ ) . '/vendor/autoload.php';

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
     */
    public function __construct() {

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        // register the module
        add_filter( 'erp_get_modules', array( $this, 'register_module' ) );

        // switch redirect
        add_filter( 'erp_switch_redirect_to', array( $this, 'module_switch_redirect' ), 10, 2 );

        add_filter( 'erp_settings_pages', array( $this, 'add_settings_page' ) );

        // load the module
        // add_action( 'wp-erp-load-module_erp-accounting', array( $this, 'plugin_init' ) );
        $this->plugin_init();
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

        $this->init_classes();
        $this->init_actions();
        $this->init_filters();
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
        require_once WPERP_ACCOUNTING_PATH . '/includes/class-form-handler.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/class-customer-list-table.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/class-vendor-list-table.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/class-expense-transaction-list-table.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/class-admin-menu.php';

        require_once WPERP_ACCOUNTING_PATH . '/includes/functions-customer.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions-transaction.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions-chart.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions.php';
    }

    public function init_classes() {
        new WeDevs\ERP\Accounting\Admin_Menu();
        new WeDevs\ERP\Accounting\Form_Handler();
    }

    /**
     * Init the plugin actions
     *
     * @return void
     */
    public function init_actions() {
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Init the plugin actions
     *
     * @return void
     */
    public function init_filters() {

    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'wp-erp-ac-styles', WPERP_ACCOUNTING_ASSETS . '/css/accounting.css', false, date( 'Ymd' ) );
        wp_enqueue_script( 'wp-erp-ac-js', WPERP_ACCOUNTING_ASSETS . '/js/accounting.js', [ 'jquery' ], date( 'Ymd' ), true );
    }

    /**
     * Redirect to the accounting dashboard page when switching from admin menu bar
     *
     * @param  string  redirect url
     * @param  string  new mode slug
     *
     * @return string  new url to redirect to
     */
    function module_switch_redirect( $url, $new_mode ) {
        if ( 'accounting' == $new_mode ) {
            return admin_url( 'admin.php?page=erp-accounting' );
        }

        return $url;
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

    public function add_settings_page( $settings = [] ) {

        $settings[] = include __DIR__ . '/includes/class-settings.php';

        return $settings;
    }

} // WeDevs_ERP_Accounting


WeDevs_ERP_Accounting::init();