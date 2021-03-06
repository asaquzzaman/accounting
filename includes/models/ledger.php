<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Ledger extends Model {
    protected $primaryKey = 'id';
    protected $table      = 'erp_ac_ledger';
    public $timestamps    = false;
    protected $fillable   = [ 'code', 'name', 'type_id', 'currency', 'cash_account', 'reconcile', 'active' ];

    public function scopeBank( $query ) {
        return $query->where( 'cash_account', '=', 1 );
    }

    public function scopeActive( $query ) {
        return $query->where( 'active', 1 );
    }

    public function scopeCode( $query, $code = '' ) {
        return $query->where( 'code', $code );
    }

    public function bank_details() {
        return $this->hasOne( 'WeDevs\ERP\Accounting\Model\Bank', 'ledger_id', 'id' );
    }
}