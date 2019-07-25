<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaseManagement extends Model
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date',
    ];


    public function lead_counsel()
    {
        return $this->hasOne('App\Counsel','counsel_id','id');
    }

    public function counselList()
    {
        return $this->hasMany('App\CaseCounsel','case_id','id')->with('info');
    }

    public function fees()
    {
        return $this->hasMany('App\TransactionFeeDetail','case_id','id')->with('fee')->with('serviceReport')->with('counsel');
    }

//    public function serviceReports()
//    {
//        return $this->hasManyThrough('App\ServiceReport','App\TransactionFeeDetail', 'case_id','fee_detail_id','id','id')->with('feeDetail')->with('chargeables');
//    }

    public function serviceReports()
    {
        return $this->hasMany('App\ServiceReport','case_id', 'id')->with('feeDetail')->with('chargeables');
    }

    public function latestServiceReports()
    {
        return $this->serviceReports()
            ->orderBy('created_at', 'DESC')
            ->orderBy('date', 'DESC')
            ->take(1);
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function counsel()
    {
        return $this->belongsTo('App\Counsel');
    }

    public function caseTracker()
    {
        return $this->hasMany('App\CaseTracker')->orderBy('created_at', 'DESC');
    }

    public function specialFees()
    {
        return $this->hasMany('App\TransactionFeeDetail','case_id','id')
            ->where('special_billing', 1)->with('fee');
    }

    public function bills()
    {
        return $this->hasMany('App\ContractBill', 'case_id', 'id')->with('billInfo');
    }
}
