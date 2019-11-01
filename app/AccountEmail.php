<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountEmail extends Model
{
    protected $table = 'account_email';
	
	protected $fillable = ['accout_id', 'email_id'];
	
	protected $timestamps = false;
}
