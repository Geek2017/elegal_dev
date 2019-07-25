<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    public function client()
    {
        return $this->hasOne('App\Client','id','client_id')->with('profile');
    }

    public function transaction()
    {
        return $this->hasOne('App\Transaction','id','transaction_id')->with('user');
    }

    public function feeDetail()
    {
        return $this->hasOne('App\TransactionFeeDetail','contract_id','id')->with('fee');
    }

    public function detail()
    {
        return $this->hasOne('App\TransactionFeeDetail','contract_id','id')->with('fee');
    }

    // error this has to be hasMany
    public function caseDetail()
    {
        return $this->hasOne('App\CaseManagement','transaction_id','transaction_id');
    }

    public function caseDetails()
    {
        return $this->hasMany('App\CaseManagement','transaction_id','transaction_id')->with('serviceReports');
    }

    public function specialFees()
    {
        return $this->hasMany('App\TransactionFeeDetail','transaction_id','transaction_id')
            ->where('special_billing', 1)->with('fee');
    }

    public function serviceReports()
    {
        return $this->hasMany('App\ServiceReport','transaction_id','transaction_id')->with('feeDetail')->with('chargeables');
    }

    public function contractBills()
    {
        return $this->hasMany('App\ContractBill', 'transaction_id', 'transaction_id')->with('billInfo');
    }

}
