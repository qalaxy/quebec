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
	
	protected $fillable = ['uuid', 'user_id', 'first_name', 'middle_name', 'last_name', 'p_number'];
	
	public function owner(){
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
	
	public function user(){
		return $this->belongsToMany('App\User', 'account_user', 'account_id', 'user_id');
	}
	
	public function supervisor(){
		return $this->hasMany('App\Supervisor', 'account_id');
	}
	
	public static function boot(){
		parent::boot();
		Account::deleted(function($account){
			$account->accountStation()->delete();
			$account->supervisor()->delete();
		});
	}
	
}
