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
	
	protected $fillable = ['uuid', 'error_id', 'user_id', 'originator_id', 'station_id', 'date_created', 'time_created', 'corrective_action', 'cause', 'remarks'];
	
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
}
