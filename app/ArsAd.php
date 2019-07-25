<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArsAd extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ars_id', 'description'
    ];
    
    public function ars()
    {
        return $this->belongsTo('App\Ars');
    }
}
