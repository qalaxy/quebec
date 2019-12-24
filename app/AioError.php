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
	
	protected $fillable = ['uuid', 'error_correction_id', 'user_id', 'originator_id'];
	
	public function errorCorrection(){
		return $this->belongsTo('App\ErrorCorrection', 'error_correction_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function errorOriginator(){
		return $this->belongsTo('App\User', 'originator_id');
	}
}
