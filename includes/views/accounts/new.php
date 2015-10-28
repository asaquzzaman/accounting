<div class="wrap">
    <h2><?php _e( 'Add Account', 'erp-accounting' ); ?></h2>

    <form action="" method="post" class="erp-form" id="erp-ac-accounts-form">

        <?php $item = null; ?>

        <?php include dirname( __FILE__ ) . '/form-fields.php'; ?>

        <?php wp_nonce_field( 'erp-ac-chart' ); ?>
        <?php submit_button( __( 'Add Account', 'erp-accounting' ), 'primary', 'submit_erp_ac_chart' ); ?>

    </form>
</div>