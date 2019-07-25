<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IcoeInfo extends Model
{
    public function contact(){
        return $this->hasMany('App\ContactInfo','icoe_id','id');
    }
}
