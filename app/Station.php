<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Station extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'stations';
	
	protected $fillable = ['uuid', 'office_id', 'name', 'abbreviation'];
	
	public function office(){
		return $this->belongsTo('App\Office', 'office_id');
	}
	
	public function accountStation(){
		return $this->hasMany('App\AccountStation', 'station_id');
	}
	
	public function phoneNumber(){
		return $this->belongsToMany('App\PhoneNumber', 'station_phone_num', 'station_id', 'phone_number_id');
	}
	
	public function email(){
		return $this->belongsToMany('App\Email', 'station_email', 'station_id', 'email_id');
	}
	
	public function func(){
		return $this->belongsToMany('App\Func', 'station_function', 'station_id', 'function_id');
	}
	
	public function reportedError(){
		return $this->hasMany('App\Error', 'reported_station_id');
	}
	
	public function reportingError(){
		return $this->hasMany('App\Error', 'reporting_station_id');
	}
	
	public function errorNotification(){
		return $this->hasMany('App\ErrorNotification', 'station_id');
	}
	
	public function errorCorrection(){
		return $this->hasMany('App\ErrorCorrection', 'station_id');
	}
	
	public function recipient(){
		return $this->hasMany('App\Recipient', 'station_id');
	}
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'station_id');
	}
	
	public function supervisor(){
		return $this->hasMany('App\Supervisor', 'station_id');
	}
	
	public function role(){
		return $this->belongsToMany('App\Role', 'role_station', 'station_id', 'role_id');
	}
	
	public static function boot(){
		parent::boot();
		Station::deleted(function($station){
			$station->accountStation()->delete();
			$station->error()->delete();
			$station->errorNotification()->delete();
			$station->errorCorrection()->delete();
			$station->recipient()->delete();
			$station->systemError()->delete();
			$station->supervisor()->delete();
		});
	}
	
}
