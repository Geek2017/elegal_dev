<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chargeable extends Model
{
    public function fee()
    {
        return $this->belongsTo('App\Fee','fee_id','id');
    }

    public function serviceReport()
    {
        return $this->belongsTo('App\ServiceReport','sr_id','id')->with('feeDetail');
    }
}
