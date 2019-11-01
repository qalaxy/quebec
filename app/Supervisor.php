<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;


class Supervisor extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'supervisors';
	
	protected $fillable = ['uuid', 'station_id', 'user_id', 'from', 'to', 'status'];
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
