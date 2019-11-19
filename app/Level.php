<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use JamesMills\Uuid\HasUuidTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'levels';
	
	protected $fillable = ['uuid', 'name', 'order'];
	
	public function user(){
		return $this->hasMany('App\User', 'level_id');
	}
	
	public function role(){
		return $this->hasMany('App\Role', 'level_id');
	}
	
	public function permission(){
		return $this->hasMany('App\Permission', 'level_id');
	}
}
