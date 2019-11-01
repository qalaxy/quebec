<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Account extends Model
{
	use SoftDeletes;
	use HasUuidTrait;
	
    protected $table = 'accounts';
	
	protected $fillable = ['uuid', 'user_id', 'first_name', 'middle_name', 'last_name', 'p_number', 'gender'];
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function accountStation(){
		return $this->hasMany('App\AccountStation', 'account_id');
	}
	
	public function phoneNumber(){
		return $this->belongsToMany('App\PhoneNumber', 'account_phone_num', 'account_id', 'phone_num_id');
	}
	
	public function email(){
		return $this->belongsToMany('App\Email', 'account_email', 'account_id', 'email_id');
	}
}
