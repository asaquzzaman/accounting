<ul class="form-fields erp-list no-style">

    <li class="erp-form-field">
        <?php
        erp_html_form_input( array(
            'label'       => __( 'Memo', 'erp-accounting' ),
            'name'        => 'summary',
            'placeholder' => __( 'Internal information', 'erp-accounting' ),
            'type'        => 'textarea',
            'custom_attr' => [
                'rows' => 3,
                'cols' => 45
            ]
        ) );
        ?>
    </li>

    <li class="erp-form-field erp-ac-attachment-field">
        <label for="attachments"><?php _e( 'Attachments', '$domain' ); ?></label>

        <div class="erp-ac-attachment-wrap">
            <div class="erp-ac-upload-filelist"></div>
            To attach, <a id="erp-ac-upload-pickfiles" href="#">select files</a> from your computer.
        </div>
    </li>
</ul>