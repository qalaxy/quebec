<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ErrorNotification extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'error_notifications';
	
	protected $fillable = ['uuid', 'error_id', 'station_id', 'user_id', 'status'];
	
	public function error(){
		return $this->belongsTo('App\Error', 'error_id');
	}
	
	public function station(){
		return $this->belongsTo('App\Station', 'station_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function message(){
		return $this->hasMany('App\Message', 'error_notification_id');
	}
	
	public function notificationRecipient(){
		return $this->hasMany('App\NotificationRecipient', 'error_notification_id');
	}
}
