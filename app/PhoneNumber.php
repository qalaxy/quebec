<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class PhoneNumber extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'phone_numbers';
	
	protected $fillable = ['uuid', 'number'];
	
	public function account(){
		return $this->belongsToMany('App\Account', 'account_phone_num', 'phone_num_id', 'account_id');
	}
	
	public function station(){
		return $this->belongsToMany('App\Station', 'station_phone_num', 'phone_num_id', 'station_id');
	}
}
