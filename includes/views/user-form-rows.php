<ol class="erp-form-fields">
    <li class="erp-form-field row-first-name">
        <?php erp_html_form_input( array(
            'label'       => __( 'First Name', 'erp-accounting' ),
            'name'        => 'first_name',
            'id'          => 'first_name',
            'required'    => true,
            'type'        => 'text',
            'placeholder' => __( 'John', 'erp-accounting' ),
            'help'        => __( 'Your first name', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->first_name ) ? $item->first_name : ''
        ) ); ?>
    </li>
    <li class="erp-form-field row-last-name">
        <?php erp_html_form_input( array(
            'label'       => __( 'Last Name', 'erp-accounting' ),
            'name'        => 'last_name',
            'id'          => 'last_name',
            'required'    => true,
            'type'        => 'text',
            'placeholder' => __( 'Doe', 'erp-accounting' ),
            'help'        => __( 'Your Last Name', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->last_name ) ? $item->last_name : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-email">
        <?php erp_html_form_input( array(
            'label'       => __( 'Email', 'erp-accounting' ),
            'name'        => 'email',
            'id'          => 'email',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'you@domain.com', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->email ) ? $item->email : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-company">
        <?php erp_html_form_input( array(
            'label'       => __( 'Compnay', 'erp-accounting' ),
            'name'        => 'company',
            'id'          => 'company',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'ABC Corporation', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->company ) ? $item->company : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-phone">
        <?php erp_html_form_input( array(
            'label'       => __( 'Phone', 'erp-accounting' ),
            'name'        => 'phone',
            'id'          => 'phone',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( '(541) 754-3010', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->phone ) ? $item->phone : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-mobile">
        <?php erp_html_form_input( array(
            'label'    => __( 'Mobile', 'erp-accounting' ),
            'name'     => 'mobile',
            'id'       => 'mobile',
            'required' => false,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->mobile ) ? $item->mobile : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-other">
        <?php erp_html_form_input( array(
            'label'    => __( 'Other', 'erp-accounting' ),
            'name'     => 'other',
            'id'       => 'other',
            'required' => false,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->other ) ? $item->other : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-website">
        <?php erp_html_form_input( array(
            'label'       => __( 'Website', 'erp-accounting' ),
            'name'        => 'website',
            'id'          => 'website',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'http://domain.com', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->website ) ? $item->website : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-fax">
        <?php erp_html_form_input( array(
            'label'    => __( 'Fax', 'erp-accounting' ),
            'name'     => 'fax',
            'id'       => 'fax',
            'required' => false,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->fax ) ? $item->fax : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-notes">
        <?php erp_html_form_input( array(
            'label'       => __( 'Notes', 'erp-accounting' ),
            'name'        => 'notes',
            'id'          => 'notes',
            'required'    => false,
            'type'        => 'textarea',
            'placeholder' => __( 'Some information about this user', 'erp-accounting' ),
            'custom_attr' => array( 'rows' => 5, 'cols' => 30 ),
            'value'       => isset( $item->notes ) ? $item->notes : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-street-1">
        <?php erp_html_form_input( array(
            'label'       => __( 'Street', 'erp-accounting' ),
            'name'        => 'street_1',
            'id'          => 'street_1',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'Street', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->street_1 ) ? $item->street_1 : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-city">
        <?php erp_html_form_input( array(
            'label'       => __( 'City', 'erp-accounting' ),
            'name'        => 'city',
            'id'          => 'city',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'City/Town', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->city ) ? $item->city : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-state">
        <?php erp_html_form_input( array(
            'label'       => __( 'State', 'erp-accounting' ),
            'name'        => 'state',
            'id'          => 'state',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'State/Province', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->state ) ? $item->state : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-postal-code">
        <?php erp_html_form_input( array(
            'label'       => __( 'Post Code', 'erp-accounting' ),
            'name'        => 'postal_code',
            'id'          => 'postal_code',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'Postal Code', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->postal_code ) ? $item->postal_code : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-country">
        <?php erp_html_form_input( array(
            'label'       => __( 'Country', 'erp-accounting' ),
            'name'        => 'country',
            'id'          => 'country',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'Country', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->country ) ? $item->country : '',
        ) ); ?>
    </li>
    <li class="erp-form-field row-currency">
        <?php erp_html_form_input( array(
            'label'       => __( 'User Currency', 'erp-accounting' ),
            'name'        => 'currency',
            'id'          => 'currency',
            'required'    => false,
            'type'        => 'text',
            'placeholder' => __( 'USD', 'erp-accounting' ),
            'class'       => 'regular-text',
            'value'       => isset( $item->currency ) ? $item->currency : '',
        ) ); ?>
    </li>
</ol>