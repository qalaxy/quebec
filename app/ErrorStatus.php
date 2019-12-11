<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ErrorStatus extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'error_status';
	
	protected $fillable = ['uuid', 'name', 'code', 'description'];
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'error_status_id');
	}
	
	public function error(){
		return $this->hasMany('App\Error', 'error_status_id');
	}
	
	public static function boot(){
		parent::boot();
		ErrorStatus::deleted(function($error_status){
			$error_status->systemError()->delete();
			$error_status->error()->delete();
		});
	}
}
