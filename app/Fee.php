<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fee extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function category(){
        return $this->belongsTo('App\FeeCategory','category_id','id');
    }

    public function description(){
        return $this->hasMany('App\FeeDescription','fee_id','id');
    }
}
