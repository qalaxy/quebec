<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ErrorCorrection extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'error_corrections';
	
	protected $fillable = ['uuid', 'error_id', 'user_id', 'originator_id', 'station_id', 'source', 'corrective_action', 'cause', 'remarks'];
	
	public function error(){
		return $this->belongsTo('App\Error', 'error_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function originator(){
		return $this->belongsTo('App\User', 'originator_id');
	}
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function supervisorReaction(){
		return $this->hasMany('App\SupervisorReaction', 'error_correction_id');
	}
	
	public function originatorReaction(){
		return $this->hasMany('App\OriginatorReaction', 'error_correction_id');
	}
	
	public function status(){
		return $this->belongsToMany('App\Status', 'error_correction_status', 'error_correction_id', 'status_id');
	}
	
	public function aioError(){
		return $this->hasOne('App\AioError', 'error_correction_id');
	}
	
	public function externalError(){
		return $this->hasOne('App\ExternalError', 'error_correction_id');
	}
	
	public static function boot(){
		parent::boot();
		ErrorCorrection::deleted(function($error_correction){
			$error_correction->supervisorReaction()->delete();
			$error_correction->originatorReaction()->delete();
			$error_correction->aioError()->delete();
			$error_correction->externalError()->delete();
		});
	}
}
