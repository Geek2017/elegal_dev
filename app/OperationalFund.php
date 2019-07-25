<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OperationalFund extends Model
{
    public function billing()
    {
    	return $this->belongsTo('App\Billing');
    }    
}
