<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ars extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_management_id',
        'case_project_name',
        'case_project_name',
        'docket_no_venue',
        'reporter',
        'gr_title',
        'client_id',
        'ars_date',
        'time_start',
        'time_finnish',
        'duration',
        'sr_no',
        'service_report_id',
        'billing_instruction',
        'billing_entry',
        'billing_instruction_type',
        'billable_time',
        'ars_no',
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'ars_date',
    ];

    public function getArsNoAttribute()
    {
        if (!isset($this->attributes['ars_no'])) {
            return 'NO ARS #';
        }

        $date = new Carbon($this->attributes['created_at']);
        $prefixArsNo = $date->format('y').'-'.$date->format('m');

        return $prefixArsNo .'-'. str_pad($this->attributes['ars_no'], 5, '0', STR_PAD_LEFT);
    }

    public function client()
    {
        return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function ads()
    {
        // return $this->hasMany('App\ArsAd','ars_id','id');
        return $this->hasMany('App\ArsAd');
    }

    public function oos()
    {
        return $this->hasMany('App\ArsOo');
    }

    public function fads()
    {
        return $this->hasMany('App\ArsFad');
    }

    public function serviceReport()
    {
        return $this->belongsTo('App\ServiceReport');
    }

    public function case()
    {
        return $this->belongsTo('App\CaseManagement', 'case_management_id', 'id');
    }
}
