<div class="wrap erp-ac-journal-entry">
    <h2><?php _e( 'New Journal Entry', 'erp-accounting' ); ?></h2>

    <form action="" method="post" class="erp-form" id="erp-journal-form">

        <ul class="erp-form-fields">
            <li class="erp-form-field row-ref">
                <?php
                erp_html_form_input( array(
                    'label' => __( 'Reference', 'erp-accounting' ),
                    'name'  => 'ref',
                    'type'  => 'text',
                    'addon' => '#',
                ) );
                ?>
            </li>
            <li class="erp-form-field row-issue-date">
                <?php erp_html_form_input( array(
                    'label'    => __( 'Date', 'erp-accounting' ),
                    'name'     => 'issue_date',
                    'id'       => 'issue_date',
                    'required' => true,
                    'type'     => 'text',
                    'class'    => 'erp-date-field',
                    'value'    => date( 'Y-m-d', current_time( 'timestamp' ) ),
                ) ); ?>
            </li>
            <li class="erp-form-field row-summary">
                <?php erp_html_form_input( array(
                    'label'       => __( 'Summary', 'erp-accounting' ),
                    'name'        => 'summary',
                    'id'          => 'summary',
                    'required'    => true,
                    'type'        => 'textarea',
                    'placeholder' => __( 'Summary', 'erp-accounting' ),
                    'custom_attr' => array( 'rows' => 5, 'cols' => 30 ),
                ) ); ?>
            </li>
        </ul>

        <table class="erp-table erp-ac-transaction-table journal-table">
            <thead>
                <tr>
                    <th class="col-chart"><?php _e( 'Account', 'erp-accounting' ); ?></th>
                    <th class="col-desc"><?php _e( 'Description', 'erp-accounting' ); ?></th>
                    <th class="col-amount"><?php _e( 'Debit', 'erp-accounting' ); ?></th>
                    <th class="col-amount"><?php _e( 'Credit', 'erp-accounting' ); ?></th>
                    <th class="col-action">&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="col-chart">
                        <?php echo erp_ac_get_chart_dropdown([
                            'required' => true,
                            'name'     => 'line_account[]'
                        ] ); ?>
                    </td>
                    <td class="col-desc">
                        <?php
                        erp_html_form_input( array(
                            'name'  => 'line_desc[]',
                            'type'  => 'text'
                        ) );
                        ?>
                    </td>
                    <td class="col-amount">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_debit[]',
                            'type'        => 'number',
                            'class'       => 'line_debit',
                            'placeholder' => '0.00'
                        ) );
                        ?>
                    </td>
                    <td class="col-amount">
                        <?php
                        erp_html_form_input( array(
                            'name'        => 'line_credit[]',
                            'type'        => 'number',
                            'class'       => 'line_credit',
                            'placeholder' => '0.00'
                        ) );
                        ?>
                    </td>
                    <td class="col-action">
                        <a href="#" class="remove-line"><span class="dashicons dashicons-trash"></span></a>
                        <a href="#" class="move-line"><span class="dashicons dashicons-menu"></span></a>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th><a href="#" class="button add-line"><?php _e( '+ Add Line', 'erp-accounting' ); ?></a></th>
                    <th class="align-right"><?php _e( 'Total', 'erp-accounting' ); ?></th>
                    <th class="col-amount">
                        <input type="number" name="debit_total" class="debit-price-total" readonly value="0.00">
                    </th>
                    <th class="col-amount">
                        <input type="number" name="credit_total" class="credit-price-total" readonly value="0.00">
                    </th>
                    <th class="col-diff">0.00</th>
                </tr>
            </tfoot>
        </table>


        <input type="hidden" name="erp-action" value="ac-new-journal-entry">

        <?php wp_nonce_field( 'erp-ac-journal-entry' ); ?>
        <?php submit_button( __( 'Add Journal Entry', 'erp-accounting' ), 'primary', 'submit_erp_ac_journal' ); ?>

    </form>
</div>