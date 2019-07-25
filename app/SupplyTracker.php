<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplyTracker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supply_id', 'in', 'out', 'balance', 'short', 'remarks'
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

    public function supply()
    {
        return $this->belongsTo('App\Suppy');
    }
}
