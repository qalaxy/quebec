<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class AioError extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'aio_errors';
	
	protected $fillable = ['uuid', 'error_id', 'user_id', 'originator_id'];
	
	public function error(){
		return $this->belongsTo('App\Error', 'error_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function errorOriginator(){
		return $this->belongsTo('App\User', 'originator_id');
	}
}
