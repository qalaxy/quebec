<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class SystemError extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'system_errors';
	
	protected $fillable = ['uuid', 'system_id', 'user_id', 'station_id', 'description', 'solution', 'from', 'to', 'date_created', 'time_created', 'error_status_id', 'remarks'];
	
	public function app(){
		return $this->belongsTo('App\System', 'system_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function errorStatus(){
		return $this->belongsTo('App\ErrorStatus', 'error_status_id');
	}
	
	public function systemErrorNotification(){
		return $this->hasMany('App\SystemErrorNotification', 'error_id');
	}
}
