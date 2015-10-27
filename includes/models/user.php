<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class User extends Model {
    protected $primaryKey = 'id';
    protected $table      = 'erp_ac_customers';
    public $timestamps    = false;
    // protected $fillable = [ 'transaction_id', 'journal_id', 'product_id', 'description', 'qty', 'unit_price', 'discount', 'tax', 'line_total', 'order'];

    public function transactions() {
        return $this->hasMany( 'WeDevs\ERP\Accounting\Model\Transaction', 'id', 'user_id' );
    }
}