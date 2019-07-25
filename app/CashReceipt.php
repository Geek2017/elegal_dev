<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashReceipt extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id',
        'transaction_id',
        'billing_id',
        'walk_in_charge_slip_id',
        'client_id',
        'cash_receipt_no',
        'payment_date',
        'amount_due',
        'amount_paid',
        'change',
        'balance',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'payment_date',
        'created_at',
        'updated_at',
    ];

    public function getAmountPaidAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function billings()
    {
        return $this->belongsToMany('App\Billing', 'bill_operational_fund_receipts', 'cash_receipt_id', 'billing_id');
    }

    public function operationalFunds()
    {
        return $this->belongsToMany('App\OperationalFund', 'bill_operational_fund_receipts', 'cash_receipt_id', 'operational_fund_id');
    }

    public function billOperationalFundReceipt()
    {
        return $this->hasMany('App\BillOperationalFundReceipt', 'cash_receipt_id', 'id');
    }
}
