<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Message extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'messages';
	
	protected $fillable = ['uuid', 'error_notification_id', 'user_id', 'text'];
	
	public function errorNotification(){
		return $this->belongsTo('App\ErrorNotification', 'error_notification_id');
	}
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function message(){
		return $this->belongsToMany('App\Message', 'message_response', 'message_id', 'response_id');
	}
	
	public function response(){
		return $this->belongsToMany('App\Message', 'message_response', 'response_id', 'message_id');
	}
}
