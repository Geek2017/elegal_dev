<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrustFund extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function bill()
    {
        return $this->hasOne('App\Billing', 'id', 'billing_id');
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id')->with('profile');
    }
}
