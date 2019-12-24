<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ExternalError extends Model
{
    protected $table = 'external_errors';
	
	protected $fillable = ['uuid', 'error_correction_id', 'user_id', 'description'];
	
	public function errorCorrection(){
		return $this->belongsTo('App\ErrorCorrection', 'error_correction_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
