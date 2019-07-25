<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id',
        'user_id',
        'firstname',
        'lastname',
        'middlename',
        'status',
        'blood_type',
        'image',
        'dob',
    ];
  
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];


    public function getFullNameAttribute()
    {
        $middleName = (isset($this->attributes['middlename'])) ? $this->attributes['middlename']: '';

        return $this->attributes['lastname'] . ", " . $this->attributes['firstname'] . " " . $middleName;
    }
	
    public function contact(){
        return $this->hasMany('App\ContactInfo','profile_id','id');
    }

    public function client(){
        return $this->belongsTo('App\Client');
    }
}
