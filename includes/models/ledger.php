<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Ledger extends Model {
    protected $primaryKey = 'id';
    protected $table = 'erp_ac_ledger';
    public $timestamps = false;
    protected $fillable = [ 'code', 'name' ];
}