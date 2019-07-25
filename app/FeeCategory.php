<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{

    const SPECIAL = 1;
    const GENERAL = 2;
    const CHARGABLE_EXPENSE = 3;

    public function fees(){
        return $this->hasMany('App\Fee', 'category_id','id');
    }
}
