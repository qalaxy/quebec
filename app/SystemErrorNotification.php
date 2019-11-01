<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class SystemErrorNotification extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'system_error_notifications';
	
	protected $fillable = ['uuid', 'error_id', 'user_id', 'email'];
	
	public function error(){
		return $this->belongsTo('App\SystemError', 'error_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
