<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Permission extends EntrustPermission
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'permissions';
	
	protected $fillable = ['uuid', 'name', 'display_name', 'description'];
	
	public function role(){
		return $this->belongsToMany('App\Role', 'permission_role', 'permission_id', 'role_id');
	}
}
