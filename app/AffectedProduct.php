<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesMills\Uuid\HasUuidTrait;

class AffectedProduct extends Model
{
	use SoftDeletes;
	use HasUuidTrait;
	
	
    protected $table = 'affected_products';
	
	protected $fillable = ['uuid', 'user_id', 'product_id', 'error_id', 'product_identification', 'remarks'];
	
	public function user(){
		return $this->belongsTo('App\User', 'user_id');
	}
	
	public function product(){
		return $this->belongsTo('App\Product', 'product_id');
	}
	
	public function error(){
		return $this->belongsTo('App\Error', 'error_id');
	}
}
