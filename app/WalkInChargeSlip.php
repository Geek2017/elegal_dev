<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalkInChargeSlip extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'client_id',
        'charge_slip_no',
        'transaction_date',
        'address',
        'reporter',
        'service_specification',
        'details',
        'total_expenses',
        'professional_fees',
        'total_charges',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'transaction_date',
    ];

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function transactionFees()
    {
        return $this->hasMany('App\TransactionFeeDetail');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }
}
