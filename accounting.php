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

        // installation
        register_activation_hook( __FILE__, array( $this, 'activate' ) );

        // Localize our plugin
        add_action( 'init', array( $this, 'localization_setup' ) );

        // register the module
        add_filter( 'erp_get_modules', array( $this, 'register_module' ) );

        // load the module
        add_action( 'erp_loaded', array( $this, 'plugin_init' ) );

        // plugin not installed notice
        add_action( 'admin_notices', array( $this, 'admin_notice' ) );
    }

    /**
     * Plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = new WeDevs\ERP\Accounting\Install();
        $installer->install();
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
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions-transaction.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions-chart.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/functions.php';
        require_once WPERP_ACCOUNTING_PATH . '/includes/function-dashboard.php';
    }

    /**
     * Initialize the classes
     *
     * @return void
     */
    public function init_classes() {
        new WeDevs\ERP\Accounting\Admin_Menu();
        new WeDevs\ERP\Accounting\Form_Handler();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new WeDevs\ERP\Accounting\Ajax_Handler();
        }
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
     * Init the plugin filters
     *
     * @return void
     */
    public function init_filters() {
        add_filter( 'erp_settings_pages', array( $this, 'add_settings_page' ) );
    }

    public function enqueue_scripts() {
        // styles
        wp_enqueue_style( 'wp-erp-ac-styles', WPERP_ACCOUNTING_ASSETS . '/css/accounting.css', false, date( 'Ymd' ) );

        // scripts
        wp_enqueue_script( 'accounting', WPERP_ACCOUNTING_ASSETS . '/js/accounting.min.js', [ 'jquery' ], date( 'Ymd' ), true );
        wp_enqueue_script( 'wp-erp-ac-js', WPERP_ACCOUNTING_ASSETS . '/js/erp-accounting.js', [ 'jquery' ], date( 'Ymd' ), true );
        wp_localize_script( 'wp-erp-ac-js', 'ERP_AC', [
            'nonce' => wp_create_nonce( 'erp-ac-nonce' )
        ] );
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

    /**
     * Give notice if ERP is not installed
     *
     * @return void
     */
    public function admin_notice() {
        if ( ! function_exists( 'wperp' ) ) {
            echo '<div class="message"><p>';
            echo __( 'Error: WP ERP Plugin is required to use accounting plugin.', 'domain' );
            echo '</p></div>';
        }
    }

} // WeDevs_ERP_Accounting


WeDevs_ERP_Accounting::init();