<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use JamesMills\Uuid\HasUuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
	use EntrustUserTrait{ restore as private restoreA; }
	use HasUuidTrait;
	use SoftDeletes{ restore as private restoreB; }
	
	public function restore()
	{
		$this->restoreA();
		$this->restoreB();
	}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'name', 'email', 'password', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	
	public function role(){
		return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
	}
	
	public function owner(){
		return $this->hasMany('App\Account', 'user_id');
	}
	
	public function account(){
		return $this->belongsToMany('App\Account', 'account_user', 'user_id', 'account_id');
	}
	
	public function error(){
		return $this->hasMany('App\Error', 'user_id');
	}
	
	public function affectedProduct(){
		return $this->hasMany('App\AffectedProduct', 'user_id');
	}
	
	public function errorNotification(){
		return $this->hasMany('App\ErrorNotification', 'user_id');
	}
	
	public function message(){
		return $this->hasMany('App\Message', 'user_id');
	}
	
	public function notificationRecipient(){
		return $this->hasMany('App\NotificationRecipient', 'user_id');
	}
	
	public function errorCorrectionUser(){
		return $this->hasMany('App\ErrorCorrection', 'user_id');
	}
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'user_id');
	}
	
	public function systemErrorNotification(){
		return $this->hasMany('App\SystemErrorNotification', 'user_id');
	}
	
	public function tracker(){
		return $this->hasMany('App\Tracker', 'user_id');
	}
	
	public function roleOwn(){
		return $this->hasMany('App\Role', 'owner_id');
	}
	
	public function aioError(){
		return $this->hasMany('App\AioError', 'user_id');
	}
	
	public function aioErrorOriginator(){
		return $this->hasMany('App\AioError', 'originator_id');
	}
	public function externalError(){
		return $this->hasMany('App\ExternalError', 'user_id');
	}
	
	public function recipient(){
		return $this->hasMany('App\Recipient', 'user_id');
	}
	
	public function status(){
		return $this->hasMany('App\Status', 'user_id');
	}

	public function supervisorReaction(){
		return $this->hasMany('App\SupervisorReaction', 'user_id');
	}
	
	public static function boot(){
		parent::boot();
		
		User::deleted(function($user){
			$user->owner()->delete();
			$user->error()->delete();
			$user->affectedProduct()->delete();
			$user->errorNotification()->delete();
			$user->message()->delete();
			$user->notificationRecipient()->delete();
			$user->errorCorrectionUser()->delete();
			$user->systemError()->delete();
			$user->systemErrorNotification()->delete();
			$user->tracker()->delete();
			$user->roleOwn()->delete();
			$user->aioError()->delete();
			$user->aioErrorOriginator()->delete();
			$user->externalError()->delete();
			$user->recipient()->delete();
			$user->status()->delete();
			$user->supervisorReaction()->delete();
		});
	}
}
