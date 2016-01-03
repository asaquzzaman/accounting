<div class="wrap erp-ac-bank-account-wrap">
    <h2><?php _e( 'Bank Account', 'accounting' ); ?></h2>

    <div class="bank-accounts">
    <?php
    $banks = WeDevs\ERP\Accounting\Model\Ledger::bank()->with('bank_details')->get();

    foreach ($banks as $bank) {
        ?>
        <div class="bank-account postbox">
            <h3 class="hndle">
                <span class="title erp-ac-bank-name" data-bank_id="<?php echo $bank->id; ?>"><?php echo esc_html( $bank->name ); ?></span>

                <span class="pull-right">
                    <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=erp-accounting-sales&action=new&type=payment&receive_payment=true&bank='.$bank->id); ?>"><?php _e( 'Receive Money', 'erp-accounting' ); ?></a>
                    <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=erp-accounting-expense&action=new&type=payment_voucher&spend_money=true&bank='.$bank->id); ?>"><?php _e( 'Spend Money', 'erp-accounting' ); ?></a>
                    <a class="add-new-h2 erp-ac-transfer-money-btn" href="#"><?php _e( 'Transfer Money', 'erp-accounting' ); ?></a>
                </span>
            </h3>

            <div class="inside">
                <span class="account-number"><?php echo $bank->bank_details->account_number; ?></span>
            </div>
        </div>
    <?php } ?>
    </div>
</div>


