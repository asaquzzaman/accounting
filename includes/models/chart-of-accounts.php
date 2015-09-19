<?php
namespace WeDevs\ERP\Accounting\Model;

use WeDevs\ERP\Framework\Model;

class Chart_Of_Accounts extends Model {
    protected $primaryKey = 'id';
    protected $table = 'erp_ac_charts';
    protected $fillable = [ 'name', 'parent', 'account_type_id', 'balance' ];
}