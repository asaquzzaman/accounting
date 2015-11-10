<?php
namespace WeDevs\ERP\Accounting;

/**
 * General class
 */
class Settings extends \ERP_Settings_Page {


    function __construct() {
        $this->id       = 'accounting';
        $this->label    = __( 'Accounting', 'erp' );
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_settings() {

        $fields = array(

            array( 'title' => __( 'Accounting Settings', 'erp-accounting' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

            array(
                'title'   => __( 'Home Currency', 'erp-accounting' ),
                'id'      => 'base_currency',
                'desc'    => __( 'The base currency of the system.', 'erp-accounting' ),
                'type'    => 'select',
                'options' => erp_get_currencies()
            ),
            array(
                'title'   => __( 'Date Format', 'erp-accounting' ),
                'id'      => 'date_format',
                'desc'    => __( 'Format of date to show accross accounting system.', 'erp-accounting' ),
                'tooltip' => true,
                'type'    => 'select',
                'options' => [
                    'm-d-Y' => 'mm-dd-yyyy',
                    'd-m-Y' => 'dd-mm-yyyy',
                    'm/d/Y' => 'mm/dd/yyyy',
                    'd/m/Y' => 'dd/mm/yyyy',
                    'Y-m-d' => 'yyyy-mm-dd',
                ]
            ),

            array( 'type' => 'sectionend', 'id' => 'script_styling_options' ),

        ); // End general settings


        return apply_filters( 'erp_ac_settings_general', $fields );
    }
}

return new Settings();