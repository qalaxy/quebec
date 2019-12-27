<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Status extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'status';
	
	protected $fillable = ['uuid', 'state_id', 'user_id', 'remarks'];
	
	public function state(){
		return $this->belongsTo('App\State', 'state_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function error(){
		return $this->belongsToMany('App\Error', 'error_status', 'status_id', 'error_id');
	}
	
	public function errorCorrection(){
		return $this->belongsToMany('App\ErrorCorrection', 'error_correction_status', 'status_id', 'error_correction_id');
	}
	
	public function supervisorReaction(){
		return $this->hasOne('App\SupervisorReaction', 'status_id');
	}
}
