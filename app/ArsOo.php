<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArsOo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ars_id', 'description', 'outcome_outputcol'
    ];


    public function ars()
    {
        return $this->belongsTo('App\Ars');
    }
}
