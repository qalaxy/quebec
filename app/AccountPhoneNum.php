<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountPhoneNum extends Model
{
    protected $table = 'account_phone_num';
	
	protected $fillable = ['account_id', 'phone_num_id'];
	
	protected $timestamps = false;
}
