<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionFeeDetail extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id', 'transaction_id', 'walk_in_charge_slip_id', 'case_id', 'client_id', 'contract_id',
        'fee_id', 'counsel_id', 'charge_type', 'special_billing', 'fee_page', 'excess_rate', 'cap_value', 'minutes',
        'installment', 'percentage', 'amount', 'total', 
    ];


    public function fee()
    {
        return $this->belongsTo('App\Fee','fee_id','id')->with('description');
    }

    public function cases()
    {
        return $this->belongsTo('App\CaseManagement','case_id','id');
    }
    public function serviceReport()
    {
        return $this->hasMany('App\ServiceReport','fee_detail_id','id');
    }
    public function chargeables()
    {
        return $this->hasMany(Chargeable::class);
    }
    public function transaction()
    {
        return $this->belongsTo('App\Transaction','transaction_id','id')->with('client');
    }
    public function contract()
    {
        return $this->belongsTo('App\Contract','transaction_id','transaction_id')->with('client');
    }
    public function counsel()
    {
        return $this->belongsTo('App\Counsel', 'counsel_id', 'id')->with('profile');
    }
}
