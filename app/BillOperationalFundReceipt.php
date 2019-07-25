<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillOperationalFundReceipt extends Model
{
    public function cashReceipt()
    {
        return $this->belongsTo('App\CashReceipt', 'cash_receipt_id', 'id');
    }

    public function billing()
    {
        return $this->belongsTo('App\Billing', 'billing_id', 'id');
    }

    public function operationalFund()
    {
        return $this->belongsTo('App\OperationalFund', 'operational_fund_id', 'id');
    }
}
