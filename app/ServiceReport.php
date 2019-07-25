<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceReport extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'date',
    ];

    public function cases()
    {
        return $this->belongsTo('App\CaseManagement');
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }

    public function transactionDetail()
    {
        return $this->belongsTo(TransactionFeeDetail::class,'transaction_fee_detail_id');
    }

    public function feeDetail(){
        return $this->belongsTo('App\TransactionFeeDetail','fee_detail_id','id')
            ->with('fee')
            ->with('transaction');
    }

    public function chargeables(){
        return $this->hasMany('App\Chargeable','sr_id','id')->with('fee');
    }

    public function client()
    {
        return $this->belongsTo('App\Client','client_id', 'id');
    }

    // user in TransactionController::343
    public function case()
    {
        return $this->belongsTo('App\CaseManagement');
    }
}
   
