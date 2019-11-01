<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Func extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'functions';
	
	protected $fillble = ['uuid','name','description'];
	
	public function product(){
		return $this->belongsToMany('App\Product', 'function_product', 'function_id', 'product_id');
	}
	
	public function station(){
		return $this->belongsToMany('App\Station', 'station_function', 'function_id', 'station_id');
	}
	
	public function func(){
		return $this->hasMany('App\Func', 'function_id');
	}
}
