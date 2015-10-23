<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Journal extends Model {
    protected $primaryKey = 'id';
    protected $table = 'erp_ac_journals';
    public $timestamps = false;
    protected $fillable = [ 'ledger_id', 'transaction_id', 'debit', 'credit' ];

    public function ledger() {
        return $this->hasOne( 'WeDevs\ERP\Accounting\Model\Ledger', 'id', 'ledger_id' );
    }
}