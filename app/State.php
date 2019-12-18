<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class State extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'states';
	
	protected $fillable = ['uuid', 'name', 'code', 'description'];
	
	public function systemError(){
		return $this->hasMany('App\State', 'state_id');
	}
	
	public function status(){
		return $this->hasMany('App\Status', 'state_id');
	}
	
	public static function boot(){
		parent::boot();
		State::deleted(function($state){
			$state->systemError()->delete();
			$state->status()->delete();
		});
	}
}
