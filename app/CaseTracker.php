<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CaseTracker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_management_id',
        'transaction_date',
        'due_date',
        'activities',
        'action_to_take',
        'status',
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
        'due_date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_text', 'formatted_transaction_date', 'formatted_due_date'];


    public function getStatusTextAttribute()
    {
        switch ($this->attributes['status']) {
            case 'D':
                return 'Done';
                break;
            
            default:
                return 'Pending';
                break;
        }
    }

    public function getFormattedTransactionDateAttribute()
    {
        $date = date_create($this->attributes['transaction_date']);

        return date_format($date, 'm/d/Y');
    }

    public function getFormattedDueDateAttribute()
    {
        $date = date_create($this->attributes['due_date']);

        return date_format($date, 'm/d/Y');
    }

    public function caseManagement()
    {
        return $this->belongsTo('App\CaseManagement');
    }
}
