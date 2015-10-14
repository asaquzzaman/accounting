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
        $classes   = erp_ac_get_chart_classes();
        $all_types = erp_ac_get_all_chart_types_array();
        $selected  = isset( $item->account_type_id ) ? $item->account_type_id : 0;
        ?>
        <select name="account_type_id" id="account_type_id">
            <?php foreach ($all_types as $class_id => $types) { ?>
                <optgroup label="<?php echo esc_attr( $classes[ $class_id ] ); ?>">
                    <?php foreach ($types as $type_id => $type) { ?>
                        <option value="<?php echo $type_id; ?>" <?php selected( $selected, $type_id ); ?>><?php echo $type; ?></option>
                    <?php } ?>
                </optgroup>
            <?php } ?>
        </select>
    </li>

    <li class="erp-form-field row-active">
        <?php erp_html_form_input( array(
            'label'    => __( 'Active', 'erp-accounting' ),
            'name'     => 'active',
            'id'       => 'active',
            'required' => true,
            'type'     => 'select',
            'value'    => isset( $item->active ) ? $item->active : 1,
            'options'  => array(
                '1' => __( 'Active', 'erp-accounting' ),
                '0' => __( 'Inactive', 'erp-accounting' ),
            ),
        ) ); ?>
    </li>

</ol>


<input type="hidden" name="field_id" value="<?php echo isset( $item->id ) ? $item->id : 0; ?>">