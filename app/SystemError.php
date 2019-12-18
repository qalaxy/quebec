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
	
	protected $fillable = ['uuid', 'system_id', 'user_id', 'station_id', 'description', 'solution', 'from', 'to', 'date_created', 'time_created', 'state_id', 'remarks'];
	
	public function app(){
		return $this->belongsTo('App\System', 'system_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function state(){
		return $this->belongsTo('App\State', 'state_id');
	}
	
	public function systemErrorNotification(){
		return $this->hasMany('App\SystemErrorNotification', 'error_id');
	}
	
	public static function boot(){
		parent::boot();
		SystemError::deleted(function($system_error){
			$system_error->systemErrorNotification()->delete();
		});
	}
}
