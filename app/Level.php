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
	
	
	public function permission(){
		return $this->hasMany('App\Permission', 'level_id');
	}
	
	public static function boot(){
		parent::boot();
		Level::deleted(function($level){
			$level->permission()->delete();
		});
	}
}
