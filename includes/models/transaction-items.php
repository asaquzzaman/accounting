<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Transaction_Items extends Model {
    protected $primaryKey = 'id';
    protected $table = 'erp_ac_transaction_items';
    public $timestamps = false;
    protected $fillable = [ 'transaction_id', 'journal_id', 'product_id', 'description', 'qty', 'unit_price', 'discount', 'tax', 'line_total', 'order'];

    public function journal() {
        return $this->hasOne( 'WeDevs\ERP\Accounting\Model\Journal', 'id', 'journal_id' );
    }
}