<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Role extends EntrustRole
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'roles';
	
	protected $fillable = ['uuid', 'name', 'display_name', 'description'];
	
	public function user(){
		return $this->belongsToMany('App\User', 'role_user', 'role_id', 'user_id');
	}
	
	public function permission(){
		return $this->belongsToMany('App\Permission', 'permission_role', 'role_id', 'permission_id');
	}
}
