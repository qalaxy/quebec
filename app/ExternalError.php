<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ExternalError extends Model
{
    protected $table = 'external_errors';
	
	protected $fillable = ['uuid', 'error_id', 'user_id', 'description'];
	
	public function error(){
		return $this->belongsTo('App\Error', 'error_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
