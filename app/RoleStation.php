<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleStation extends Model
{
    protected $table = 'role_station';
	
	protected $fillable = ['role_id', 'station_id'];
	
	public $timestamps = false;
}
