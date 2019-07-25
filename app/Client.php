<?php

namespace App;

class Client extends Model
{

    public function profile(){
        return $this->hasOne('App\Profile','client_id','id');
    }

    public function business(){
        return $this->hasOne('App\Business', 'client_id', 'id')
            ->whereRaw("
                    (
                      main = 1
                      OR 
                      (billing = 0 OR billing = 1)
                    )
                ")
            ->with('telephone')
            ->with('mobile')
            ->with('address');
    }

    public function billingAddress(){
        return $this->hasOne('App\Business', 'client_id', 'id')
            ->whereRaw("
                    (
                      main = 0
                      AND 
                      billing = 1
                    )
                ")
            ->with('telephone')
            ->with('mobile')
            ->with('address');
    }

    public function transaction(){
        return $this->hasMany('App\Transaction','client_id','id');
    }

    public function ars()
    {
        return $this->belongsTo('App\Ars');
    }

    // do not delete this is use in billing and trust fund ledger report
    public function latestTrustFund()
    {
        return $this->hasOne('App\TrustFund', 'client_id', 'id')
            ->orderBy('created_at', 'DESC');
    }

}