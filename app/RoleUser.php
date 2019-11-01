<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
	
	protected $fillable = ['role', 'user_id'];
	
	protected $timestamps = false;
	
	
}
