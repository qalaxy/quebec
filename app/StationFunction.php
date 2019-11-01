<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StationFunction extends Model
{
    protected $table = 'station_function';
	
	protected $fillable = ['station_id', 'function_id'];
	
	protected $timestamps = false;
}
