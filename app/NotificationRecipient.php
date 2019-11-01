<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class NotificationRecipient extends Model
{
	use SoftDeletes;
	use HasUuidTrait;
	
    protected $table = 'notification_recipients';
	
	protected $fillable = ['uuid', 'error_notification_id', 'user_id'];
	
	public function errorNotification(){
		return $this->belongsTo('App\ErrorNotification', 'error_notification_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
}
