<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pdf'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'bill_date',
    ];

    public function client()
    {
        return $this->hasOne('App\Client','id','client_id')
            ->with('profile')
            ->with('billingAddress')
            ->with('business');
    }

    public function serviceReports()
    {
        return $this->hasMany('App\ServiceReport','billing_id','id')
            ->with('feeDetail')
            ->with('chargeables');
    }

    public function prevBalance()
    {
        return $this->hasMany('App\Billing','merged_to','id');
    }

    public function operationalFund()
    {
        return $this->hasOne('App\OperationalFund','billing_id','id');
    }

    public function trustFund()
    {
        return $this->hasOne('App\TrustFund', 'billing_id', 'id');
    }

    public function transactionFeeDetails()
    {
        return $this->hasMany('App\TransactionFeeDetail', 'billing_id', 'id');
    }
}
