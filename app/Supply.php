<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'category'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function suppliesIn()
    {
        return $this->hasMany('App\SupplyTracker')
            ->whereNotNull('in')
            ->orderBy('created_at');
    }

    public function suppliesOut()
    {
        return $this->hasMany('App\SupplyTracker')
            ->whereNotNull('out')
            ->orderBy('created_at');
    }

    public function latestHistory()
    {
        return $this->hasOne('App\SupplyTracker')
            ->orderBy('created_at', 'DESC');
    }

    public function getTotalInAttribute()
    {
        $totalIn = 0;

        foreach ($this->suppliesIn()->get() as $key => $s) {
            $totalIn += $s->in;
        }

        return $totalIn;
    }

    public function getTotalOutAttribute()
    {
        $totalOut = 0;

        foreach ($this->suppliesOut()->get() as $key => $s) {
            $totalOut += $s->out;
        }

        return $totalOut;
    }
}
