<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'title',
        'level',
        'parent',
        'type',
        'category_type',
        'normal_account_balance',
        'is_cash_type',
        'is_cash_account',
        'has_check',
        'is_default_cash_account',
        'is_net_income',
    ];
}
