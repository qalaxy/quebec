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
        'uuid', 'name', 'email', 'password',
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
	
	public function account(){
		return $this->hasOne('App\Account', 'user_id');
	}
	
	public function supervisor(){
		return $this->hasMany('App\Supervisor', 'user_id');
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
	
	public function errorCorrectionOriginator(){
		return $this->hasMany('App\ErrorCorrection', 'originator_id');
	}
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'user_id');
	}
	
	public function systemErrorNotification(){
		return $this->hasMany('App\SystemErrorNotification', 'user_id');
	}
}
