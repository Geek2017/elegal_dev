<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $fillable = [
        'subject','action','model', 'url', 'method', 'ip', 'agent', 'user_id'
    ];

    public function user(){
        return $this->hasOne('App\User','id','user_id')->with('profile');
    }
}
