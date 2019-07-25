<?php

namespace App;

class Business extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client', 'business');
    }

    public function address()
    {
        return $this->hasOne('App\ContactInfo', 'business_id', 'id')->where('type', 'permanent_address');
    }

    public function telephone()
    {
        return $this->hasOne('App\ContactInfo', 'business_id', 'id')->where('type', 'telephone');
    }

    public function mobile()
    {
        return $this->hasOne('App\ContactInfo', 'business_id', 'id')->where('type', 'mobile');
    }
}