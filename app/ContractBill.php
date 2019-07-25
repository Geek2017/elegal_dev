<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractBill extends Model
{
    public function billInfo()
    {
        return $this->belongsTo('App\Billing', 'billing_id', 'id');
    }
}
