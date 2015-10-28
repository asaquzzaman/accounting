<ol class="erp-form-fields">

    <li class="erp-form-field row-account-type-id">
        <label for="account_type_id"><?php _e( 'Account Type', 'erp-accounting' ); ?></label>

        <?php
        $custom_attr = isset( $item->id ) ? [ 'disabled' => 'disabled' ] : [];
        $classes     = erp_ac_get_chart_classes();
        $all_types   = erp_ac_get_all_chart_types_array();
        $selected    = isset( $item->type_id ) ? $item->type_id : 0;
        ?>
        <select name="account_type_id" id="account_type_id" <?php echo isset( $item->id ) ? 'disabled="disabled"' : ''; ?>>
            <?php foreach ($all_types as $class_id => $types) { ?>
                <optgroup label="<?php echo esc_attr( $classes[ $class_id ] ); ?>">
                    <?php foreach ($types as $type_id => $type) { ?>
                        <option value="<?php echo $type_id; ?>" <?php selected( $selected, $type_id ); ?>><?php echo $type; ?></option>
                    <?php } ?>
                </optgroup>
            <?php } ?>
        </select>
    </li>

    <li class="erp-form-field row-name">
        <?php erp_html_form_input( array(
            'label'    => __( 'Account Code', 'erp-accounting' ),
            'name'     => 'code',
            'required' => true,
            'type'     => 'number',
            'class'    => 'small-text',
            'help'    => __( 'A unique code/number for this account', 'erp-accounting' ),
            'value'    => isset( $item->code ) ? $item->code : '',
        ) ); ?>
    </li>

    <li class="erp-form-field row-name">
        <?php erp_html_form_input( array(
            'label'    => __( 'Name', 'erp-accounting' ),
            'name'     => 'name',
            'required' => true,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->name ) ? $item->name : '',
        ) ); ?>
    </li>

    <li class="erp-form-field row-description">
        <?php erp_html_form_input( array(
            'label'    => __( 'Description', 'erp-accounting' ),
            'name'     => 'description',
            'required' => false,
            'type'     => 'textarea',
            'value'    => isset( $item->description ) ? $item->description : '',
            'custom_attr' => [
                'rows' => 3,
                'cols' => 45
            ]
        ) ); ?>
    </li>

    <li class="show_if_bank">
        <h3><?php _e( 'Bank Details', 'erp-accounting' ); ?></h3>
    </li>

    <li class="erp-form-field row-name show_if_bank">
        <?php erp_html_form_input( array(
            'label'    => __( 'Account Number', 'erp-accounting' ),
            'name'     => 'bank[account_number]',
            'required' => false,
            'type'     => 'text',
            'class'    => 'regular-text',
        ) ); ?>
    </li>

    <li class="erp-form-field row-name show_if_bank">
        <?php erp_html_form_input( array(
            'label'    => __( 'Bank Name', 'erp-accounting' ),
            'name'     => 'bank[bank_name]',
            'required' => false,
            'type'     => 'text',
            'class'    => 'regular-text',
        ) ); ?>
    </li>

    <?php if ( isset( $item->id ) ) { ?>

        <li class="erp-form-field row-active">
            <?php erp_html_form_input( [
                'label'    => __( 'Status', 'erp-accounting' ),
                'name'     => 'active',
                'type'     => 'select',
                'value'    => isset( $item->active ) ? $item->active : 1,
                'options'  => [
                    '1' => __( 'Active', 'erp-accounting' ),
                    '0' => __( 'Inactive', 'erp-accounting' ),
                ],
            ] ); ?>
        </li>

    <?php } ?>

</ol>

<script type="text/javascript">
    jQuery(function($) {
        $( '#account_type_id' ).on('change', function() {
            if ( $(this).val() === '6' ) {
                $( '.show_if_bank' ).show();
            } else {
                $( '.show_if_bank' ).hide();
            }
        });

        $( '#account_type_id' ).trigger('change');
    });
</script>


<input type="hidden" name="field_id" value="<?php echo isset( $item->id ) ? $item->id : 0; ?>">