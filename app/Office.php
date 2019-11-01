<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Office extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'offices';
	
	protected $fillable = ['uuid', 'name', 'description'];
	
	public function station(){
		return $this->hasMany('App\Station', 'office_id');
	}
}
