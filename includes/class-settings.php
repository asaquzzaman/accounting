<?php
namespace WeDevs\ERP\Accounting;

/**
 * General class
 */
class Settings extends \ERP_Settings_Page {


    function __construct() {
        $this->id       = 'accounting';
        $this->label    = __( 'Accounting', 'erp' );
        $this->sections = $this->get_sections();
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {

        $fields['general'] = array(

            array( 'title' => __( 'General Options', 'erp-accounting' ), 'type' => 'title', 'desc' => '', 'id' => 'general_options' ),

            array(
                'title'   => __( 'Home Currency', 'erp-accounting' ),
                'id'      => 'erp_ac_base_currency',
                'desc'    => __( 'The base currency of the system.', 'erp-accounting' ),
                'type'    => 'select',
                'options' => erp_get_currencies()
            ),

            array( 'type' => 'sectionend', 'id' => 'script_styling_options' ),

        ); // End general settings


        $section = ( $section === false ) ? $fields['general'] : $fields[$section];

        return $section;
    }

    /**
     * Get sections
     *
     * @return array
     */
    public function get_sections() {

        $sections = array(
            'general' => __( 'General Settings', 'erp' ),
        );

        return apply_filters( 'erp_get_sections_' . $this->id, $sections );
    }
}

return new Settings();