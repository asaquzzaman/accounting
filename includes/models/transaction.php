<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Transaction extends Model {
    protected $primaryKey = 'id';
    protected $table      = 'erp_ac_transactions';
    public $timestamps    = false;
    protected $fillable   = [ 'type', 'form_type', 'status', 'user_id', 'billing_address', 'ref', 'summary', 'issue_date', 'due_date', 'currency', 'conversion_rate', 'total', 'trans_total', 'files', 'created_by', 'created_at'];

    public function items() {
        return $this->hasMany( 'WeDevs\ERP\Accounting\Model\Transaction_Items', 'transaction_id' );
    }

    public function journals() {
        return $this->hasMany( 'WeDevs\ERP\Accounting\Model\Journal', 'transaction_id' );
    }

    public function scopeType( $query, $type = 'expense' ) {
        return $query->where( 'type', '=', $type );
    }
}