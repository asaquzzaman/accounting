<ol class="erp-form-fields">

    <li class="erp-form-field row-name">
        <?php erp_html_form_input( array(
            'label'    => __( 'Name', 'erp-accounting' ),
            'name'     => 'name',
            'id'       => 'name',
            'required' => true,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->name ) ? $item->name : '',
        ) ); ?>
    </li>

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

    <li class="erp-form-field row-currency">
        <?php erp_html_form_input( [
            'label'    => __( 'Currency', 'erp-accounting' ),
            'name'     => 'currency',
            'id'       => 'currency',
            'type'     => 'select',
            'value'    => isset( $item->currency ) ? $item->currency : 'USD',
            'options'  => erp_get_currencies(),
        ] ); ?>
    </li>

    <li class="erp-form-field row-balance hide_if_income hide_if_expense">
        <?php erp_html_form_input( array(
            'label'    => __( 'Opening Balance', 'erp-accounting' ),
            'name'     => 'balance',
            'id'       => 'balance',
            'required' => true,
            'type'     => 'text',
            'class'    => 'regular-text',
            'value'    => isset( $item->balance ) ? $item->balance : '0.00',
        ) ); ?>
    </li>

    <li class="erp-form-field row-date hide_if_income hide_if_expense">
        <?php erp_html_form_input( array(
            'label'    => __( 'On Date', 'erp-accounting' ),
            'name'     => 'date',
            'id'       => 'date',
            'required' => true,
            'type'     => 'text',
            'class'    => 'erp-date-field',
            'value'    => isset( $item->date ) ? $item->date : date( 'Y-m-d', current_time( 'timestamp' ) ),
        ) ); ?>
    </li>

    <?php if ( isset( $item->id ) ) { ?>

        <li class="erp-form-field row-active">
            <?php erp_html_form_input( [
                'label'    => __( 'Status', 'erp-accounting' ),
                'name'     => 'active',
                'id'       => 'active',
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


<input type="hidden" name="field_id" value="<?php echo isset( $item->id ) ? $item->id : 0; ?>">