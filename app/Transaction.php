<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'client_id',
        'status',
    ];

    public function user()
    {
        return $this->hasOne('App\User','id','author');
    }

    public function client()
    {
        return $this->hasOne('App\Client','id','client_id')->with('profile')->with('business');
    }

    public function contract()
    {
        return $this->hasOne('App\Contract','transaction_id','id')->with('caseDetails');
    }

    public function fees()
    {
        return $this->hasMany('App\TransactionFeeDetail','transaction_id','id')->with('fee')->with('serviceReport');
    }

    public function cases()
    {
        return $this->hasMany('App\CaseManagement','transaction_id','id')->with('fees')->with('counselList')->with('serviceReports');
    }

    public function srGeneral()
    {
        return $this->hasManyThrough('App\ServiceReport','App\TransactionFeeDetail', 'transaction_id','fee_detail_id','id','id')->with('feeDetail')->with('chargeables');
    }

    public function srSpecial()
    {
        return $this->hasMany('App\CaseManagement','transaction_id','id')->with('serviceReports');
    }

    public function servicesReport()
    {
        return $this->hasOne('App\ServiceReport')->orderBy('date', 'Desc');
    }
}