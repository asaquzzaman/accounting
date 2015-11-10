<?php $country = \WeDevs\ERP\Countries::instance(); ?>

<ul class="erp-list two-col separated">
    <li><?php erp_print_key_value( __( 'Name', 'erp-accounting' ), $customer->get_full_name() ); ?></li>
    <li><?php erp_print_key_value( __( 'Email', 'erp-accounting' ), erp_get_clickable( 'email', $customer->get_email() ) ); ?></li>
    <li><?php erp_print_key_value( __( 'Phone', 'erp-accounting' ), $customer->phone ); ?></li>
    <li><?php erp_print_key_value( __( 'Mobile', 'erp-accounting' ), $customer->mobile ); ?></li>
    <li><?php erp_print_key_value( __( 'Fax', 'erp-accounting' ), $customer->fax ); ?></li>
    <li><?php erp_print_key_value( __( 'Website', 'erp-accounting' ), $customer->website ); ?></li>
</ul>

<hr>
<strong><?php _e( 'Address', 'erp-accounting' ); ?></strong><br>
<?php echo $country->get_formatted_address( [
    'address_1' => $customer->street_1,
    'address_2' => '',
    'city'      => $customer->city,
    'state'     => $customer->state,
    'postcode'  => $customer->postcode,
    'country'   => $customer->country,
]); ?>