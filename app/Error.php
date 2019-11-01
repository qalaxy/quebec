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
	
	protected $fillable = ['uuid', 'user_id', 'function_id', 'station_id', 'date_created', 'time_created', 'description', 'impact'];
	
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
		return $this->hasMany('App\AffectedProduct', 'user_id');
	}
	
	public function errorNotification(){
		return $this->hasMany('App\ErrorNotification', 'error_id');
	}
	
	public function errorCorrection(){
		return $this->hasOne('App\ErrorCorrection', 'error_id');
	}
}
