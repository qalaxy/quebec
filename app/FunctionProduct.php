<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionProduct extends Model
{
    protected $table = 'function_product';
	
	protected $fillable = ['function_id', 'product_id'];
	
	protected $timestamps = false;
}
