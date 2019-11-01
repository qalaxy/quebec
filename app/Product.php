<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class Product extends Model
{
    use SoftDeletes;
	use HasUuidTrait;
	
	protected $table = 'products';
	
	protected $fillable = ['uuid', 'name', 'description'];
	
	public function func(){
		return $this->belongsToMany('App\Func', 'function_product', 'product_id', 'function_id');
	}
	
	public function affectedProduct(){
		return $this->hasMany('App\AffectedProduct', 'user_id');
	}
}
