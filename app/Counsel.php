<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counsel extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function address ()
    {
        return $this->hasOne('App\ContactInfo','counsel_id','id');
    }

    public function profile(){
        return $this->hasOne('App\Profile','counsel_id','id');
    }

    public function cases()
    {
    	return $this->hasMany('App\CaseManagement');
    }
}
