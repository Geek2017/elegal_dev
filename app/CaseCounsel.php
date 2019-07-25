<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseCounsel extends Model
{
    public function info()
    {
        return $this->belongsTo('App\Counsel','counsel_id','id')->with('profile');
    }
}
