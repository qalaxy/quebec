<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class ErrorStatus extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'error_status';
	
	protected $fillable = ['uuid', 'name', 'code', 'description'];
	
	public function systemError(){
		return $this->hasMany('App\SystemError', 'error_status_id');
	}
}
