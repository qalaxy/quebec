<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class System extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'systems';
	
	protected $fillable = ['uuid', 'name', 'abbreviation', 'description'];
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'system_id');
	}
	
	public static function boot(){
		parent::boot();
		System::deleted(function($system){
			$system->systemError()->delete();
		});
	}
}
