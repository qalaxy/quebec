<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorStatus extends Model
{
    protected $table = 'error_status';
	
	protected $fillable = ['error_id','status_id'];
	
	protected $timestamps = false;
}
