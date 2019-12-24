<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Error extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'errors';
	
	protected $fillable = ['uuid', 'user_id', 'function_id', 'station_id', 'number', 'description', 'impact', 'state_id', 'remarks', 'responsibility'];

	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function func(){
		return $this->belongsTo('App\Func', 'function_id');
	}
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function affectedProduct(){
		return $this->hasMany('App\AffectedProduct', 'error_id');
	}
	
	public function errorNotification(){
		return $this->hasMany('App\ErrorNotification', 'error_id');
	}
	
	public function errorCorrection(){
		return $this->hasOne('App\ErrorCorrection', 'error_id');
	}
	
	public function status(){
		return $this->belongsToMany('App\Status', 'error_status', 'error_id', 'status_id');
	}
	
	public static function boot(){
		parent::boot();
		Error::deleted(function($error){
			$error->affectedProduct()->delete();
			$error->errorNotification()->delete();
			$error->errorCorrection()->delete();
		});
	}
}
